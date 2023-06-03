<?php

declare(strict_types=1);

namespace App\Project\Shared\Application\Service;

use App\Project\Shared\Application\DTO\PaginationItemsDTO;
use App\Project\Shared\Application\DTO\RequestCriteriaDTO;
use App\Project\Shared\Domain\Criteria\Criteria;
use App\Project\Shared\Domain\Service\PageableRepositoryInterface;

final class PaginationBuilder implements PaginationBuilderInterface
{
    public function build(
        PageableRepositoryInterface $repository,
        Criteria $criteria,
        RequestCriteriaDTO $dto
    ): PaginationItemsDTO {
        $criteria->loadScalarFilters($dto->filters)
            ->loadScalarOrders($dto->orders)
            ->loadOffsetAndLimit(...Pagination::getOffsetAndLimit($dto->page));
        $count = $repository->findCountByCriteria($criteria);
        $items = $repository->findAllByCriteria($criteria);

        return PaginationItemsDTO::create(new Pagination($count, $dto->page), $items);
    }
}
