<?php


namespace HubspotTags\Domain;


use DateTimeImmutable;
use HubspotTags\Domain\ValueObject\AbstractTag;

final class Activity
{
    /**
     * @var DateTimeImmutable $time
     */
    private $time;

    /**
     * @var AbstractTag $tag;
     */
    private $tag;

    public function __construct(DateTimeImmutable $time, AbstractTag $tag)
    {
        $this->tag = $tag;
        $this->time = $time;
    }

    //TODO Comparison method for soring by DATE or by TAG?
}