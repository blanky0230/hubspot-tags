<?php

declare(strict_types=1);

namespace HubspotTags\UseCase;

use HubspotTags\Domain\Contact;
use HubspotTags\Domain\ContactRepositoryInterface;

class GetAllContactsCloseAndDemoAggregate implements UseCaseInterface
{
    private $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function execute()
    {
        $allContacts = $this->contactRepository->getAllContacts();
        $aggregate = [];
        /** @var Contact $contact */
        foreach ($allContacts as $contact) {
            $contactAggregate = (new GetSingleContactsCloseAndDemoAggregate($this->contactRepository,
                $contact->getIdentifier()))->execute();
            foreach ($contactAggregate as $date => $values) {
                if (!array_key_exists($date, $aggregate)) {
                    $aggregate[$date] = $values;
                } else {
                    $aggregate[$date]['DEMO'] += $values['DEMO'];
                    $aggregate[$date]['CLOSE'] += $values['CLOSE'];
                }
            }
        }

        return $aggregate;
    }
}
