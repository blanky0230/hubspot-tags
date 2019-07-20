<?php

declare(strict_types=1);

namespace HubspotTags\Domain;

use HubspotTags\Domain\ValueObject\ContactIdentifierInterface;

final class InMemoryContactRepository implements ContactRepositoryInterface
{
    /**
     * @var Contact[]
     */
    private $contacts;

    public function __construct()
    {
        $this->contacts = [];
    }

    public function getAllContacts(): array
    {
        return $this->contacts;
    }

    public function getSingleContact(ContactIdentifierInterface $identifier): Contact
    {
        return $this->contacts[strval($identifier)];
    }

    public function addContact(Contact $contact): ContactRepositoryInterface
    {
        if (!array_key_exists(strval($contact->getIdentifier()), $this->contacts)) {
            $this->contacts[strval($contact->getIdentifier())] = $contact;
        }

        return $this;
    }
}
