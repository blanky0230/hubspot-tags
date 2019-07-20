<?php

declare(strict_types=1);

namespace HubspotTags\Domain\ValueObject;

use InvalidArgumentException;

final class Email
{
    /**
     * @var string
     */
    private $data;

    public function __construct(string $input)
    {
        if (!$this->isValid($input)) {
            throw new InvalidArgumentException($input.' is not a valid email address!');
        }

        $this->data = $input;
    }

    private function isValid(string $input): bool
    {
        return false !== filter_var($input, FILTER_VALIDATE_EMAIL);
    }

    public function __toString()
    {
        return $this->data;
    }
}
