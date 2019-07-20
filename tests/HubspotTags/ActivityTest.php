<?php

declare(strict_types=1);

namespace HubspotTags\Test;

use HubspotTags\Domain\Activity;
use HubspotTags\Domain\ValueObject\ActivityBasicId;
use HubspotTags\Domain\ValueObject\CloseTag;
use HubspotTags\Domain\ValueObject\MetaTag;
use PHPUnit\Framework\TestCase;

class ActivityTest extends TestCase
{
    public function testCanInstantiate()
    {
        $this->assertInstanceOf(Activity::class,
            new Activity(new ActivityBasicId(1), new \DateTimeImmutable(), new MetaTag('BAR', 'BAR')));
    }

    public function testCanGetTag()
    {
        $tag = new MetaTag('askljdlsajlj asdf', 'asdf');
        $activity = new Activity(new ActivityBasicId(231), new \DateTimeImmutable(), $tag);
        $this->assertSame($tag, $activity->getTag());
    }

    public function testCanGetIdentifier()
    {
        $id = new ActivityBasicId(213124213);
        $activity = new Activity($id, new \DateTimeImmutable(), new CloseTag('CLOSE'));
        $this->assertSame($id, $activity->getIdentifier());
    }
}
