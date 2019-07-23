<?php

declare(strict_types=1);

require_once '../../../vendor/autoload.php';

//namespace HubspotTags\Test;

use DateTimeImmutable;
use HubspotTags\UseCase\GetAllContactsCloseAndDemoAggregate;
use HubspotTags\Domain\Activity;
use HubspotTags\Domain\ActivityRepositoryInterface;
use HubspotTags\Domain\Contact;
use HubspotTags\Domain\ContactRepositoryInterface;
use HubspotTags\Domain\InMemoryActivityRepository;
use HubspotTags\Domain\InMemoryContactRepository;
use HubspotTags\Domain\ValueObject\ActivityBasicId;
use HubspotTags\Domain\ValueObject\CloseTag;
use HubspotTags\Domain\ValueObject\ContactBasicId;
use HubspotTags\Domain\ValueObject\DemoTag;

$contactRepo = new InMemoryContactRepository();
$activityRepo = new InMemoryActivityRepository($contactRepo);

function fill_repos_with_data(
    ContactRepositoryInterface $contactRepository,
    ActivityRepositoryInterface $activityRepository)
{
    for ($i = 0; $i < 100; ++$i) {
        $c = new Contact(new ContactBasicId($i));
        $contactRepository->addContact($c);
        for ($j = 0; $j < rand(10, 4096); ++$j) {
            switch (rand(0, 2)) {
                case 0:
                    $a = new Activity(new ActivityBasicId($j),
                        new DateTimeImmutable(sprintf('%d days', rand(-100, 0))),
                        new CloseTag('FooBar CLOSE'));
                    $activityRepository->addActivity($c->getIdentifier(), $a);
                    break;
                case 1:
                    $a = new Activity(new ActivityBasicId($j),
                        new DateTimeImmutable(sprintf('%d days', rand(-100, 0))),
                        new DemoTag('BAZ DEMO'));
                    $activityRepository->addActivity($c->getIdentifier(), $a);
                    break;
                case 2:
                    break;
            }
        }
    }
}

fill_repos_with_data($contactRepo, $activityRepo);

$useCase = new GetAllContactsCloseAndDemoAggregate($contactRepo);
//TODO Sort
$data = $useCase->execute();
echo (new \HubspotTags\UseCase\ActivityActivityAggregateTable())->generateOutput($data);
