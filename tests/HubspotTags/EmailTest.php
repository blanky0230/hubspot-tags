<?php

declare(strict_types=1);

namespace HubspotTags\Test;

use HubspotTags\Domain\ValueObject\Email;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testCanCreateWithValid()
    {
        $this->assertInstanceOf(Email::class, new Email('foo@bar.com'));
    }

    public function testThrowsInvalidArgumentExceptionOnInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        new Email('hugenddybeblasdbbe.adsd.-asd.asdcom');
    }

    public function testCanCompareAsString()
    {
        $this->assertEquals('foo@bar.com', new Email('foo@bar.com'));
    }
}
