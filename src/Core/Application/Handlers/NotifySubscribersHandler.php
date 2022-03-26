<?php

namespace FluxEco\GlobalStream\Core\Application\Handlers;

use FluxEco\GlobalStream\Core\{Ports};
use Flux\Eco\ObjectProvider;


class NotifySubscribersHandler implements Handler
{
    private Ports\Publisher\StatePublisher $notifierClient;

    private function __construct(
        Ports\Publisher\StatePublisher $notifierClient)
    {
        $this->notifierClient = $notifierClient;
    }

    public static function new(
        Ports\Publisher\StatePublisher $notifierClient
    ): self
    {
        return new self(
            $notifierClient
        );
    }

    public function handle(Command|NotifySubscribersCommand $command): void
    {
        $this->notifierClient->notify($command->getGlobalEvent());
    }

}