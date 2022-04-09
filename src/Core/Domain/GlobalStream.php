<?php


namespace FluxEco\GlobalStream\Core\Domain;

use FluxEco\GlobalStream\{Core\Ports};

class GlobalStream
{
    protected static ?self $instance = null;

    private array $loadedSubjects = [];

    /** @var StateChanged[] */
    private array $recordedStates = [];
    private array $states = [];
    private int $lastSequence = 0;
    private Ports\Outbounds $outbounds;


    private function __construct(
        Ports\Outbounds $outbounds
    )
    {
        $this->outbounds = $outbounds;
    }

    final public function getNextSequence(): int
    {
        return ($this->getLastSequence() + 1);
    }

    public static function new(
        Ports\Outbounds $outbounds,
        array  $subjectNames
    ): self
    {
        if (static::$instance === null) {
            static::$instance = new self($outbounds);
        }
        foreach ($subjectNames as $subjectName) {
            if (!array_key_exists($subjectName, static::$instance->loadedSubjects)) {
                static::$instance->loadCurrentStates($subjectName);
            }
        }
        return static::$instance;
    }

    final public function loadCurrentStates(string $subjectName): void
    {
        $queriedRows = $this->outbounds->queryStates($subjectName);
        foreach ($queriedRows as $state) {
            $subjectId = $state->getSubjectId();
            $this->applyState($subjectId, $state);
        }
        $this->loadedSubjects[] = $subjectName;
    }

    final public function applyRecordAndPublish(StateChanged $state): void
    {
        $subjectId = $state->getSubjectId();
        $this->applyState($subjectId, $state);
        $this->recordState($subjectId, $state);
        $this->publish($state);
    }

    private function recordState(string $subjectId, StateChanged $state): void
    {
        $this->recordedStates[$subjectId] = $state;
    }

    private function publish(StateChanged $state): void
    {
        $this->outbounds->publishStateChanged($state);
    }

    final public function applyState(string $subjectId, StateChanged $state): void
    {
        $this->lastSequence = $state->getSequence();
        $this->states[$subjectId] = $state;
    }


    final public function getLastSequence(): int
    {
        return $this->lastSequence;
    }


    final public function hasStates(): bool
    {
        return count($this->states) > 0;
    }

    final public function hasRecordedStates(): bool
    {
        return count($this->recordedStates) > 0;
    }

    /**
     * @return StateChanged[]
     */
    final public function getStates(): array
    {
        return $this->states;
    }

    /**
     * @return StateChanged[]
     */
    final public function getRecordedStates(): array
    {
        return $this->recordedStates;
    }

    final public function flushRecordedStates(): void
    {
        $this->recordedStates = [];
    }

    final public function storeRecordedStates(): void
    {
        if ($this->hasRecordedStates() === true) {
            foreach ($this->recordedStates as $recordedState) {
                $this->outbounds->storeState(
                    $recordedState->getSequence(),
                    $recordedState->getCorrelationId(),
                    $recordedState->getCreatedBy(),
                    $recordedState->getCreatedDateTime(),
                    $recordedState->getSubjectId(),
                    $recordedState->getSubjectSequence(),
                    $recordedState->getSubject(),
                    $recordedState->getSubjectName(),
                    $recordedState->getJsonRootObjectSchema(),
                    $recordedState->getEventName(),
                    $recordedState->getCurrentState()
                );
            }
            $this->flushRecordedStates();
        }
    }
}