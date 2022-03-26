<?php

namespace FluxEco\GlobalStream\Core\Application\Processes;

use FluxEco\GlobalStream\Core\Domain\Events\GlobalEvent;

class PublishGlobalEventCommand implements \JsonSerializable
{
    private GlobalEvent $globalEvent;

    private function __construct(GlobalEvent $globalEvent)
    {
        $this->globalEvent = $globalEvent;
    }


    public static function new(GlobalEvent $globalEvent): self
    {
        return new self($globalEvent);
    }

    final public function getGlobalEvent(): GlobalEvent
    {
        return $this->globalEvent;
    }


    final public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}