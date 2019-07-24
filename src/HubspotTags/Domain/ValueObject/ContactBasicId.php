<?php

declare(strict_types=1);

namespace HubspotTags\Domain\ValueObject;

final class ContactBasicId implements ContactIdentifierInterface
{
    /**
     * @var int
     */
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function __toString()
    {
        return strval($this->id);
    }
}
