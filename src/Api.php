<?php

namespace FluxEco\GlobalStream;

use FluxEco\GlobalStream\Adapters\Outbounds;
use FluxEco\GlobalStream\Core\Ports;

class Api
{
    private Ports\GlobalStreamService $globalStreamService;
    private Ports\Outbounds $outbounds;

    private function __construct(Ports\GlobalStreamService $globalStreamService, Ports\Outbounds $outbounds)
    {
        $this->globalStreamService = $globalStreamService;
        $this->outbounds = $outbounds;
    }

    public static function newFromEnv(array $channelNames = []) : self
    {
        $env = Env::new();

        $outbounds = Outbounds::new(
            $env->getStreamStorageConfigEnvPrefix(),
            $env->getStreamTableName(),
            $env->getStreamStateSchemaFile(),
            $env->getStreamPublishedMessagesTableName(),
            $env->getStreamPublishedMessagesSchemaFile(),
            $env->getChannels()
        );
        $globalStreamService = Ports\GlobalStreamService::new($outbounds, $channelNames);

        return new self($globalStreamService, $outbounds);
    }

    final public function initialize() : void
    {
        $this->globalStreamService->createGlobalStreamStorages();
    }

    final public function publishStateChange(
        string $correlationId,
        string $createdBy,
        string $channel,
        string $subject,
        string $subjectId,
        int $subjectSequence,
        string $subjectName,
        string $eventName,
        string $currentState
    ) : void {
        $this->globalStreamService->publishStateChange(
            $correlationId,
            $createdBy,
            $channel,
            $subject,
            $subjectId,
            $subjectSequence,
            $subjectName,
            $eventName,
            $currentState
        );
    }

    final public function republishAllStates() : void
    {
        $this->globalStreamService->republishAllStates();
    }

    final public function notify(string $channelName): void {
        $this->globalStreamService->notify($channelName);
    }
}