<?php

namespace FluxEco\GlobalStream\Adapters\Storage;

use FluxEco\GlobalStream\Core\{Domain, Ports};
use FluxEco\Storage\Adapters\Api\StorageApi;

class GlobalStreamStorageClient implements Ports\Storage\GlobalStreamStorageClient
{
    private const SEQUENCE_COLUMN_NAME = 'sequence';
    private StorageApi $storageApi;

    private function __construct(StorageApi $storageApi)
    {
        $this->storageApi = $storageApi;
    }

    public static function new(string $databaseName, string $tableName, array $jsonSchema): self
    {
        $storageApi = StorageApi::new($databaseName, $tableName, $jsonSchema);
        return new self($storageApi);
    }

    public function createGlobalStreamStorage(array $jsonSchema): void
    {
        if (array_key_exists(self::SEQUENCE_COLUMN_NAME, $jsonSchema['properties']) === false) {
            throw new \Exception('An global stream storage schema MUST contain a ' . self::SEQUENCE_COLUMN_NAME . ' column!');
        }
        echo PHP_EOL;
        echo "createStorage";
        echo PHP_EOL;
        $this->storageApi->createStorage(null);
    }

    public function storeState(
        int $sequence,
        string $correlationId,
        string $createdBy,
        string $createdDateTime,
        string $subjectId,
        int $subjectSequence,
        string $subject,
        string $subjectName,
        string $jsonRootObjectSchema,
        string $eventName,
        string $currentState): void
    {
        $data = get_defined_vars();
        $this->storageApi->appendData($data);
    }

    /**
     * @return Domain\StateChanged[]
     */
    public function queryStates(string $subjectName): array
    {
        $filter = ['subjectName' => $subjectName];
        $result = $this->storageApi->getData($filter);

        return GlobalStreamStatesAdapter::fromQueryResult($result)->toStates();
    }
}