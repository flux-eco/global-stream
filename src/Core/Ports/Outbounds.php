<?php

namespace FluxEco\GlobalStream\Core\Ports;

use FluxEco\GlobalStream\Core;

interface Outbounds
{

    public function createGlobalStreamStorage(): void;

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

    /** @return Core\Domain\StateChanged[] */
    public function queryStates(string $subjectName): array;

    public function getNewUuid(): string;

    public function getCurrentTime(): string;

    public function publishStateChanged(Core\Domain\StateChanged $state);

    public function getStreamStorageConfigEnvPrefix() : string;

    public function getStreamTableName() : string;

    public function getStreamStateSchema() : array;
}