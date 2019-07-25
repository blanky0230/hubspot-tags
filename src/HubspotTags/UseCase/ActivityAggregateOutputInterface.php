<?php

declare(strict_types=1);

namespace HubspotTags\UseCase;

use HubspotTags\Domain\ActivityAggregate;

interface ActivityAggregateOutputInterface
{
    public function generateOutput(ActivityAggregate $activityAggregate): string;
}
