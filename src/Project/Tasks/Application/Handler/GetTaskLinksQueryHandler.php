<?php

declare(strict_types=1);

namespace App\Project\Tasks\Application\Handler;

use App\Project\Tasks\Application\Query\GetTaskLinksQuery;
use App\Project\Tasks\Application\Query\GetTaskLinksQueryResponse;
use App\Project\Tasks\Domain\Repository\TaskLinkQueryRepositoryInterface;
use App\Project\Shared\Application\Bus\Query\QueryHandlerInterface;
use App\Project\Shared\Application\Bus\Query\QueryResponseInterface;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Application\Service\PaginationBuilder;
use App\Project\Shared\Domain\Criteria\Criteria;
use App\Project\Shared\Domain\Criteria\ExpressionOperand;

final class GetTaskLinksQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly TaskLinkQueryRepositoryInterface $repository,
        private readonly AuthenticatorServiceInterface $authenticatorService,
        private readonly PaginationBuilder $paginationBuilder
    ) {
    }

    /**
     * @return GetTaskLinksQueryResponse
     */
    public function __invoke(GetTaskLinksQuery $query): QueryResponseInterface
    {
        $userId = $this->authenticatorService->getAuthUser()->getId();

        $criteria = new Criteria([
            new ExpressionOperand('ownerTaskId', '=', $query->id),
            new ExpressionOperand('userId', '=', $userId->value),
        ]);
        $result = $this->paginationBuilder->build($this->repository, $criteria, $query->criteria);

        return new GetTaskLinksQueryResponse($result);
    }
}
