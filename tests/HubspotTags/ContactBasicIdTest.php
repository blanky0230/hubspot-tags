<?php

declare(strict_types=1);

namespace HubspotTags\Test;

use HubspotTags\Domain\ValueObject\ContactBasicId;
use PHPUnit\Framework\TestCase;

class ContactBasicIdTest extends TestCase
{
    public function testCanCompareAsString()
    {
        $this->assertEquals('1', new ContactBasicId(1));
    }
}
