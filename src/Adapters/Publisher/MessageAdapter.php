<?php


namespace FluxEco\GlobalStream\Adapters\Publisher;

use  FluxEco\GlobalStream\Core\{Domain};

class MessageAdapter
{
    private Domain\StateChanged $state;

    private function __construct(Domain\StateChanged $state)
    {
        $this->state = $state;
    }

    public static function fromDomain(Domain\StateChanged $state): self
    {
        return new self($state);
    }

    public function toMessage(): Message
    {
        $currentState = $this->state;

        $correlationId = $currentState->getCorrelationId();
        $subject = $currentState->getSubject();
        $subjectId = $currentState->getSubjectId();
        $subjectName = $currentState->getSubjectName();
        $createdBy = $currentState->getCreatedBy();
        $createdDateTime = $currentState->getCreatedDateTime();
        $jsonRootObjectSchema = $currentState->getJsonRootObjectSchema();

        $messageName = $currentState->getEventName();

        $payload = $currentState->getCurrentState();


        return Message::new(
            $correlationId,
            $subject,
            $subjectId,
            $subjectName,
            $createdBy,
            $createdDateTime,
            $jsonRootObjectSchema,
            $messageName,
            $payload
        );
    }
}