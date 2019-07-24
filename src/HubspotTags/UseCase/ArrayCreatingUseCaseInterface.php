<?php

declare(strict_types=1);

namespace App\HubspotTags\UseCase;

use HubspotTags\UseCase\UseCaseInterface;

interface ArrayCreatingUseCaseInterface extends UseCaseInterface
{
    public function execute(): array;
}
