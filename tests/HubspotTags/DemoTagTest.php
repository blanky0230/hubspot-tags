<?php

declare(strict_types=1);

namespace HubspotTags\Test;

use HubspotTags\Domain\ValueObject\DemoTag;
use HubspotTags\Domain\ValueObject\TagNotMatchingException;
use PHPUnit\Framework\TestCase;

class DemoTagTest extends TestCase
{
    public function testCanCreateWithValidInput()
    {
        $this->assertInstanceOf(DemoTag::class, new DemoTag('FooBar DEMO'));
    }

    public function testThrowsTagNotMatchExceptionIfTagIsNotFound()
    {
        $this->expectException(TagNotMatchingException::class);
        new DemoTag('FooBar');
    }
}
