<?php


namespace HubspotTags\Domain\ValueObject;


abstract class AbstractTag
{
    /**
     * @var string $inputText
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