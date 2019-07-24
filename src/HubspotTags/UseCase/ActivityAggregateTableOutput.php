<?php

declare(strict_types=1);

namespace HubspotTags\UseCase;

use HubspotTags\Domain\ActivityAggregate;

final class ActivityAggregateTableOutput implements ActivityAggregateOutputInterface
{
    public function generateOutput(ActivityAggregate $activityAggregate): string
    {
        $result = '';
        $mask = "|%-10s |%-5s |%-5s |\n";
        $result .= sprintf($mask, 'Day', 'CLOSE', 'DEMO');
        foreach ($activityAggregate->getItems() as $day => $values) {
            $result .= sprintf($mask, $day, $values['CLOSE'], $values['DEMO']);
        }

        return $result;
    }
}
