<?php

declare(strict_types=1);

namespace HubspotTags\Domain\ValueObject;

use Throwable;

final class TagNotMatchingException extends \InvalidArgumentException
{
    public function __construct(string $inputText, $code = 0, Throwable $previous = null)
    {
        parent::__construct($inputText.PHP_EOL.'is not a valid Tag!', $code, $previous);
    }
}
