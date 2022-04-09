<?php


namespace FluxEco\GlobalStream\Adapters\Publisher;


class Message implements StatePublished
{

    private array $headers;
    private string $payload;

    /**
     * @throws \JsonException
     */
    private function __construct(
        array  $headers,
        string $payload
    )
    {
        $this->headers = $headers;
        $this->payload = $payload;
    }

    public static function new(
        string $correlationId,
        string $subject,
        string $subjectId,
        string $subjectName,
        string $createdBy,
        string $createdDateTime,
        string $jsonRootObjectSchema,
        string $messageName,
        string $payload
    ): self
    {
        $headers = [];
        $headers['correlationId'] = $correlationId;
        $headers['subject'] = $subject;
        $headers['subjectId'] = $subjectId;
        $headers['subjectName'] = $subjectName;
        $headers['createdBy'] = $createdBy;
        $headers['createdDateTime'] = $createdDateTime;
        $headers['jsonRootObjectSchema'] = $jsonRootObjectSchema;
        $headers['messageName'] = $messageName;

        return new self(
            $headers,
            $payload
        );
    }

    final public function getMessageName(): string
    {
        return $this->headers['messageName'];
    }

    final public function getSubjectName(): string
    {
        return $this->headers['subjectName'];
    }

    final public function getSubjectId(): string
    {
        return $this->headers['subjectId'];
    }

    final public function getHeaders(): array
    {
        return $this->headers;
    }

    final public function getJsonRootObjectSchema(): string {
        return $this->headers['jsonRootObjectSchema'];
    }

    final public function getPayload(): string
    {
        return $this->payload;
    }
}