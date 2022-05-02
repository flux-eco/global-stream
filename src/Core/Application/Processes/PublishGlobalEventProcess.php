<?php

namespace FluxEco\GlobalStream\Core\Application\Processes;

use FluxEco\GlobalStream\Core\{Application\Handlers, Ports};

/**
 * @author martin@fluxlabs.ch
 */
class PublishGlobalEventProcess
{
    private Ports\Storage\GlobalStreamStorageClient $eventStorageClient;
    private Ports\Publisher\StatePublisher $eventNotifierClient;

    private function __construct()
    {

    }

    public static function new(): self
    {
        return new self();
    }

    public function handle(PublishGlobalEventCommand $publishGlobalEventCommand)
    {
        $globalEvent = $publishGlobalEventCommand->getGlobalEvent();
        $storeGlobalEventCommand = Handlers\RecordCurrentStateCommand::new($globalEvent);
        $storeGlobalEventHandler = Handlers\RecordCurrentStateHandler::new($this->eventStorageClient);
        $this->process($storeGlobalEventCommand, $storeGlobalEventHandler);

        $notifySubscribersCommand = Handlers\NotifySubscribersCommand::new($globalEvent);
        $notifySubscribersHandler = Handlers\NotifySubscribersHandler::new($this->eventNotifierClient);
        $this->process($notifySubscribersCommand, $notifySubscribersHandler);
    }

    private function process(Handlers\Command $command, Handlers\Handler $handler): void
    {
        $handler->handle($command);
    }
}