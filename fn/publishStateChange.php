<?php

namespace fluxGlobalStream;

use FluxEco\GlobalStream;

function publishStateChange(
    string $correlationId,
    string $createdBy,
    string $subject,
    string $subjectId,
    int    $subjectSequence,
    string $subjectName,
    string $jsonObjectSchema,
    string $eventName,
    string $currentState) {
    GlobalStream\Api::newFromEnv()->publishStateChange(
        $correlationId,
        $createdBy,
        $subject,
        $subjectId,
        $subjectSequence,
        $subjectName,
        $jsonObjectSchema,
        $eventName,
        $currentState
    );
}