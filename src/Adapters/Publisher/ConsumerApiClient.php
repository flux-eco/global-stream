<?php


namespace FluxEco\GlobalStream\Adapters\Publisher;

interface ConsumerApiClient
{
    public static function new(): self;
    public function publish(Message $message): void;
}