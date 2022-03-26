<?php

namespace FluxEco\GlobalStream\Core\Application\Processes;

use FluxEco\GlobalStream\Core\{
    Application,
    Ports
};

/**
 * Class ProcessHandlerCreateNewImportDefinition
 *
 * @author Martin Studer <martin@fluxlabs.ch>
 */
class QueryGlobalEventsProcess
{
    private Application\Queries\GetGlobalStreamEventStreamHandler $getAggregateEventsHandler;

    private function __construct(Application\Queries\GetGlobalStreamEventStreamHandler $getAggregateEventsHandler)
    {
        $this->getAggregateEventsHandler = $getAggregateEventsHandler;
    }

    public static function new(Ports\Storage\GlobalStreamEventStorage $storageClient): self
    {
        $queryHandler = Application\Queries\GetGlobalStreamEventStreamHandler::new($storageClient);
        return new self($queryHandler);
    }

    public function process(Application\Queries\GetGlobalStreamEventStreamQuery $query): \JsonSerializable
    {
        return $this->getAggregateEventsHandler->handle($query);
    }
}