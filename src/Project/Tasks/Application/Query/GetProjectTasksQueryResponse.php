<?php

declare(strict_types=1);

namespace App\Project\Tasks\Application\Query;

use App\Project\Shared\Application\Bus\Query\QueryResponseInterface;
use App\Project\Shared\Application\DTO\PaginationItemsDTO;

final class GetProjectTasksQueryResponse implements QueryResponseInterface
{
    private readonly PaginationItemsDTO $pagination;

    public function __construct(PaginationItemsDTO $pagination)
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): PaginationItemsDTO
    {
        return $this->pagination;
    }
}
