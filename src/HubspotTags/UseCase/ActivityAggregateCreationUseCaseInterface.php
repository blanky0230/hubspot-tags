<?php

declare(strict_types=1);

namespace App\HubspotTags\UseCase;

use HubspotTags\Domain\ActivityAggregate;
use HubspotTags\UseCase\UseCaseInterface;

interface ActivityAggregateCreationUseCaseInterface extends UseCaseInterface
{
    public function execute(): ActivityAggregate;
}
