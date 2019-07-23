<?php

declare(strict_types=1);

namespace HubspotTags\UseCase;

use HubspotTags\Domain\Activity;
use HubspotTags\Domain\ActivityAggregate;
use HubspotTags\Domain\ContactRepositoryInterface;
use HubspotTags\Domain\ValueObject\CloseTag;
use HubspotTags\Domain\ValueObject\ContactIdentifierInterface;
use HubspotTags\Domain\ValueObject\DemoTag;

class GetSingleContactsCloseAndDemoAggregate implements UseCaseInterface
{
    /**
     * @var ContactRepositoryInterface
     */
    private $contactRepository;

    /**
     * @var ContactIdentifierInterface
     */
    private $targetContact;

    public function __construct(ContactRepositoryInterface $contactRepository, ContactIdentifierInterface $targetContact)
    {
        $this->contactRepository = $contactRepository;
        $this->targetContact = $targetContact;
    }

    public function execute()
    {
        $all = array_merge(
            (new GetSingleContactCloseTagActivities($this->contactRepository, $this->targetContact))->execute(),
            (new GetSingleContactDemoTagActivities($this->contactRepository, $this->targetContact))->execute()
        );
        $aggregate = new ActivityAggregate();
        /** @var Activity $activity */
        foreach ($all as $activity) {
            $isDemo = DemoTag::class === get_class($activity->getTag());
            $isClose = CloseTag::class === get_class($activity->getTag());
            if ($isClose) {
                $aggregate->incrementClose($activity->getCreationDateString());
            } elseif ($isDemo) {
                $aggregate->incrementDemo($activity->getCreationDateString());
            }
        }

        return $aggregate;
    }
}
