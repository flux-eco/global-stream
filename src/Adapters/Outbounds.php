<?php

declare(strict_types=1);

namespace FluxEco\GlobalStream\Adapters;

use FluxEco\GlobalStream\Adapters;
use FluxEco\GlobalStream\Core;
use FluxEco\GlobalStream\Core\Ports;
use fluxValueObject;
use fluxStorage;
use fluxProjection;

class Outbounds implements Core\Ports\Outbounds
{

    private string $streamStorageConfigEnvPrefix;
    private string $streamTableName;
    private array $streamStateSchema;

    private function __construct(
        string $streamStorageConfigEnvPrefix,
        string $streamTableName,
        string $streamStateSchemaFile
    ) {
        $this->streamStorageConfigEnvPrefix = $streamStorageConfigEnvPrefix;
        $this->streamTableName = $streamTableName;
        $this->streamStateSchema = yaml_parse(file_get_contents($streamStateSchemaFile));
    }

    public static function new(
        string $streamStorageConfigEnvPrefix,
        string $streamTableName,
        string $streamStateSchemaFile,
    ) : self {
        return new self($streamStorageConfigEnvPrefix, $streamTableName, $streamStateSchemaFile);
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
        fluxProjection\receiveAggregateRootStatePublished(
            $state->getSubjectId(),
            $state->getSubjectName(),
            $state->getEventName(),
            $state->getJsonRootObjectSchema(),
            $state->getCurrentState()
        );
    }

    public function createGlobalStreamStorage() : void
    {
        //if (array_key_exists(self::SEQUENCE_COLUMN_NAME, $jsonSchema['properties']) === false) {
        //    throw new \Exception('An global stream storage schema MUST contain a ' . self::SEQUENCE_COLUMN_NAME . ' column!');
        //}
        fluxStorage\createStorage($this->streamTableName, $this->streamStateSchema, $this->streamStorageConfigEnvPrefix);
    }

    public function storeState(
        int $sequence,
        string $correlationId,
        string $createdBy,
        string $createdDateTime,
        string $subjectId,
        int $subjectSequence,
        string $subject,
        string $subjectName,
        string $jsonRootObjectSchema,
        string $eventName,
        string $currentState
    ) : void {
        $data = get_defined_vars();
        fluxStorage\appendData($this->streamTableName, $this->streamStateSchema, $data, $this->streamStorageConfigEnvPrefix);
    }

    /**
     * @return Core\Domain\StateChanged[]
     */
    public function queryStates(string $subjectName) : array
    {
        $filter = ['subjectName' => $subjectName];
        $result = fluxStorage\getData($this->streamTableName, $this->streamStateSchema, $this->streamStorageConfigEnvPrefix, $filter);
        return Storage\GlobalStreamStatesAdapter::fromQueryResult($result)->toStates();
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
}