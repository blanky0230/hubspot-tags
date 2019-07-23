<?php

declare(strict_types=1);

namespace HubspotTags\Domain;

use HubspotTags\Domain\ValueObject\ActivityIdentifierInterface;
use HubspotTags\Domain\ValueObject\ContactIdentifierInterface;

final class InMemoryActivityRepository implements ActivityRepositoryInterface
{
    /**
     * @var Activity[]
     */
    private $activities;

    /**
     * @var ContactRepositoryInterface
     */
    private $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
        $this->activities = [];
    }

    public function getContactActivities(ContactIdentifierInterface $identifier): array
    {
        $contact = $this->contactRepository->getSingleContact($identifier);
        if (null === $contact) {
            return [];
        }

        return $contact->getActivities();
    }

    public function getActivity(ActivityIdentifierInterface $identifier): ?Activity
    {
        if (!array_key_exists(strval($identifier), $this->activities)) {
            return null;
        }

        return $this->activities[strval($identifier)];
    }

    public function addActivity(ContactIdentifierInterface $contactIdentifier, Activity $activity): ActivityRepositoryInterface
    {
        $contact = $this->contactRepository->getSingleContact($contactIdentifier);
        if (null !== $contact && !array_key_exists(strval($activity->getIdentifier()), $this->activities)) {
            $this->activities[strval($activity->getIdentifier())] = $activity;
            $contact->addActivity($activity);
        }

        return $this;
    }

    public function getAllActivities(): array
    {
        return $this->activities;
    }
}
