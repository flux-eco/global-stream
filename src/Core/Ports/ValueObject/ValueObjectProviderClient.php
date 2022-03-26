<?php

namespace FluxEco\GlobalStream\Core\Ports\ValueObject;

interface ValueObjectProviderClient
{
    public function createUuid(): string;

    public function createCurrentTime(): string;
}