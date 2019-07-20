<?php


namespace HubspotTags\Test;


use HubspotTags\Domain\ValueObject\CloseTag;
use HubspotTags\Domain\ValueObject\TagNotMatchingException;
use PHPUnit\Framework\TestCase;

class CloseTagTest extends TestCase
{

    public function testCanCreateWithValidInput()
    {
        $this->assertInstanceOf(CloseTag::class, new CloseTag('GG EZ CLOSE Fooo/Bar\\^^'));
    }

    public function testThrowsExceptionIfTagNotFoundInInput()
    {
        $this->expectException(TagNotMatchingException::class);
        new CloseTag('DEMO on monday!');
    }


}