<?php

declare(strict_types=1);

namespace HubspotTags\Domain;

final class Contact
{
    /**
     * @var Activity[] $activities
     */
    private $activities;

    public function __construct()
    {
        $this->activities = [];
    }

    public function addActivity(Activity $activity)
    {
        $this->activities[] = $activity;
    }

    public function getActivityTags()
    {
    }
}
