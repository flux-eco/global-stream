<?php

namespace FluxEco\GlobalStream\Core\Ports\Storage;

use FluxEco\GlobalStream\Core\{Domain};

interface GlobalStreamStorageClient
{
    public function createGlobalStreamStorage(array $jsonSchema): void;

    public function storeState(int    $sequence,
                               string $correlationId,
                               string $createdBy,
                               string $createdDateTime,
                               string $subjectId,
                               int    $subjectSequence,
                               string $subject,
                               string $subjectName,
                               string $jsonRootObjectSchema,
                               string $eventName,
                               string $currentState
    ): void;

    /** @return Domain\StateChanged[] */
    public function queryStates(string $subjectName): array;
}