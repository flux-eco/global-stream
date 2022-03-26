<?php

namespace FluxEco\GlobalStream\Adapters\Api;

interface StatePublished
{
    public function getSubjectName(): string;

    public function getMessageName(): string;

    public function getSubjectId(): string;

    public function getHeaders(): array;

    public function getJsonRootObjectSchema(): string;

    public function getPayload(): string;
}