<?php

declare(strict_types=1);

namespace App\Project\Shared\Application\DTO;

final readonly class RequestCriteriaDTO
{
    public function __construct(
        public array $filters,
        public array $orders,
        public int   $page
    ) {
    }
}
