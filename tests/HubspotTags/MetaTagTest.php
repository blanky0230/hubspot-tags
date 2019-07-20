<?php

declare(strict_types=1);

namespace HubspotTags\Test;

use HubspotTags\Domain\ValueObject\MetaTag;
use HubspotTags\Domain\ValueObject\TagNotMatchingException;
use PHPUnit\Framework\TestCase;

class MetaTagTest extends TestCase
{
    public function testCanMatchWithCustomInput()
    {
        $this->assertInstanceOf(MetaTag::class, new MetaTag('Foo Bar blabkabka \n \r \t FbasBza COOKIE', 'COOKIE'));
    }

    public function testWillThrowExceptionIfCustomTagCannotBeFound()
    {
        $this->expectException(TagNotMatchingException::class);
        new MetaTag('FooBar', 'COOKIE');
    }
}
