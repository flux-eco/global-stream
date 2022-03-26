<?php

namespace FluxEco\GlobalStream\Core\Application\Handlers;

use FluxEco\GlobalStream\Core\{Ports};
use Flux\Eco\ObjectProvider;


class RecordCurrentStateHandler implements Handler
{
    private Ports\Storage\GlobalStreamStorageClient $eventStorageClient;

    private function __construct(
        Ports\Storage\GlobalStreamStorageClient $eventStorageClient)
    {
        $this->eventStorageClient = $eventStorageClient;
    }

    public static function new(
        Ports\Storage\GlobalStreamStorageClient $eventStorageClient
    ): self
    {
        return new self(
            $eventStorageClient
        );
    }

    public function handle(Command $command): void
    {
        $this->eventStorageClient->storeGlobalEvent($command);
    }

}