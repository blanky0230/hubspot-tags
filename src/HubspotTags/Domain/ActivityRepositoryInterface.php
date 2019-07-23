<?php

declare(strict_types=1);

namespace HubspotTags\Domain;

use HubspotTags\Domain\ValueObject\ActivityIdentifierInterface;
use HubspotTags\Domain\ValueObject\ContactIdentifierInterface;

interface ActivityRepositoryInterface
{
    public function getContactActivities(ContactIdentifierInterface $identifier): array;

    public function getAllActivities(): array;

    public function getActivity(ActivityIdentifierInterface $identifier): ?Activity;

    public function addActivity(ContactIdentifierInterface $contactIdentifier, Activity $activity): self;
}
