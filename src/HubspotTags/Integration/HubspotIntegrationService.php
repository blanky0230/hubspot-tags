<?php

declare(strict_types=1);

namespace HubspotTags\Integration;

use DateTimeImmutable;
use GuzzleHttp\Client;
use HubspotTags\Domain\Activity;
use HubspotTags\Domain\ActivityRepositoryInterface;
use HubspotTags\Domain\Contact;
use HubspotTags\Domain\ContactRepositoryInterface;
use HubspotTags\Domain\InMemoryActivityRepository;
use HubspotTags\Domain\InMemoryContactRepository;
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

    private $contactCache;

    private $activityCache;

    private $lazy;

    /**
     * HubspotIntegrationService constructor.
     *
     * @param string $hubSpotApiKey
     * @param bool   $lazy          if set to false, will try to pre-populate data upon creation.
     *                              You may get outdated results if set to true since we'd only fetch fresh data if we've never seen it.
     *                              We could extend upon this mechanism to get som "validity timescale" but that's beyond current scope ;)
     */
    public function __construct(string $hubSpotApiKey, bool $lazy = false)
    {
        $this->hubsSpotApiKey = $hubSpotApiKey;
        $this->lazy = $lazy;
        $this->httpClient = new Client();
        $this->contactCache = new InMemoryContactRepository();
        $this->activityCache = new InMemoryActivityRepository($this->contactCache);
        if (!$lazy) {
            $this->populateCache();
        }
    }

    public function getAllContacts(): array
    {
        return $this->contactCache->getAllContacts();
    }

    public function getSingleContact(ContactIdentifierInterface $identifier): ?Contact
    {
        if (null === $this->contactCache->getSingleContact($identifier)) {
            $this->onContactCacheMiss($identifier);
        }

        return $this->contactCache->getSingleContact($identifier);
    }

    public function addContact(Contact $contact): ContactRepositoryInterface
    {
        throw new RuntimeException('Write Operations are not implemented (yet)!');
    }

    public function getContactActivities(ContactIdentifierInterface $identifier): array
    {
        if (null === $this->contactCache->getSingleContact($identifier)) {
            $this->onContactCacheMiss($identifier);
        }

        return $this->activityCache->getContactActivities($identifier);
    }

    public function getAllActivities(): array
    {
        return $this->activityCache->getAllActivities();
    }

    /**
     * @param string $input
     *
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

    public function getActivity(ActivityIdentifierInterface $identifier): ?Activity
    {
        if (null === $this->activityCache->getActivity($identifier)) {
            $this->onActivityCacheMiss($identifier);
        }

        return $this->activityCache->getActivity($identifier);
    }

    public function addActivity(ContactIdentifierInterface $contactIdentifier, Activity $activity): ActivityRepositoryInterface
    {
        throw new RuntimeException('Write Operations are not implemented (yet)!');
    }

    private function onContactCacheMiss(ContactIdentifierInterface $identifier)
    {
    }

    private function onActivityCacheMiss(ActivityIdentifierInterface $identifier)
    {
    }

    private function populateCache()
    {
    }

    //        $data = $this->httpClient->get(
//            'https://api.hubapi.com/engagements/v1/engagements/paged?'.http_build_query(
//                ['hapikey' => $this->hubsSpotApiKey]));
//        $result = json_decode($data->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
//        if ($result['hasMore']) {
//            //TODO
//        }
//        $resultData = [];
//        foreach ($result['results'] as $inputData) {
//            $activityId = new ActivityBasicId($inputData['engagement']['id']);
////            $contactIdentifier = new ContactBasicId($inputData['associations']['contactIds'][0]);
////            $actualContact = new Contact($contactIdentifier);
//
////            if ($this->getSingleContact($contactIdentifier)) {
////                $actualContact = $this->getSingleContact($actualContact);
////            }
//            $date = DateTimeImmutable::createFromFormat('U', (int) ($inputData['engagement']['createdAt'] / 1000));
//            $tag = $this->createTagFromInputString($inputData['metadata']['body']);
//            if ($tag) {
//                $activity  = new Activity($activityId, $date, $tag);
////                $actualContact->addActivity($activity);
//                $resultData[] = $activity;
//            }
//        }
//        return $resultData;
}
