<?php

namespace FluxEco\GlobalStream\Adapters\Storage;

use FluxEco\GlobalStream\Core\Domain\StateChanged;

class GlobalStreamStatesAdapter
{
    /** @var StateChanged[] */
    private array $states;

    private function __construct(array $states)
    {
        $this->states = $states;
    }

    public static function fromQueryResult(array $queryResult): self
    {
        $states = [];
        foreach ($queryResult as $row) {
            $states[$row['sequence']] = StateChanged::new(
                $row['sequence'],
                $row['correlationId'],
                $row['createdBy'],
                $row['createdDateTime'],
                $row['subject'],
                $row['subjectId'],
                $row['subjectSequence'],
                $row['subjectName'],
                $row['jsonRootObjectSchema'],
                $row['eventName'],
                $row['currentState'],
            );
        }
        return new self($states);
    }

    /**
     * @return StateChanged[]
     */
    final public function toStates(): array
    {
        return $this->states;
    }
}