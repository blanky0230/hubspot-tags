<?php


namespace HubspotTags\Domain\ValueObject;


final class CloseTag extends AbstractTag
{

    protected function isValid(): bool
    {
        return (bool) preg_match('/CLOSE/', $this->inputText);
    }
}