<?php

declare(strict_types=1);

namespace HubspotTags\UseCase;

use App\HubspotTags\UseCase\ActivityAggregateCreationUseCaseInterface;
use HubspotTags\Domain\ActivityAggregate;
use HubspotTags\Domain\Contact;
use HubspotTags\Domain\ContactRepositoryInterface;

final class GetAllContactsCloseAndDemoAggregate implements ActivityAggregateCreationUseCaseInterface
{
    private $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function execute(): ActivityAggregate
    {
        $allContacts = $this->contactRepository->getAllContacts();
        $aggregate = new ActivityAggregate();
        /** @var Contact $contact */
        foreach ($allContacts as $contact) {
            $contactAggregate = (new GetSingleContactsCloseAndDemoAggregate($this->contactRepository,
                $contact->getIdentifier()))->execute();
            $aggregate->extendWithAggregate($contactAggregate);
        }

        return $aggregate;
    }
}
