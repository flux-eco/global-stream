<?php

namespace FluxEco\GlobalStream\Core\Ports\Publisher;

use  FluxEco\GlobalStream\Core\Domain;

interface StatePublisher
{
    public function publish(Domain\StateChanged $state): void;
}