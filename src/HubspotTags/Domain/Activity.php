<?php

declare(strict_types=1);

namespace HubspotTags\Domain;

use DateTimeImmutable;
use HubspotTags\Domain\ValueObject\AbstractTag;
use HubspotTags\Domain\ValueObject\ActivityIdentifierInterface;

final class Activity
{
    /**
     * @var DateTimeImmutable
     */
    private $time;

    /**
     * @var AbstractTag
     */
    private $tag;

    /**
     * @var ActivityIdentifierInterface
     */
    private $identifier;

    public function __construct(ActivityIdentifierInterface $identifier, DateTimeImmutable $time, AbstractTag $tag)
    {
        $this->identifier = $identifier;
        $this->tag = $tag;
        $this->time = $time;
    }

    /**
     * @return AbstractTag
     */
    public function getTag(): AbstractTag
    {
        return $this->tag;
    }

    /**
     * @return ActivityIdentifierInterface
     */
    public function getIdentifier(): ActivityIdentifierInterface
    {
        return $this->identifier;
    }

    /**
     * Creates a string in format of 'Y-m-d' of the instance's creation date.
     *
     * @return string
     */
    public function getCreationDateString(): string
    {
        return $this->time->format('Y-m-d');
    }
}
