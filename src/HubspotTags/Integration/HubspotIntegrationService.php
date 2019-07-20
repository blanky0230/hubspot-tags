<?php

namespace HubspotTags\Integration;

use DateTimeImmutable;
use GuzzleHttp\Client;
use HubspotTags\Domain\Activity;
use HubspotTags\Domain\ActivityRepositoryInterface;
use HubspotTags\Domain\Contact;
use HubspotTags\Domain\ContactRepositoryInterface;
use HubspotTags\Domain\ValueObject\AbstractTag;
use HubspotTags\Domain\ValueObject\ActivityBasicId;
use HubspotTags\Domain\ValueObject\ActivityIdentifierInterface;
use HubspotTags\Domain\ValueObject\CloseTag;
use HubspotTags\Domain\ValueObject\ContactBasicId;
use HubspotTags\Domain\ValueObject\ContactIdentifierInterface;
use HubspotTags\Domain\ValueObject\DemoTag;
use HubspotTags\Domain\ValueObject\TagNotMatchingException;
use RuntimeException;

class HubspotIntegrationService implements ContactRepositoryInterface, ActivityRepositoryInterface
{
    private $hubsSpotApiKey;

    private $httpClient;

    public function __construct(string $hubSpotApiKey)
    {
        $this->hubsSpotApiKey = $hubSpotApiKey;
        $this->httpClient = new Client();
    }

    public function getAllContacts(): array
    {
        // TODO: Implement getAllContacts() method.
    }

    public function getSingleContact(ContactIdentifierInterface $identifier): Contact
    {
        // TODO: Implement getSingleContact() method.
    }

    public function addContact(Contact $contact): ContactRepositoryInterface
    {
        throw new RuntimeException('Write Operations are not implemented (yet)!');
    }

    public function getContactActivities(ContactIdentifierInterface $identifier): array
    {
        // TODO: Implement getContactActivities() method.
    }

    public function getAllActivities(): array
    {

        $data = $this->httpClient->get(
            'https://api.hubapi.com/engagements/v1/engagements/paged?'.http_build_query(
                ['hapikey' => $this->hubsSpotApiKey]));
        $result = json_decode($data->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
        if ($result['hasMore']) {
            //TODO
        }
        $resultData = [];
        foreach ($result['results'] as $inputData) {
            $activityId = new ActivityBasicId($inputData['engagement']['id']);
//            $contactIdentifier = new ContactBasicId($inputData['associations']['contactIds'][0]);
//            $actualContact = new Contact($contactIdentifier);

//            if ($this->getSingleContact($contactIdentifier)) {
//                $actualContact = $this->getSingleContact($actualContact);
//            }
            $date = DateTimeImmutable::createFromFormat('U', (int) ($inputData['engagement']['createdAt'] / 1000));
            $tag = $this->createTagFromInputString($inputData['metadata']['body']);
            if ($tag) {
                $activity  = new Activity($activityId, $date, $tag);
//                $actualContact->addActivity($activity);
                $resultData[] = $activity;
            }
        }
        return $resultData;
    }

    /**
     * @param string $input
     * @return AbstractTag|null
     */
    private function createTagFromInputString(string $input): ?AbstractTag
    {
        $tag = null;
        try {
            $tag = new DemoTag($input);
        } catch (TagNotMatchingException $e) {
            $tag = new CloseTag($input);
        } finally {
            return $tag;
        }
    }

    public function getActivity(ActivityIdentifierInterface $identifier): Activity
    {
        // TODO: Implement getActivity() method.
    }

    public function addActivity(ContactIdentifierInterface $contactIdentifier, Activity $activity): ActivityRepositoryInterface
    {
        throw new RuntimeException('Write Operations are not implemented (yet)!');
    }
}