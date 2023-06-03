<?php

declare(strict_types=1);

namespace App\Project\Projects\Application\Handler;

use App\Project\Shared\Application\Bus\Query\QueryHandlerInterface;
use App\Project\Shared\Application\Bus\Query\QueryResponseInterface;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Application\Service\PaginationBuilder;
use App\Project\Shared\Domain\Criteria\Criteria;
use App\Project\Shared\Domain\Criteria\ExpressionOperand;
use App\Project\Projects\Application\Query\GetProjectRequestsQueryResponse;
use App\Project\Projects\Application\Query\GetProjectsRequestsQuery;
use App\Project\Projects\Domain\Repository\RequestQueryRepositoryInterface;

final class GetProjectRequestsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly RequestQueryRepositoryInterface $repository,
        private readonly AuthenticatorServiceInterface $authenticatorService,
        private readonly PaginationBuilder $paginationBuilder
    ) {
    }

    /**
     * @return GetProjectRequestsQueryResponse
     */
    public function __invoke(GetProjectsRequestsQuery $query): QueryResponseInterface
    {
        $userId = $this->authenticatorService->getAuthUser()->getId();

        $criteria = new Criteria([
            new ExpressionOperand('projectId', '=', $query->projectId),
            new ExpressionOperand('projectOwnerId', '=', $userId->value),
        ]);

        $result = $this->paginationBuilder->build($this->repository, $criteria, $query->criteria);

        return new GetProjectRequestsQueryResponse($result);
    }
}
