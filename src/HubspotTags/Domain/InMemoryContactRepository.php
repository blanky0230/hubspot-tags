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

    /**
     * @return Contact[]
     */
    public function getAllContacts(): array
    {
        return $this->contacts;
    }

    /**
     * @param ContactIdentifierInterface $identifier
     *
     * @return Contact|null
     */
    public function getSingleContact(ContactIdentifierInterface $identifier): ?Contact
    {
        if (array_key_exists(strval($identifier), $this->contacts)) {
            return $this->contacts[strval($identifier)];
        }

        return null;
    }

    /**
     * @param Contact $contact
     *
     * @return ContactRepositoryInterface
     */
    public function addContact(Contact $contact): ContactRepositoryInterface
    {
        if (!array_key_exists(strval($contact->getIdentifier()), $this->contacts)) {
            $this->contacts[strval($contact->getIdentifier())] = $contact;
        }

        return $this;
    }
}
