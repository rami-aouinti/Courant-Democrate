<?php

declare(strict_types=1);

namespace App\Project\Tasks\Application\Handler;

use App\Project\Tasks\Application\Query\GetTaskQuery;
use App\Project\Tasks\Application\Query\GetTaskQueryResponse;
use App\Project\Tasks\Domain\Repository\TaskQueryRepositoryInterface;
use App\Project\Shared\Application\Bus\Query\QueryHandlerInterface;
use App\Project\Shared\Application\Bus\Query\QueryResponseInterface;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Domain\Criteria\Criteria;
use App\Project\Shared\Domain\Criteria\ExpressionOperand;
use App\Project\Shared\Domain\Exception\TaskNotExistException;
use App\Project\Shared\Domain\Exception\UserIsNotInProjectException;

final class GetTaskQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly TaskQueryRepositoryInterface $taskRepository,
        private readonly AuthenticatorServiceInterface $authenticatorService
    ) {
    }

    /**
     * @return GetTaskQueryResponse
     */
    public function __invoke(GetTaskQuery $query): QueryResponseInterface
    {
        $userId = $this->authenticatorService->getAuthUser()->getId();
        $task = $this->taskRepository->findByCriteria(new Criteria([
            new ExpressionOperand('id', '=', $query->id),
        ]));
        if (null === $task) {
            throw new TaskNotExistException($query->id);
        }

        $userTask = $this->taskRepository->findByCriteria(new Criteria([
            new ExpressionOperand('id', '=', $query->id),
            new ExpressionOperand('userId', '=', $userId->value),
        ]));
        if (null === $userTask) {
            throw new UserIsNotInProjectException($userId->value, $task->projectId);
        }

        return new GetTaskQueryResponse($task);
    }
}
