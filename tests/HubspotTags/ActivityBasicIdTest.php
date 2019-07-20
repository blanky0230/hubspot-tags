<?php

declare(strict_types=1);

namespace HubspotTags\Test;

use HubspotTags\Domain\ValueObject\ActivityBasicId;
use PHPUnit\Framework\TestCase;

class ActivityBasicIdTest extends TestCase
{
    public function testCanCompareAsString()
    {
        $this->assertEquals('123', new ActivityBasicId(123));
    }
}
