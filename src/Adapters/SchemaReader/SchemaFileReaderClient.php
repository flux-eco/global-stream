<?php

namespace FluxEco\GlobalStream\Adapters\SchemaReader;

class SchemaFileReaderClient implements Ports\SchemaReader\SchemaFileReader
{

    private function __construct()
    {

    }

    public static function new(): self
    {
        return new self();
    }

    public function readSchemaFile(string $schemaFilePath): Domain\Models\SchemaDocument
    {
        $schemaFileReader = Api\SchemaFileReaderApi::new();
        $schemaDocument = $schemaFileReader->getSchemaDocument($schemaFilePath);

        return SchemaDocument::fromApi($schemaDocument)->toDomain();

    }
}