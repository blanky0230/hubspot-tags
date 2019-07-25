<?php

declare(strict_types=1);

namespace HubspotTags\UseCase;

use HubspotTags\Domain\ActivityAggregate;

final class ActivityAggregateJsonPrettyOutput implements ActivityAggregateOutputInterface
{
    public function generateOutput(ActivityAggregate $activityAggregate): string
    {
        $data = json_encode($activityAggregate->getItems(), JSON_PRETTY_PRINT);
        if ($data) {
            return $data;
        }

        return '';
    }
}
