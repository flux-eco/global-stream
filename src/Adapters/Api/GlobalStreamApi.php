<?php

namespace FluxEco\GlobalStream\Adapters\Api;

use FluxEco\GlobalStream\Adapters\Configs\GlobalStreamOutbounds;
use FluxEco\GlobalStream\Core\Ports;

class GlobalStreamApi
{
    private Ports\GlobalStreamService $globalStreamService;

    private function __construct(Ports\GlobalStreamService $globalStreamService)
    {
        $this->globalStreamService = $globalStreamService;
    }

    public static function new(array $subjectNames = []): self
    {
        $globalStreamOutbounds = GlobalStreamOutbounds::new();
        $globalStreamService = Ports\GlobalStreamService::new($globalStreamOutbounds, $subjectNames);

        return new self($globalStreamService);
    }

    final public function initializeGlobalStream(): void
    {
        $this->globalStreamService->createGlobalStreamStorage();
    }


    final public function publishStateChange(string $correlationId,
                                       string $createdBy,
                                       string $subject,
                                       string $subjectId,
                                       int    $subjectSequence,
                                       string $subjectName,
                                       string $rootObjectSchema,
                                       string $eventName,
                                       string $currentState): void
    {
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

    final public function republishAllStates(): void {
        $this->globalStreamService->republishAllStates();
    }
}