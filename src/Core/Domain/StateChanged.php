<?php

namespace FluxEco\GlobalStream\Core\Domain;
use FluxEco\GlobalStream\Core\Ports;

class StateChanged
{
    private int $sequence;
    private string $correlationId;
    private string $createdBy;
    private string $createdDateTime;
    private string $channel;
    private string $subject;
    private string $subjectId;
    private int $subjectSequence;
    private string $subjectName;
    private string $eventName;
    private string $payload;

    private function __construct(
        int    $sequence,
        string $correlationId,
        string $createdBy,
        string $createdDateTime,
        string $channel,
        string $subject,
        string $subjectId,
        int    $subjectSequence,
        string $subjectName,
        string $eventName,
        string $payload
    )
    {
        $this->sequence = $sequence;
        $this->correlationId = $correlationId;
        $this->createdBy = $createdBy;
        $this->createdDateTime = $createdDateTime;
        $this->channel = $channel;
        $this->subject = $subject;
        $this->subjectId = $subjectId;
        $this->subjectSequence = $subjectSequence;
        $this->subjectName = $subjectName;
        $this->eventName = $eventName;
        $this->payload = $payload;
    }

    public static function new(
        int    $sequence,
        string $correlationId,
        string $createdBy,
        string $createdDateTime,
        string $channel,
        string $subject,
        string $subjectId,
        int    $subjectSequence,
        string $subjectName,
        string $eventName,
        string $payload
    ): self
    {
        return new self($sequence,
            $correlationId,
            $createdBy,
            $createdDateTime,
            $channel,
            $subject,
            $subjectId,
            $subjectSequence,
            $subjectName,
            $eventName,
            $payload);
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

    final public function getChannel() : string
    {
        return $this->channel;
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

    final public function getEventName(): string
    {
        return $this->eventName;
    }

    final public function getPayload(): string
    {
        return $this->payload;
    }
}