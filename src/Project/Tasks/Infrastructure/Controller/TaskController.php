<?php

declare(strict_types=1);

namespace App\Project\Tasks\Infrastructure\Controller;

use App\Project\Tasks\Application\Command\ActivateTaskCommand;
use App\Project\Tasks\Application\Command\AddLinkCommand;
use App\Project\Tasks\Application\Command\CloseTaskCommand;
use App\Project\Tasks\Application\Command\DeleteLinkCommand;
use App\Project\Tasks\Application\Query\GetProjectTasksQuery;
use App\Project\Tasks\Application\Query\GetProjectTasksQueryResponse;
use App\Project\Tasks\Application\Query\GetTaskLinksQuery;
use App\Project\Tasks\Application\Query\GetTaskLinksQueryResponse;
use App\Project\Tasks\Application\Query\GetTaskQuery;
use App\Project\Tasks\Application\Query\GetTaskQueryResponse;
use App\Project\Tasks\Infrastructure\Symfony\DTO\TaskCreateDTO;
use App\Project\Tasks\Infrastructure\Symfony\DTO\TaskUpdateDTO;
use App\Project\Shared\Application\Bus\Command\CommandBusInterface;
use App\Project\Shared\Application\Bus\Query\QueryBusInterface;
use App\Project\Shared\Application\DTO\RequestCriteriaDTO;
use App\Project\Shared\Application\Service\PaginationResponseFormatterInterface;
use App\Project\Shared\Application\Service\UuidGeneratorInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TaskController
 *
 * @OA\Tag(name="Project")
 *
 * @package App\Project
 */
#[AsController]
#[Route('/v1/tasks', name: 'task.')]
final class TaskController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
        private readonly PaginationResponseFormatterInterface $responseFormatter,
        private readonly UuidGeneratorInterface $uuidGenerator
    ) {
    }

    #[Route('/in-project/{projectId}/', name: 'createInProject', methods: ['POST'])]
    public function createInProject(string $projectId, TaskCreateDTO $dto, Request $request): JsonResponse
    {
        $now = new \DateTime('now');
        $tomorrow = new \DateTime('tomorrow');

        $dto = new TaskCreateDTO('Task 1', 'Brief 1', 'Description 1', $now->format('y-m-d'), $tomorrow->format('y-m-d'));
        $command = $dto->createCommand($this->uuidGenerator->generate(), $projectId);
        $this->commandBus->dispatch($command);

        return new JsonResponse(['id' => $command->id], Response::HTTP_CREATED);
    }

    #[Route(
        '/in-project/{projectId}/create-for-participant/{participantId}/',
        name: 'createForParticipant',
        methods: ['POST']
    )]
    public function createTaskForParticipant(string $projectId, string $participantId, TaskCreateDTO $dto): JsonResponse
    {
        $command = $dto->createCommand($this->uuidGenerator->generate(), $projectId, $participantId);
        $this->commandBus->dispatch($command);

        return new JsonResponse(['id' => $command->id], Response::HTTP_CREATED);
    }

    #[Route('/{id}/activate/', name: 'activate', methods: ['PATCH'])]
    public function activate(string $id): JsonResponse
    {
        $this->commandBus->dispatch(new ActivateTaskCommand($id));

        return new JsonResponse();
    }

    #[Route('/{id}/close/', name: 'close', methods: ['PATCH'])]
    public function close(string $id): JsonResponse
    {
        $this->commandBus->dispatch(new CloseTaskCommand($id));

        return new JsonResponse();
    }

    #[Route('/{id}/', name: 'update', methods: ['PATCH'])]
    public function update(string $id, TaskUpdateDTO $dto): JsonResponse
    {
        $this->commandBus->dispatch($dto->createCommand($id));

        return new JsonResponse();
    }

    #[Route('/{id}/add-link/{toTaskId}/', name: 'addLink', methods: ['PATCH'])]
    public function addLink(string $id, string $toTaskId): JsonResponse
    {
        $this->commandBus->dispatch(new AddLinkCommand($id, $toTaskId));

        return new JsonResponse();
    }

    #[Route('/{id}/delete-link/{toTaskId}/', name: 'deleteLink', methods: ['PATCH'])]
    public function deleteLink(string $id, string $toTaskId): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteLinkCommand($id, $toTaskId));

        return new JsonResponse();
    }

    #[Route('/in-project/{projectId}/', name: 'getAllInProject', methods: ['GET'])]
    public function getAllInProject(string $projectId, RequestCriteriaDTO $dto): JsonResponse
    {
        /** @var GetProjectTasksQueryResponse $envelope */
        $envelope = $this->queryBus->dispatch(new GetProjectTasksQuery($projectId, $dto));

        $response = $this->responseFormatter->format($envelope->getPagination());

        return new JsonResponse($response);
    }

    #[Route('/{id}/links/', name: 'getAllLinksInTask', methods: ['GET'])]
    public function getAllLinksInTask(string $id, RequestCriteriaDTO $dto): JsonResponse
    {
        /** @var GetTaskLinksQueryResponse $envelope */
        $envelope = $this->queryBus->dispatch(new GetTaskLinksQuery($id, $dto));

        $response = $this->responseFormatter->format($envelope->getPagination());

        return new JsonResponse($response);
    }

    #[Route('/{id}/', name: 'get', methods: ['GET'])]
    public function get(string $id): JsonResponse
    {
        /** @var GetTaskQueryResponse $envelope */
        $envelope = $this->queryBus->dispatch(new GetTaskQuery($id));

        return new JsonResponse($envelope->getTask());
    }
}
