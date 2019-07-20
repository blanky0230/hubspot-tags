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
use PHPUnit\Framework\TestCase;

class InMemoryActivityRepositoryTest extends TestCase
{

    public function testCanInstantiate()
    {
        $this->assertInstanceOf(InMemoryActivityRepository::class, new InMemoryActivityRepository(new InMemoryContactRepository()));
    }

    public function testCanAddNewActivity()
    {
        $contact = new Contact(new ContactBasicId(123));
        $cRepo = new InMemoryContactRepository();
        $cRepo->addContact($contact);
        $aRepo = new InMemoryActivityRepository($cRepo);
        $activity = new Activity(new ActivityBasicId(123123), new \DateTimeImmutable(), new CloseTag('CLOSE'));
        $aRepo->addActivity(new ContactBasicId(123), $activity);
        $this->assertCount(1, $aRepo->getAllActivities());
        $this->assertSame($activity, $aRepo->getActivity(new ActivityBasicId(123123)));
    }

    /**
     * @dataProvider generateSomeActivities
     */
    public function testCanGetContactActivities(Activity $activity)
    {
        $contact = new Contact(new ContactBasicId(1337));
        $cRepo = new InMemoryContactRepository();
        $cRepo->addContact($contact);
        $aRepo = new InMemoryActivityRepository($cRepo);
        $aRepo->addActivity($contact->getIdentifier(), $activity);
        $this->assertSame($contact->getActivities(), $aRepo->getContactActivities($contact->getIdentifier()));

    }

    public function generateSomeActivities()
    {
        for ($i =0; $i < 10; $i++) {
            yield [new Activity(new ActivityBasicId($i), new \DateTimeImmutable(), new DemoTag('DEMO'))];
        }
    }

}