<?php


namespace FluxEco\GlobalStream\Adapters\Projection;

use FluxEco\GlobalStream\Adapters;
use FluxEco\Projection\Adapters\Api;

class ProjectionClient implements Adapters\Publisher\ConsumerApiClient
{
    private Api\ProjectionApi $projectionApi;

    private function __construct(Api\ProjectionApi $projectionApi)
    {
        $this->projectionApi = $projectionApi;
    }

    public static function new(): self
    {
        $projectionApi = Api\ProjectionApi::new();
        return new self($projectionApi);
    }

    public function publish(Adapters\Publisher\Message $message): void
    {
        $this->projectionApi->receiveAggregateRootStatePublished($message);
    }
}