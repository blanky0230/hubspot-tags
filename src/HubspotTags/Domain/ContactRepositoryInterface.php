<?php

declare(strict_types=1);

namespace HubspotTags\Domain;

use HubspotTags\Domain\ValueObject\ContactIdentifierInterface;

interface ContactRepositoryInterface
{
    public function getAllContacts(): array;

    public function getSingleContact(ContactIdentifierInterface $identifier): ?Contact;

    public function addContact(Contact $contact): self;
}
