<?php

declare(strict_types=1);

namespace HubspotTags\UseCase;

use HubspotTags\Domain\Activity;
use HubspotTags\Domain\Contact;
use HubspotTags\Domain\ContactRepositoryInterface;
use HubspotTags\Domain\ValueObject\ContactIdentifierInterface;
use HubspotTags\Domain\ValueObject\DemoTag;

class GetSingleContactDemoTagActivities implements UseCaseInterface
{
    /**
     * @var ContactRepositoryInterface
     */
    private $contactRepository;

    /**
     * @var ContactIdentifierInterface
     */
    private $targetContactId;

    public function __construct(ContactRepositoryInterface $contactRepository, ContactIdentifierInterface $targetContact)
    {
        $this->contactRepository = $contactRepository;
        $this->targetContactId = $targetContact;
    }

    public function execute()
    {
        /** @var Contact $contact */
        $contact = $this->contactRepository->getSingleContact($this->targetContactId);

        return array_filter($contact->getActivities(), function ($activity) {
            /* @var Activity $activity */
            return DemoTag::class === get_class($activity->getTag());
        });
    }
}
