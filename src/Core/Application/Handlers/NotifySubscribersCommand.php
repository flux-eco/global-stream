<?php


namespace FluxEco\GlobalStream\Core\Application\Handlers;
use FluxEco\GlobalStream\Core\Domain;

class NotifySubscribersCommand implements Command
{
    private Domain\Events\GlobalEvent $globalEvent;

    private function __construct(
        Domain\Events\GlobalEvent $globalEvent
    )
    {
        $this->globalEvent = $globalEvent;
    }


    public static function new(
        Domain\Events\GlobalEvent $globalEvent
    ): self
    {
        return new self(
            $globalEvent
        );
    }

    final public function getGlobalEvent(): Domain\Events\GlobalEvent
    {
        return $this->globalEvent;
    }


    final public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}