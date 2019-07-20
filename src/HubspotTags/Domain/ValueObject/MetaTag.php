<?php

declare(strict_types=1);

namespace HubspotTags\Domain\ValueObject;

final class MetaTag extends AbstractTag
{
    /**
     * @var string
     */
    private $stringTag;

    public function __construct(string $inputText, string $stringTag)
    {
        $this->stringTag = $stringTag;
        parent::__construct($inputText);
    }

    protected function isValid(): bool
    {
        return (bool) preg_match("/{$this->stringTag}/", $this->inputText);
    }
}
