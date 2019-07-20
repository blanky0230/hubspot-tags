<?php

declare(strict_types=1);

namespace HubspotTags\Domain;

use HubspotTags\Domain\ValueObject\ContactIdentifierInterface;

final class Contact
{
    /**
     * @var Activity[]
     */
    private $activities;

    /**
     * @var ContactIdentifierInterface
     */
    private $identifier;

    public function __construct(ContactIdentifierInterface $identifier)
    {
        $this->identifier = $identifier;
        $this->activities = [];
    }
}
