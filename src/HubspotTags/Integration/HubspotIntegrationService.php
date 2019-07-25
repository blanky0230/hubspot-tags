<?php

declare(strict_types=1);

namespace HubspotTags\Integration;

use DateTimeImmutable;
use Exception;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
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
use HubspotTags\Domain\ValueObject\ContactMailIdentifier;
use HubspotTags\Domain\ValueObject\DemoTag;
use HubspotTags\Domain\ValueObject\TagNotMatchingException;

/**
 * Class HubspotIntegrationService.
 */
final class HubspotIntegrationService implements ContactRepositoryInterface, ActivityRepositoryInterface
{
    /**
     * @var string
     */
    private $hubsSpotApiKey;

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var InMemoryContactRepository
     */
    private $contactCache;

    /**
     * @var InMemoryActivityRepository
     */
    private $activityCache;

    /**
     * @var bool
     */
    private $lazy;

    /**
     * HubspotIntegrationService constructor.
     *
     * @param string $hubSpotApiKey
     * @param bool   $lazy          if set to false, will try to pre-populate data upon creation.
     *                              You may get outdated results if set to true since we'd only fetch fresh data if we've never seen it.
     *                              We could extend upon this mechanism to get som "validity timescale" but that's beyond current scope ;)
     *
     * @throws Exception
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

    /**
     * @return array
     */
    public function getAllContacts(): array
    {
        return $this->contactCache->getAllContacts();
    }

    /**
     * @param ContactIdentifierInterface $identifier
     *
     * @return Contact|null
     */
    public function getSingleContact(ContactIdentifierInterface $identifier): ?Contact
    {
        if (null === $this->contactCache->getSingleContact($identifier)) {
            $this->onContactCacheMiss($identifier);
        }

        return $this->contactCache->getSingleContact($identifier);
    }

    /**
     * @param Contact $contact
     *
     * @return ContactRepositoryInterface
     */
    public function addContact(Contact $contact): ContactRepositoryInterface
    {
        throw new RuntimeException('Write Operations are not implemented (yet)!');
    }

    /**
     * @param ContactIdentifierInterface $identifier
     *
     * @return array
     *
     * @throws RuntimeException
     * @throws Exception
     */
    public function getContactActivities(ContactIdentifierInterface $identifier): array
    {
        if (null === $this->contactCache->getSingleContact($identifier)) {
            $this->onContactCacheMiss($identifier);
        }

        $activities = $this->activityCache->getContactActivities($identifier);

        if (!count($activities)) {
            $this->onContactActivityCacheMiss($identifier);
        }

        return $this->activityCache->getContactActivities($identifier);
    }

    /**
     * @return array
     */
    public function getAllActivities(): array
    {
        return $this->activityCache->getAllActivities();
    }

    /**
     * @param ActivityIdentifierInterface $identifier
     *
     * @return Activity|null
     */
    public function getActivity(ActivityIdentifierInterface $identifier): ?Activity
    {
        if (null === $this->activityCache->getActivity($identifier)) {
            return null;
        }

        return $this->activityCache->getActivity($identifier);
    }

    /**
     * @param ContactIdentifierInterface $contactIdentifier
     * @param Activity                   $activity
     *
     * @return ActivityRepositoryInterface
     */
    public function addActivity(ContactIdentifierInterface $contactIdentifier, Activity $activity): ActivityRepositoryInterface
    {
        throw new RuntimeException('Write Operations are not implemented (yet)!');
    }

    /**
     * @param ContactIdentifierInterface $identifier
     */
    private function onContactCacheMiss(ContactIdentifierInterface $identifier): void
    {
        $contact = $this->retrieveContactByEmail($identifier);
        if (null !== $contact) {
            $this->contactCache->addContact($contact);
        }
    }

    /**
     * @param ContactIdentifierInterface $identifier
     *
     * @throws RuntimeException,
     * @throws Exception
     */
    private function onContactActivityCacheMiss(ContactIdentifierInterface $identifier): void
    {
        $this->failOnNonMailContactId($identifier);
        $contact = $this->getSingleContact($identifier);

        if (null === $contact || null === $contact->getVritualId()) {
            throw new RuntimeException("${identifier} can not be resolved to a Contact!");
        }

        $rawEngagements = $this->retrieveContactEngagements($contact->getVritualId());

        if (null == $rawEngagements) {
            return;
        }

        foreach ($rawEngagements as $engagement) {
            $tag = $this->getTagForEngagementData($engagement);
            if (null !== $tag) {
                $actualTimestampBecauseHubspotIsWeird = intval($engagement['engagement']['createdAt'] / 1000);
                $this->activityCache->addActivity($identifier, new Activity(
                    new ActivityBasicId($engagement['engagement']['id']),
                    new DateTimeImmutable("@{$actualTimestampBecauseHubspotIsWeird}"),
                    $tag
                    ));
            }
        }
    }

