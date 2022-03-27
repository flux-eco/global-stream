<?php

namespace FluxEco\GlobalStream\Adapters\ValueObjectProvider;

use FluxEco\GlobalStream\Core\Domain;
use FluxEco\ValueObject\Adapters\Api;

class ValueObjectAdapter
{
    private Api\StringObject $providedStringObject;

    private function __construct(Api\StringObject $stringObject)
    {
        $this->providedStringObject = $stringObject;
    }

    public static function fromApi(Api\StringObject $stringObject): self
    {
        return new self($stringObject);
    }

    public function toString(): string
    {
        return $this->providedStringObject->getValue();
    }
}