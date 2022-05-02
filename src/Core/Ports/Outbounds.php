<?php

namespace FluxEco\GlobalStream\Core\Ports;

use FluxEco\GlobalStream\Core;

interface Outbounds
{

    public function createGlobalStreamStorages(): void;

    public function storeState(
        string $correlationId,
        string $createdBy,
        string $createdDateTime,
        string $channel,
        string $subjectId,
        int    $subjectSequence,
        string $subject,
        string $subjectName,
        string $eventName,
        string $currentState
    ): void;

    /** @return Core\Domain\StateChanged[] */
    public function queryStates(string $channelName): array;

    public function notify(string $channel) : void;

    public function getNewUuid(): string;

    public function getCurrentTime(): string;

    public function publishStateChanged(Core\Domain\StateChanged $state);

    public function getStreamStorageConfigEnvPrefix() : string;

    public function getStreamTableName() : string;

    public function getStreamStateSchema() : array;
}