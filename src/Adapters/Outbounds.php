<?php

declare(strict_types=1);

namespace FluxEco\GlobalStream\Adapters;

use FluxEco\GlobalStream\Adapters;
use FluxEco\GlobalStream\Core;
use FluxEco\GlobalStream\Core\Ports;
use fluxValueObject;
use fluxStorage;
use fluxMessageServer;
use fluxProjection;
use Exception;

class Outbounds implements Core\Ports\Outbounds
{

    private string $streamStorageConfigEnvPrefix;
    private string $streamTableName;
    private array $streamStateSchema;
    private string $streamPublishedMessagesTableName;
    private array $streamPublishedMessagesSchema;
    private array $channels;

    private function __construct(
        string $streamStorageConfigEnvPrefix,
        string $streamTableName,
        string $streamStateSchemaFile,
        string $streamPublishedMessagesTableName,
        string $streamPublishedMessagesSchemaFile,
        array $channels
    ) {
        $this->streamStorageConfigEnvPrefix = $streamStorageConfigEnvPrefix;
        $this->streamTableName = $streamTableName;
        $this->streamStateSchema = yaml_parse(file_get_contents($streamStateSchemaFile));
        $this->streamPublishedMessagesTableName = $streamPublishedMessagesTableName;
        $this->streamPublishedMessagesSchema = yaml_parse(file_get_contents($streamPublishedMessagesSchemaFile));
        $this->channels = $channels;
    }

    public static function new(
        string $streamStorageConfigEnvPrefix,
        string $streamTableName,
        string $streamStateSchemaFile,
        string $streamPublishedMessagesTableName,
        string $streamPublishedMessagesSchemaFile,
        array $channels
    ) : self {
        return new self($streamStorageConfigEnvPrefix, $streamTableName, $streamStateSchemaFile, $streamPublishedMessagesTableName, $streamPublishedMessagesSchemaFile, $channels);
    }


    public function getCurrentTime() : string
    {
        return fluxValueObject\getCurrentTime();
    }

    public function getNewUuid() : string
    {
        return fluxValueObject\getNewUuid();
    }

    public function publishStateChanged(Core\Domain\StateChanged $state) {
        /*
        fluxProjection\receiveAggregateRootStatePublished(
            $state->getSubjectId(),
            $state->getSubjectName(),
            $state->getEventName(),
            $state->getPayload()
        );
        */
    }

    public function createGlobalStreamStorages() : void
    {
        //if (array_key_exists(self::SEQUENCE_COLUMN_NAME, $jsonSchema['properties']) === false) {
        //    throw new \Exception('An global stream storage schema MUST contain a ' . self::SEQUENCE_COLUMN_NAME . ' column!');
        //}
        fluxStorage\createStorage($this->streamTableName, $this->streamStateSchema, $this->streamStorageConfigEnvPrefix);
        fluxStorage\createStorage($this->streamPublishedMessagesTableName, $this->streamPublishedMessagesSchema, $this->streamStorageConfigEnvPrefix);
    }

    public function storeState(
        string $correlationId,
        string $createdBy,
        string $createdDateTime,
        string $channel,
        string $subjectId,
        int $subjectSequence,
        string $subject,
        string $subjectName,
        string $eventName,
        string $currentState
    ) : void {
        $data = get_defined_vars();
        fluxStorage\appendData($this->streamTableName, $this->streamStateSchema, $data, $this->streamStorageConfigEnvPrefix);
    }

    /**
     * @return Core\Domain\StateChanged[]
     */
    public function queryStates(string $channelName) : array
    {
        $filter = ['channel' => $channelName];
        $result = fluxStorage\getData($this->streamTableName, $this->streamStateSchema, $this->streamStorageConfigEnvPrefix, $filter);
        return Storage\GlobalStreamStatesAdapter::fromQueryResult($result)->toStates();
    }

    public function getServerConfig(string $serverName) : Core\Domain\Models\ServerConfig
    {
        $asyncApi = $this->config->getAsyncApi();
        $serverConfig = $asyncApi['servers'][$serverName];

        return Core\Domain\Models\ServerConfig::new(
            $serverConfig['url'],
            $serverConfig['port']
        );
    }


    private function getServerNamesForChannel(string $channel): array {
        if(key_exists('$channel',  $this->channels['channels']) === false) {
            return [];
        }
        $channel = $this->channels['channels'][$channel];
        if(key_exists('subscribers', $channel) === false) {
            return [];
        }

        return $channel['subscribers'];
    }


    public function notify(string $channel)  : void
    {
        $serverNames = $this->getServerNamesForChannel($channel);
        if(count($serverNames) === 0) {
            return;
        }

        foreach($serverNames as $serverName) {
            $lastReadSequence = $this->getLastPublishedSequence($serverName, $channel);
            echo $lastReadSequence;
            $filter = ['channel' => $channel];
            $result = fluxStorage\getData($this->streamTableName, $this->streamStateSchema, $this->streamStorageConfigEnvPrefix, $filter, null, null, null, null, $lastReadSequence);

            foreach($result as $row) {
                fluxMessageServer\post($row['correlationId'],$row['createdBy'],$serverName, $channel, $row['currentState']);
                $lastReadSequence = (int)$row['autoSeq'];
                $this->storeLastPublishedSequence($serverName, $channel, $lastReadSequence);
            }
        }

    }


    public function publishStateChange(Core\Domain\StateChanged $state) : void
    {
        if ($this->consumerRegistry->has()) {

            $message = MessageAdapter::fromDomain($state)->toMessage();
            $consumers = $this->consumerRegistry->getConsumerClients();

            foreach ($consumers as $consumer) {
                $consumer->publish($message);
            }
        }
    }

    public function getStreamStorageConfigEnvPrefix() : string
    {
        return $this->streamStorageConfigEnvPrefix;
    }

    public function getStreamTableName() : string
    {
        return $this->streamTableName;
    }

    public function getStreamStateSchema() : array
    {
        return $this->streamStateSchema;
    }

    private function getLastPublishedSequence(string $subscriber, string $channel): int
    {
        $filter = ['subscriber' => $subscriber, 'channel' => $channel];
        $result = fluxStorage\getData($this->streamPublishedMessagesTableName, $this->streamPublishedMessagesSchema, $this->streamStorageConfigEnvPrefix, $filter);
        if(count($result) === 0) {
            return 0;
        }
        return (int)$result[0]['lastSequence'];
    }

    private function storeLastPublishedSequence(string $subscriber, string $channel, int $sequence): void
    {
        $filter = ['subscriber' => $subscriber, 'channel' => $channel];

        $data = [
            'subscriber' => $subscriber,
            'channel' => $channel,
            'lastSequence' => $sequence,
            'lastDateTime' => fluxValueObject\getCurrentTime()
        ];

        fluxStorage\storeData($this->streamPublishedMessagesTableName, $this->streamPublishedMessagesSchema, $this->streamStorageConfigEnvPrefix, $filter, $data);
    }
}