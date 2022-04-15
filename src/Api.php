<?php

namespace FluxEco\GlobalStream;

use FluxEco\GlobalStream\Adapters\Outbounds;
use FluxEco\GlobalStream\Core\Ports;

class Api
{
    private Ports\GlobalStreamService $globalStreamService;

    private function __construct(Ports\GlobalStreamService $globalStreamService)
    {
        $this->globalStreamService = $globalStreamService;
    }

    public static function newFromEnv(array $subjectNames = []) : self
    {
        $env = Env::new();

        $outbounds = Outbounds::new(
            $env->getStreamStorageConfigEnvPrefix(),
            $env->getStreamTableName(),
            $env->getStreamStateSchemaFile()
        );
        $globalStreamService = Ports\GlobalStreamService::new($outbounds, $subjectNames);

        return new self($globalStreamService);
    }

    final public function initialize() : void
    {
        $this->globalStreamService->createGlobalStreamStorage();
    }

    final public function publishStateChange(
        string $correlationId,
        string $createdBy,
        string $subject,
        string $subjectId,
        int $subjectSequence,
        string $subjectName,
        string $rootObjectSchema,
        string $eventName,
        string $currentState
    ) : void {
        $this->globalStreamService->publishStateChange(
            $correlationId,
            $createdBy,
            $subject,
            $subjectId,
            $subjectSequence,
            $subjectName,
            $rootObjectSchema,
            $eventName,
            $currentState
        );
    }

    final public function republishAllStates() : void
    {
        $this->globalStreamService->republishAllStates();
    }
}