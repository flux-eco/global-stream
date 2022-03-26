<?php


namespace FluxEco\GlobalStream\Adapters\Publisher;

use FluxEco\GlobalStream\Core\Domain;
use FluxEco\GlobalStream\Core\Ports;
use Swoole;

class StatePublisherClient implements Ports\Publisher\StatePublisher
{

    private ConsumerRegistry $consumerRegistry;

    private function __construct(ConsumerRegistry $consumerRegistry)
    {
        $this->consumerRegistry = $consumerRegistry;
    }

    public static function new(ConsumerRegistry $consumerRegistry): self
    {
        return new self($consumerRegistry);
    }

    public function publish(Domain\StateChanged $state): void
    {
        if ($this->consumerRegistry->has()) {

            $message = MessageAdapter::fromDomain($state)->toMessage();
            $consumers = $this->consumerRegistry->getConsumerClients();

            foreach ($consumers as $consumer) {
                $consumer->publish($message);
            }
        }
    }

}