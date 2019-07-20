<?php


namespace HubspotTags\Test;


use HubspotTags\Domain\Activity;
use HubspotTags\Domain\Contact;
use HubspotTags\Domain\InMemoryActivityRepository;
use HubspotTags\Domain\InMemoryContactRepository;
use HubspotTags\Domain\ValueObject\ActivityBasicId;
use HubspotTags\Domain\ValueObject\CloseTag;
use HubspotTags\Domain\ValueObject\ContactBasicId;
use HubspotTags\Domain\ValueObject\DemoTag;
use HubspotTags\UseCase\GetSingleContactCloseTagActivities;
use PHPUnit\Framework\TestCase;

class GetSingleContactCloseTagsUseCaseTest extends TestCase
{

    public function testCanCreate()
    {
        $this->assertInstanceOf(GetSingleContactCloseTagActivities::class,
            new GetSingleContactCloseTagActivities(new InMemoryContactRepository(), new ContactBasicId(123)));
    }

    public function testCanExecuteWithEmptyResult()
    {
        $repo = new InMemoryContactRepository();
        $contact = new Contact(new ContactBasicId(123));
        $repo->addContact($contact);
        $this->assertEmpty((new GetSingleContactCloseTagActivities($repo, $contact->getIdentifier()))->execute());
    }

    public function testWillReturnAllActivitiesWithCloseTag()
    {
        $contactRepo = new InMemoryContactRepository();
        $c = new Contact(new ContactBasicId(111));
        $contactRepo->addContact($c);
        $activityRepo = new InMemoryActivityRepository($contactRepo);
        $close = new Activity(new ActivityBasicId(123123), new \DateTimeImmutable(), new CloseTag('Foasdlassd CLOSE'));
        $other = new Activity(new ActivityBasicId(1111), new \DateTimeImmutable(), new DemoTag('DEMO'));
        $activityRepo->addActivity($c->getIdentifier(), $close)->addActivity($c->getIdentifier(), $other);
        $useCase = new GetSingleContactCloseTagActivities($contactRepo, $c->getIdentifier());
        $this->assertCount(2, $c->getActivities());
        $this->assertCount(1, $useCase->execute());
    }

}