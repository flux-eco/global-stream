<?php


declare(strict_types=1);

namespace FluxEco\GlobalStream\Adapters\Configs;

use FluxEco\GlobalStream\Adapters;
use FluxEco\GlobalStream\Core;
use FluxEco\GlobalStream\Core\Ports;

class GlobalStreamOutbounds implements Core\Ports\Configs\GlobalStreamOutbounds
{
    private string $databaseName;
    private string $tableName;
    private array $jsonSchema;


    private function __construct(string $databaseName, string $tableName, array $jsonSchema)
    {
        $this->databaseName = $databaseName;
        $this->tableName = $tableName;
        $this->jsonSchema = $jsonSchema;
    }

    public static function new(): self
    {
        $databaseName = getenv(GlobalStreamEnv::PARAM_DATABASE);
        $tableName = getenv(GlobalStreamEnv::PARAM_TABLE);
        $jsonSchemaFile = getenv(GlobalStreamEnv::PARAM_JSON_SCHEMA_FILE);
        $jsonSchema = yaml_parse(file_get_contents($jsonSchemaFile));
        return new self($databaseName, $tableName, $jsonSchema);
    }

    final public function getGlobalStreamStorageClient(): Ports\Storage\GlobalStreamStorageClient
    {
        return Adapters\Storage\GlobalStreamStorageClient::new($this->databaseName, $this->tableName, $this->jsonSchema);
    }

    final public function getValueObjectProvider(): Ports\ValueObject\ValueObjectProviderClient
    {
        return Adapters\ValueObjectProvider\ValueObjectProviderClient::new();
    }

    final public function getJsonSchema(): array
    {
        return $this->jsonSchema;
    }

    public function getStatePublisher(): Ports\Publisher\StatePublisher
    {
        $consumerRegistry = Adapters\Publisher\ConsumerRegistry::new(
            Adapters\Publisher\ConsumerRegistry::SUBJECT_AGGREGATE_ROOT, [Adapters\Projection\ProjectionClient::new()]
        );
        return Adapters\Publisher\StatePublisherClient::new($consumerRegistry);
    }
}