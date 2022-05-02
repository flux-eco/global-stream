<?php

namespace fluxGlobalStream;

use FluxEco\GlobalStream;

function publishStateChange(
    string $correlationId,
    string $createdBy,
    string $channel,
    string $subject,
    string $subjectId,
    int    $subjectSequence,
    string $subjectName,
    string $eventName,
    string $message) {
    GlobalStream\Api::newFromEnv()->publishStateChange(
        $correlationId,
        $createdBy,
        $channel,
        $subject,
        $subjectId,
        $subjectSequence,
        $subjectName,
        $eventName,
        $message
    );
}