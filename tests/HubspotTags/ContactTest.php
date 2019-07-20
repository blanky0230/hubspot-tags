<?php

declare(strict_types=1);

namespace HubspotTags\Test;

use HubspotTags\Domain\Contact;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    public function testCanCreate()
    {
        $this->assertInstanceOf(Contact::class, new Contact());
    }
}
