<?php

declare(strict_types=1);

namespace HubspotTags\UseCase;

use HubspotTags\Domain\Contact;
use HubspotTags\Domain\ContactRepositoryInterface;

final class GetAllContactsCloseTags implements UseCaseInterface
{
    /**
     * @var ContactRepositoryInterface
     */
    private $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function execute()
    {
        $aggregate = [];
        /** @var Contact $contact */
        foreach ($this->contactRepository->getAllContacts() as $contact) {
            $aggregate[strval($contact->getIdentifier())] = (new GetSingleContactCloseTagActivities(
                $this->contactRepository, $contact->getIdentifier()))->execute();
        }

        return $aggregate;
    }
}
