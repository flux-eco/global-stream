<?php

namespace FluxEco\GlobalStream\Core\Application\Handlers;

use FluxEco\GlobalStream\Core\{Ports};

class CreateGlobalStreamStorageHandler
{
    private  Ports\Outbounds $outbounds;


    private function __construct(
        Ports\Outbounds $outbounds
    )
    {
        $this->outbounds = $outbounds;
    }

    public static function new(
        Ports\Outbounds $outbounds,
    ): self
    {
        return new self(
            $outbounds
        );
    }


    public function handle()
    {
        $this->outbounds->createGlobalStreamStorages();
    }

}