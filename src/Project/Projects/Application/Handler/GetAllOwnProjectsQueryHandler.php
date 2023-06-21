<?php

declare(strict_types=1);

namespace App\Project\Projects\Application\Handler;

use App\Project\Shared\Application\Bus\Query\QueryHandlerInterface;
use App\Project\Shared\Application\Bus\Query\QueryResponseInterface;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Application\Service\PaginationBuilder;
use App\Project\Shared\Domain\Criteria\Criteria;
use App\Project\Shared\Domain\Criteria\ExpressionOperand;
use App\Project\Projects\Application\Query\GetAllOwnProjectsQuery;
use App\Project\Projects\Application\Query\GetAllOwnProjectsQueryResponse;
use App\Project\Projects\Domain\Repository\ProjectQueryRepositoryInterface;

final readonly class GetAllOwnProjectsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProjectQueryRepositoryInterface $projectRepository,
        private AuthenticatorServiceInterface   $authenticatorService,
        private PaginationBuilder               $paginationBuilder
    ) {
    }

    /**
     * @return GetAllOwnProjectsQueryResponse
     */
    public function __invoke(GetAllOwnProjectsQuery $query): QueryResponseInterface
    {
        $userId = $this->authenticatorService->getAuthUser()->getId();

        $criteria = new Criteria([
            new ExpressionOperand('userId', '=', $userId),
        ]);

        $result = $this->paginationBuilder->build($this->projectRepository, $criteria, $query->criteria);

        return new GetAllOwnProjectsQueryResponse($result);
    }
}
