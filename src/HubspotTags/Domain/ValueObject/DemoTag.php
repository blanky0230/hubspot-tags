<?php


namespace HubspotTags\Domain\ValueObject;


final class DemoTag extends AbstractTag
{
    /**
     * @return bool
     * TODO Could be sped up by only looking at the last line of the text. (Needs to be a ValidUseCaseThough!)
     */
    protected function isValid(): bool
    {
        return (bool) preg_match('/DEMO/', $this->inputText);
    }
}