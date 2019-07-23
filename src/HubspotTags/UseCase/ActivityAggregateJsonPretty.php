<?php

declare(strict_types=1);

namespace HubspotTags\UseCase;

use HubspotTags\Domain\ActivityAggregate;

final class ActivityAggregateJsonPretty implements ActivityAggregateOutputInterface
{
    public function generateOutPut(ActivityAggregate $activityAggregate): string
    {
        $data = json_encode($activityAggregate->getItems(), JSON_PRETTY_PRINT);
        if ($data) {
            return $data;
        }

        return '';
    }
}
