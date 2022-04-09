<?php

namespace FluxEco\GlobalStream\Core\Ports;

use FluxEco\GlobalStream\Core\{Domain, Application\Handlers, Ports};

class GlobalStreamService
{
    private Ports\Outbounds $outbounds;
    private Domain\GlobalStream $globalStream;

    private function __construct(
        Ports\Outbounds $outbounds,
        Domain\GlobalStream                         $globalStream,
    )
    {
        $this->outbounds = $outbounds;
        $this->globalStream = $globalStream;
    }

    final public static function new(
        Ports\Outbounds $outbounds,
        array $subjectNames
    ): self
    {

        $globalStream = Domain\GlobalStream::new(
            $outbounds, $subjectNames
        );

        return new self(
            $outbounds,
            $globalStream
        );
    }

    final public function createGlobalStreamStorage(): void
    {
        $handler = Handlers\CreateGlobalStreamStorageHandler::new($this->outbounds);
        $handler->handle();
    }

    final public function publishStateChange(
        string $correlationId,
        string $createdBy,
        string $subject,
        string $subjectId,
        int    $subjectSequence,
        string $subjectName,
        string $jsonRootObjectSchema,
        string $eventName,
        string $currentState
    ): void
    {
        $globalStream = $this->globalStream;
        $sequence = $this->globalStream->getNextSequence();
        $createdDateTime = $this->outbounds->getCurrentTime();

        $state = Domain\StateChanged::new(
            $sequence,
            $correlationId,
            $createdBy,
            $createdDateTime,
            $subject,
            $subjectId,
            $subjectSequence,
            $subjectName,
            $jsonRootObjectSchema,
            $eventName,
            $currentState
        );
        $globalStream->applyRecordAndPublish($state);
        $globalStream->storeRecordedStates();
    }

    final function republishAllStates() {
        $states = $this->globalStream->getStates();
        foreach($states as $state) {
            $this->outbounds->publishStateChanged($state);
        }
    }
}