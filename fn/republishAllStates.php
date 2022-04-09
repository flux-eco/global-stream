<?php

namespace fluxGlobalStream;

use FluxEco\GlobalStream;

function republishAllStates(array $subjectNames) {
    GlobalStream\Api::newFromEnv($subjectNames)->republishAllStates();
}