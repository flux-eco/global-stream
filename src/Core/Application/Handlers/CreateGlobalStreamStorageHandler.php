<?php

namespace FluxEco\GlobalStream\Core\Application\Handlers;

use FluxEco\GlobalStream\Core\{Ports};

class CreateGlobalStreamStorageHandler
{
    private Ports\Storage\GlobalStreamStorageClient $globalStreamStorageClient;


    private function __construct(
        Ports\Storage\GlobalStreamStorageClient $globalStreamStorageClient)
    {
        $this->globalStreamStorageClient = $globalStreamStorageClient;
    }

    public static function new(
        Ports\Storage\GlobalStreamStorageClient $globalStreamStorageClient
    ): self
    {
        return new self(
            $globalStreamStorageClient
        );
    }


    public function handle(CreateGlobalStreamStorageCommand $command)
    {
        $schema = $command->getSchema();
        $this->globalStreamStorageClient->createGlobalStreamStorage($schema);
    }

}