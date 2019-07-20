<?php

declare(strict_types=1);

namespace HubspotTags\Domain;

use HubspotTags\Domain\ValueObject\ContactIdentifierInterface;

interface ActivityRepositoryInterface
{
    public function getContactActivities(ContactIdentifierInterface $identifier): array;
}
