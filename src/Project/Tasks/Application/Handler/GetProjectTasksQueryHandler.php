<?php

declare(strict_types=1);

namespace App\Project\Tasks\Application\Handler;

use App\Project\Tasks\Application\Query\GetProjectTasksQuery;
use App\Project\Tasks\Application\Query\GetProjectTasksQueryResponse;
use App\Project\Tasks\Domain\Repository\TaskQueryRepositoryInterface;
use App\Project\Shared\Application\Bus\Query\QueryHandlerInterface;
use App\Project\Shared\Application\Bus\Query\QueryResponseInterface;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Application\Service\PaginationBuilder;
use App\Project\Shared\Domain\Criteria\Criteria;
use App\Project\Shared\Domain\Criteria\ExpressionOperand;

final class GetProjectTasksQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly TaskQueryRepositoryInterface $taskRepository,
        private readonly AuthenticatorServiceInterface $authenticatorService,
        private readonly PaginationBuilder $paginationBuilder
    ) {
    }

    /**
     * @return GetProjectTasksQueryResponse
     */
    public function __invoke(GetProjectTasksQuery $query): QueryResponseInterface
    {
        $userId = $this->authenticatorService->getAuthUser()->getId();

        $criteria = new Criteria([
            new ExpressionOperand('projectId', '=', $query->projectId),
            new ExpressionOperand('userId', '=', $userId->value),
        ]);
        $result = $this->paginationBuilder->build($this->taskRepository, $criteria, $query->criteria);

        return new GetProjectTasksQueryResponse($result);
    }
}
