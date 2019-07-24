<?php

declare(strict_types=1);

namespace HubspotTags\Domain;

use HubspotTags\Domain\ValueObject\ActivityIdentifierInterface;
use HubspotTags\Domain\ValueObject\ContactIdentifierInterface;

interface ActivityRepositoryInterface
{
    /**
     * @param ContactIdentifierInterface $identifier
     *
     * @return array
     */
    public function getContactActivities(ContactIdentifierInterface $identifier): array;

    /**
     * @return array
     */
    public function getAllActivities(): array;

    /**
     * @param ActivityIdentifierInterface $identifier
     *
     * @return Activity|null
     */
    public function getActivity(ActivityIdentifierInterface $identifier): ?Activity;

    /**
     * @param ContactIdentifierInterface $contactIdentifier
     * @param Activity                   $activity
     *
     * @return ActivityRepositoryInterface
     */
    public function addActivity(ContactIdentifierInterface $contactIdentifier, Activity $activity): self;
}
