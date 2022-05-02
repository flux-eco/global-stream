<?php

namespace fluxGlobalStream;

use FluxEco\GlobalStream;

function notify(string $channelName): void {
    GlobalStream\Api::newFromEnv([$channelName])->notify($channelName);
}