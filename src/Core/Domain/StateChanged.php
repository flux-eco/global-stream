<?php

namespace FluxEco\GlobalStream\Core\Domain;
use FluxEco\GlobalStream\Core\Ports;

class StateChanged
{
    private int $sequence;
    private string $correlationId;
    private string $createdBy;
    private string $createdDateTime;
    private string $subject;
    private string $subjectId;
    private int $subjectSequence;
    private string $subjectName;
    private string $jsonRootObjectSchema;
    private string $eventName;
    private string $currentState;

    private function __construct(
        int    $sequence,
        string $correlationId,
        string $createdBy,
        string $createdDateTime,
        string $subject,
        string $subjectId,
        int    $subjectSequence,
        string $subjectName,
        string $jsonRootObjectSchema,
        string $eventName,
        string $currentState
    )
    {
        $this->sequence = $sequence;
        $this->correlationId = $correlationId;
        $this->createdBy = $createdBy;
        $this->createdDateTime = $createdDateTime;
        $this->subject = $subject;
        $this->subjectId = $subjectId;
        $this->subjectSequence = $subjectSequence;
        $this->subjectName = $subjectName;
        $this->jsonRootObjectSchema = $jsonRootObjectSchema;
        $this->eventName = $eventName;
        $this->currentState = $currentState;
    }

    public static function new(
        int    $sequence,
        string $correlationId,
        string $createdBy,
        string $createdDateTime,
        string $subject,
        string $subjectId,
        int    $subjectSequence,
        string $subjectName,
        string $jsonRootObjectSchema,
        string $eventName,
        string $currentState
    ): self
    {
        return new self($sequence,
            $correlationId,
            $createdBy,
            $createdDateTime,
            $subject,
            $subjectId,
            $subjectSequence,
            $subjectName,
            $jsonRootObjectSchema,
            $eventName,
            $currentState);
    }

    final public function getSequence(): int
    {
        return $this->sequence;
    }

    final public function getCorrelationId(): string
    {
        return $this->correlationId;
    }

    final public function getCreatedBy(): string
    {
        return $this->createdBy;
    }

    final public function getCreatedDateTime(): string
    {
        return $this->createdDateTime;
    }

      final public function getSubject(): string
      {
          return $this->subject;
      }

    final public function getSubjectId(): string
    {
        return $this->subjectId;
    }

    final public function getSubjectSequence(): int
    {
        return $this->subjectSequence;
    }

    final public function getSubjectName(): string
    {
        return $this->subjectName;
    }

    final public function getJsonRootObjectSchema(): string
    {
        return $this->jsonRootObjectSchema;
    }

    final public function getEventName(): string
    {
        return $this->eventName;
    }

    final public function getCurrentState(): string
    {
        return $this->currentState;
    }
}