    /**
     * Can be a bit heavy obviously. Depending on the raw number of contacts/engagements are in use.
     *
     * @throws RuntimeException
     * @throws Exception
     */
    private function populateCache()
    {
        //Populate the ContactCache
        $urlTemplate = 'https://api.hubapi.com/contacts/v1/lists/all/contacts/all?'.http_build_query(
            [
                'hapikey' => $this->hubsSpotApiKey,
                'propertyMode' => 'value_only',
                'property' => 'identity-profiles',
                'count' => 100,
            ]);

        $response = $this->httpClient->get($urlTemplate);
        $data = json_decode($response->getBody()->getContents(), true);
        if (200 === $response->getStatusCode() && !empty($data)) {
            foreach ($data['contacts'] as $contact) {
                //Seriously though? THIS is a very "INTERESTING" API!
                $identityProfiles = $contact['identity-profiles'];
                foreach ($identityProfiles as $idP) {
                    foreach ($idP['identities'] as $id) {
                        if ('EMAIL' === $id['type']) {
                            $this->onContactActivityCacheMiss(new ContactMailIdentifier($id['value']));
                        }
                    }
                }
            }
        }
    }

    /**
     * @param ContactIdentifierInterface $email
     *
     * @return Contact|null
     */
    public function retrieveContactByEmail(ContactIdentifierInterface $email): ?Contact
    {
        $this->failOnNonMailContactId($email);
        $urlTemplate = "https://api.hubapi.com/contacts/v1/contact/email/{$email}/profile?".http_build_query(
            [
                'hapikey' => $this->hubsSpotApiKey,
                'showListMemberships' => false,
                'formSubmissionMode' => false,
            ]);
        try {
            $response = $this->httpClient->get($urlTemplate);
            $data = json_decode($response->getBody()->getContents(), true);
            if (200 === $response->getStatusCode() && !empty($data)) {
                return new Contact($email, new ContactBasicId(intval($data['canonical-vid'])));
            }
        } catch (ClientException $httpException) {
            $response = $httpException->getResponse();
            /** @var ResponseInterface $response */
            if ($response) {
                if (404 === $response->getStatusCode()) {
                    throw new RuntimeException("There is no Contact '{$email}' registered with your HubSpot!");
                }
                if (500 <= $response->getStatusCode()) {
                    throw new RuntimeException("API ERROR: \n {$response->getBody()->getContents()}");
                }
            } else {
                throw $httpException;
            }
        }

        return null;
    }

    /**
     * @param ContactIdentifierInterface $identifier
     *
     * @return array|null
     */
    public function retrieveContactEngagements(ContactIdentifierInterface $identifier): ?array
    {
        $urlTemplate = "https://api.hubapi.com/engagements/v1/engagements/associated/contact/{$identifier}?".http_build_query(
            [
                'hapikey' => $this->hubsSpotApiKey,
                'limit' => 100,
            ]);

        $response = $this->httpClient->get($urlTemplate);
        $data = json_decode($response->getBody()->getContents(), true);
        if (200 === $response->getStatusCode() && !empty($data)) {
            return $data['results'];
        }

        return null;
    }

    /**
     * @param ContactIdentifierInterface $identifier
     */
    private function failOnNonMailContactId(ContactIdentifierInterface $identifier): void
    {
        if (ContactMailIdentifier::class !== get_class($identifier)) {
            throw new RuntimeException(
                'Hubspot-Integration only works for contacts using Email as identifiers!');
        }
    }

    /**
     * @param array $engagement
     *
     * @return AbstractTag|null
     */
    private function getTagForEngagementData(array $engagement): ?AbstractTag
    {
        if (!array_key_exists('metadata', $engagement) ||
            !array_key_exists('body', $engagement['metadata'])) {
            return null;
        }
        $stringData = $engagement['metadata']['body'];
        $tag = $this->createCloseTagIfInString($stringData);
        if ($tag) {
            return $tag;
        }
        $tag = $this->createDemoTagIfInString($stringData);

        return $tag;
    }

    /**
     * @param string $input
     *
     * @return CloseTag|null
     */
    private function createCloseTagIfInString(string $input): ?CloseTag
    {
        try {
            return new CloseTag($input);
        } catch (TagNotMatchingException $e) {
            return null;
        }
    }

    /**
     * @param string $input
     *
     * @return DemoTag|null
     */
    private function createDemoTagIfInString(string  $input): ?DemoTag
    {
        try {
            return new DemoTag($input);
        } catch (TagNotMatchingException $e) {
            return null;
        }
    }
}
