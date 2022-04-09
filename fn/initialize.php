<?php

namespace fluxGlobalStream;

use FluxEco\GlobalStream;

function initialize() {
    GlobalStream\Api::newFromEnv()->initialize();
}