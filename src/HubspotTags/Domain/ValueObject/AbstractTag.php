<?php

declare(strict_types=1);

namespace HubspotTags\Domain\ValueObject;

abstract class AbstractTag
{
    /**
     * @var string
     */
    protected $inputText;

    public function __construct(string $inputText)
    {
        $this->inputText = $inputText;
        if (!$this->isValid()) {
            throw new TagNotMatchingException($inputText);
        }
    }

    abstract protected function isValid(): bool;
}
