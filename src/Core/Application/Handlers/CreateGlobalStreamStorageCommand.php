<?php

namespace FluxEco\GlobalStream\Core\Application\Handlers;

class CreateGlobalStreamStorageCommand implements \JsonSerializable
{
    private array $schema;

    private function __construct(array $schema)
    {
        $this->schema = $schema;
    }

    public static function new(array $schema): self
    {
        return new self($schema);
    }


    final public function getSchema(): array
    {
        return $this->schema;
    }


    final public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}