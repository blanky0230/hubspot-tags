<?php

declare(strict_types=1);

namespace HubspotTags\Domain\ValueObject;

final class ContactMailIdentifier implements ContactIdentifierInterface
{
    /**
     * @var Email
     */
    private $email;

    public function __construct(string $input)
    {
        $this->email = new Email($input);
    }

    public function __toString()
    {
        return $this->email->__toString();
    }
}
