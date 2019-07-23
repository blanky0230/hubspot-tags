<?php

declare(strict_types=1);

namespace HubspotTags\Test;

use HubspotTags\Domain\Contact;
use HubspotTags\Domain\InMemoryContactRepository;
use HubspotTags\Domain\ValueObject\ContactBasicId;
use PHPUnit\Framework\TestCase;

class InMemoryContactRepositoryTest extends TestCase
{
    public function testCanInstantiate()
    {
        $this->assertInstanceOf(InMemoryContactRepository::class, new InMemoryContactRepository());
    }

    public function testCanAddContact()
    {
        $repo = new InMemoryContactRepository();
        $repo->addContact(new Contact(new ContactBasicId(123123)));
        $this->assertInstanceOf(Contact::class, array_pop($repo->getAllContacts()));
    }

    public function testCanRetrieveContactViaId()
    {
        $id = new ContactBasicId(123);
        $contact = new Contact($id);
        $this->assertSame($contact, (new InMemoryContactRepository())->addContact($contact)->getSingleContact($id));
    }
}
