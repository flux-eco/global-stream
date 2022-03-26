<?php


namespace FluxEco\GlobalStream\Adapters\Publisher;

use FluxEco\GlobalStream\Core\Ports;


class ConsumerRegistry
{
    const SUBJECT_AGGREGATE_ROOT = 'AggregateRoot';

    protected static array $instances = [];
    /** @param ConsumerApiClient[] $consumerClients */
    private array $consumerClients = [];

    /** @param ConsumerApiClient[] $consumerClients */
    private function __construct(array $consumerClients)
    {
        $this->consumerClients = $consumerClients;
    }

    /**
     * @param string $subject
     * @param ConsumerApiClient[] $consumerClients
     * @return static
     */
    public static function new(string $subject, array $consumerClients): self
    {
        if (empty($instances[$subject])) {
            self::$instances[$subject] = new self($consumerClients);
        }
        return self::$instances[$subject];
    }

    final public function has(): bool
    {
        return !empty($this->consumerClients);
    }

    /**
     * @return consumerApiClient[]
     */
    final public function getConsumerClients(): array
    {
        if (!$this->has()) {
            return [];
        }
        return $this->consumerClients;
    }
}