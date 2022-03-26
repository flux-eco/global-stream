<?php

namespace FluxEco\GlobalStream\Core\Ports\Configs;

use FluxEco\GlobalStream\Core\Ports;

interface GlobalStreamOutbounds
{
    public function getJsonSchema(): array;

    public function getGlobalStreamStorageClient(): Ports\Storage\GlobalStreamStorageClient;

    public function getValueObjectProvider(): Ports\ValueObject\ValueObjectProviderClient;

    public function getStatePublisher():  Ports\Publisher\StatePublisher;
}