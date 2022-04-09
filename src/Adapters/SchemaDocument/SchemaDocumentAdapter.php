<?php


namespace FluxEco\GlobalStream\Adapters\SchemaDocument;
use  FluxEco\AggregateRoot\Core\Domain;
use FluxEco\JsonSchemaDocument;

class SchemaDocumentAdapter
{
    private JsonSchemaDocument\SchemaDocument $schemaDocument;

    private function __construct(JsonSchemaDocument\SchemaDocument $schemaDocument)
    {
        $this->schemaDocument = $schemaDocument;
    }

    public static function fromApi(JsonSchemaDocument\SchemaDocument $schemaDocument)
    {
        return new self($schemaDocument);
    }

    public function toDomain():Domain\Models\SchemaDocument {
        return Domain\Models\SchemaDocument::new($this->schemaDocument->getProperties());
    }
}