<?php

declare(strict_types=1);

namespace HubspotTags\Test;

use HubspotTags\Domain\Activity;
use HubspotTags\Domain\Contact;
use HubspotTags\Domain\ValueObject\ActivityBasicId;
use HubspotTags\Domain\ValueObject\CloseTag;
use HubspotTags\Domain\ValueObject\ContactBasicId;
use HubspotTags\Domain\ValueObject\ContactMailIdentifier;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    public function testCanCreate()
    {
        $this->assertInstanceOf(Contact::class, new Contact(new ContactBasicId(1)));
    }

    public function testCanCreateWithMailAsID()
    {
        $this->assertInstanceOf(Contact::class, new Contact(new ContactMailIdentifier('foo@bar.com')));
    }

    public function testCanAddActivty()
    {
        $contact = new Contact(new ContactBasicId(21321));
        $this->assertCount(1, $contact->addActivity(
            new Activity(new ActivityBasicId(12312), new \DateTimeImmutable(), new CloseTag('CLOSE')))->getActivities());
    }

    public function testCanGetIdentifier()
    {
        $id = new ContactBasicId(123124212);
        $contact = new Contact($id);
        $this->assertSame($id, $contact->getIdentifier());
    }
}
