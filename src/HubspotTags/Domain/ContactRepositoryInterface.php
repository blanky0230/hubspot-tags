<?php

declare(strict_types=1);

namespace HubspotTags\Domain;

use HubspotTags\Domain\ValueObject\ContactIdentifierInterface;

interface ContactRepositoryInterface
{
    /**
     * @return array
     */
    public function getAllContacts(): array;

    /**
     * @param ContactIdentifierInterface $identifier
     *
     * @return Contact|null
     */
    public function getSingleContact(ContactIdentifierInterface $identifier): ?Contact;

    /**
     * @param Contact $contact
     *
     * @return ContactRepositoryInterface
     */
    public function addContact(Contact $contact): self;
}
