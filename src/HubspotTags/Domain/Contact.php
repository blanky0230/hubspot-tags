<?php

declare(strict_types=1);

namespace HubspotTags\Domain;

use HubspotTags\Domain\ValueObject\ContactIdentifierInterface;

final class Contact
{
    /**
     * @var Activity[]
     */
    private $activities;

    /**
     * @var ContactIdentifierInterface
     */
    private $identifier;

    public function __construct(ContactIdentifierInterface $identifier)
    {
        $this->identifier = $identifier;
        $this->activities = [];
    }

    /**
     * @return array
     */
    public function getActivities(): array
    {
        return $this->activities;
    }

    /**
     * @param Activity $activity
     *
     * @return Contact
     */
    public function addActivity(Activity $activity): Contact
    {
        $this->activities[] = $activity;

        return $this;
    }

    /**
     * @return ContactIdentifierInterface
     */
    public function getIdentifier(): ContactIdentifierInterface
    {
        return $this->identifier;
    }
}
