<?php

namespace FluxEco\GlobalStream\Core\Ports\Storage;
use FluxEco\GlobalStream\Core\Domain\Events;


interface GlobalStream
{
    public function getNextSequence(): int;
    public function hasRecordedStates(): bool;
    /** @return Events\CurrentStatePublished[] */
    public function getRecordedStates(): array;
}