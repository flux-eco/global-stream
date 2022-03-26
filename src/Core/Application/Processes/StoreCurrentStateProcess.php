<?php

namespace FluxEco\GlobalStream\Core\Application\Processes;

use FluxEco\GlobalStream\Core\{Application,
    Application\Handlers,
    Domain,
    Ports\Storage\GlobalStreamEventStream};
use FluxEco\GlobalStream\Adapters\Storage\EventStorage;

/**
 * Class ProcessHandlerCreateNewImportDefinition
 *
 * @author Martin Studer <martin@fluxlabs.ch>
 */
class StoreCurrentStateProcess
{


    private function __construct(

    )
    {

    }

    public static function new(

    ): self
    {
        return new self( );
    }

    public function process(Handlers\StoreGlobalStreamChangedEventCommand $command, Handlers\StoreGlobalStreamChangedEventHandler $handler): void
    {
       $handler->handle($command);
    }
}