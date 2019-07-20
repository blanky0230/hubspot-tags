<?php

namespace HubspotTags\Integration;

use GuzzleHttp\Client;
use HubspotTags\Domain\Activity;
use HubspotTags\Domain\ActivityRepositoryInterface;
use HubspotTags\Domain\Contact;
use HubspotTags\Domain\ContactRepositoryInterface;
use HubspotTags\Domain\ValueObject\ActivityIdentifierInterface;
use HubspotTags\Domain\ValueObject\ContactIdentifierInterface;
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
        // TODO: Implement getAllActivities() method.
    }

    public function getActivity(ActivityIdentifierInterface $identifier): Activity
    {
        // TODO: Implement getActivity() method.
    }

    public function addActivity(ContactIdentifierInterface $contactIdentifier, Activity $activity): ActivityRepositoryInterface
    {
        throw new \RuntimeException('Write Operations are not implemented (yet)!');
    }
}