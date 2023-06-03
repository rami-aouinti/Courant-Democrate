<?php

declare(strict_types=1);

namespace App\Project\Shared\Application\Service;

use App\Project\Shared\Application\DTO\PaginationItemsDTO;
use App\Project\Shared\Application\DTO\RequestCriteriaDTO;
use App\Project\Shared\Domain\Criteria\Criteria;
use App\Project\Shared\Domain\Service\PageableRepositoryInterface;

interface PaginationBuilderInterface
{
    public function build(
        PageableRepositoryInterface $repository,
        Criteria $criteria,
        RequestCriteriaDTO $dto
    ): PaginationItemsDTO;
}
