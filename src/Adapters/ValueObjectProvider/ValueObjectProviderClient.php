<?php

namespace FluxEco\GlobalStream\Adapters\ValueObjectProvider;

use  FluxEco\GlobalStream\Core\Ports;
use FluxEco\ValueObject\Adapters\Api;

class ValueObjectProviderClient implements Ports\ValueObject\ValueObjectProviderClient
{
    private Api\ValueObjectApi $objectProvider;

    private function __construct(Api\ValueObjectApi $objectProvider)
    {
        $this->objectProvider = $objectProvider;
    }

    public static function new(): self
    {
        $objectProvider = Api\ValueObjectApi::new();
        return new self($objectProvider);
    }

    public function createUuid(): string
    {
        $valueObject = $this->objectProvider->createUuid();
        return ValueObjectAdapter::fromApi($valueObject)->toString();
    }

    public function createCurrentTime(): string
    {
        $valueObject = $this->objectProvider->createCurrentTime();
        return ValueObjectAdapter::fromApi($valueObject)->toString();
    }
}