<?php

declare(strict_types=1);

namespace HubspotTags\Test;

use HubspotTags\Domain\ValueObject\ContactMailIdentifier;
use PHPUnit\Framework\TestCase;

class ContactMailIdentifierTest extends TestCase
{
    public function testCanCompareAsString()
    {
        $this->assertEquals('foo@bar.com', new ContactMailIdentifier('foo@bar.com'));
    }
}
