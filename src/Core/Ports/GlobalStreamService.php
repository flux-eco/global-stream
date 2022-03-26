<?php

namespace FluxEco\GlobalStream\Core\Ports;

use FluxEco\GlobalStream\Core\{Domain, Application\Handlers, Ports};

class GlobalStreamService
{
    private array $stateSchema;
    private Ports\Storage\GlobalStreamStorageClient $globalStreamStorageClient;
    private Ports\Publisher\StatePublisher $statePublisher;
    private Domain\GlobalStream $globalStream;
    private Ports\ValueObject\ValueObjectProviderClient $valueObjectProvider;

    private function __construct(
        array                                       $stateSchema,
        Ports\Storage\GlobalStreamStorageClient     $globalStreamStorageClient,
        Ports\Publisher\StatePublisher              $statePublisher,
        Domain\GlobalStream                         $globalStream,
        Ports\ValueObject\ValueObjectProviderClient $valueObjectProvider
    )
    {
        $this->globalStreamStorageClient = $globalStreamStorageClient;
        $this->statePublisher = $statePublisher;
        $this->stateSchema = $stateSchema;
        $this->globalStream = $globalStream;
        $this->valueObjectProvider = $valueObjectProvider;
    }

    final public static function new(
        Ports\Configs\GlobalStreamOutbounds $globalStreamOutbounds,
        array $subjectNames
    ): self
    {
        $stateSchema = $globalStreamOutbounds->getJsonSchema();
        $globalStreamStorageClient = $globalStreamOutbounds->getGlobalStreamStorageClient();
        $statePublisher = $globalStreamOutbounds->getStatePublisher();

        $globalStream = Domain\GlobalStream::new(
            $globalStreamStorageClient, $statePublisher, $subjectNames
        );
        $valueObjectProvider = $globalStreamOutbounds->getValueObjectProvider();

        return new self(
            $stateSchema,
            $globalStreamStorageClient,
            $statePublisher,
            $globalStream,
            $valueObjectProvider
        );
    }

    final public function createGlobalStreamStorage(): void
    {
        $schema = $this->stateSchema;
        $storageClient = $this->globalStreamStorageClient;

        $command = Handlers\CreateGlobalStreamStorageCommand::new($schema);
        $handler = Handlers\CreateGlobalStreamStorageHandler::new($storageClient);
        $handler->handle($command);
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
        $valueObjectProvider = $this->valueObjectProvider;
        $sequence = $this->globalStream->getNextSequence();
        $createdDateTime = $valueObjectProvider->createCurrentTime();

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
            $this->statePublisher->publish($state);
        }
    }
}