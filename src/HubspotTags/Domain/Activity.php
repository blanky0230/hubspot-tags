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

    public function getTag(): AbstractTag
    {
        return $this->tag;
    }

    public function getIdentifier(): ActivityIdentifierInterface
    {
        return $this->identifier;
    }

    public function getCreationDateString(): string
    {
        return $this->time->format('Y-m-d');
    }
}
