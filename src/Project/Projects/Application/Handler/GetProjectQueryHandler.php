<?php

declare(strict_types=1);

namespace App\Project\Projects\Application\Handler;

use App\Project\Shared\Application\Bus\Query\QueryHandlerInterface;
use App\Project\Shared\Application\Bus\Query\QueryResponseInterface;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Domain\Criteria\Criteria;
use App\Project\Shared\Domain\Criteria\ExpressionOperand;
use App\Project\Shared\Domain\Exception\ProjectNotExistException;
use App\Project\Shared\Domain\Exception\UserIsNotInProjectException;
use App\Project\Projects\Application\Query\GetProjectQuery;
use App\Project\Projects\Application\Query\GetProjectQueryResponse;
use App\Project\Projects\Domain\Repository\ProjectQueryRepositoryInterface;

final class GetProjectQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly ProjectQueryRepositoryInterface $projectRepository,
        private readonly AuthenticatorServiceInterface $authenticatorService
    ) {
    }

    /**
     * @return GetProjectQueryResponse
     */
    public function __invoke(GetProjectQuery $query): QueryResponseInterface
    {
        $userId = $this->authenticatorService->getAuthUser()->getId();
        $count = $this->projectRepository->findCountByCriteria(new Criteria([
            new ExpressionOperand('id', '=', $query->id),
        ]));
        if (0 === $count) {
            throw new ProjectNotExistException($query->id);
        }

        $project = $this->projectRepository->findByCriteria(
            new Criteria([
                new ExpressionOperand('id', '=', $query->id),
                new ExpressionOperand('userId', '=', $userId),
            ])
        );
        if (null === $project) {
            throw new UserIsNotInProjectException($userId, $query->id);
        }

        return new GetProjectQueryResponse($project);
    }
}
