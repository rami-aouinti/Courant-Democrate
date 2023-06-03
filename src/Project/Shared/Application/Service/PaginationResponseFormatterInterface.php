<?php

declare(strict_types=1);

namespace App\Project\Shared\Application\Service;

use App\Project\Shared\Application\DTO\PaginationItemsDTO;

interface PaginationResponseFormatterInterface
{
    public function format(PaginationItemsDTO $pagination): array;
}
