<?php

declare(strict_types=1);

namespace App\Project\Projects\Infrastructure\Controller;

use App\Project\Shared\Application\Bus\Command\CommandBusInterface;
use App\Project\Shared\Application\Bus\Query\QueryBusInterface;
use App\Project\Shared\Application\DTO\RequestCriteriaDTO;
use App\Project\Shared\Application\Service\PaginationResponseFormatterInterface;
use App\Project\Shared\Application\Service\UuidGeneratorInterface;
use App\Project\Projects\Application\Command\ActivateProjectCommand;
use App\Project\Projects\Application\Command\ChangeProjectOwnerCommand;
use App\Project\Projects\Application\Command\CloseProjectCommand;
use App\Project\Projects\Application\Command\CreateRequestToProjectCommand;
use App\Project\Projects\Application\Command\LeaveProjectCommand;
use App\Project\Projects\Application\Command\RemoveProjectParticipantCommand;
use App\Project\Projects\Application\Query\GetAllOwnProjectsQuery;
use App\Project\Projects\Application\Query\GetAllOwnProjectsQueryResponse;
use App\Project\Projects\Application\Query\GetProjectQuery;
use App\Project\Projects\Application\Query\GetProjectQueryResponse;
use App\Project\Projects\Application\Query\GetProjectRequestsQueryResponse;
use App\Project\Projects\Application\Query\GetProjectsRequestsQuery;
use App\Project\Projects\Infrastructure\Symfony\DTO\ProjectCreateDTO;
use App\Project\Projects\Infrastructure\Symfony\DTO\ProjectUpdateDTO;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProjectController
 *
 * @OA\Tag(name="Project")
 *
 * @package App\Project
 */
#[AsController]
#[Route('/v1/projects', name: 'project.')]
final readonly class ProjectController
{
    public function __construct(
        private CommandBusInterface                  $commandBus,
        private QueryBusInterface                    $queryBus,
        private PaginationResponseFormatterInterface $responseFormatter,
        private UuidGeneratorInterface               $uuidGenerator
    ) {
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(ProjectCreateDTO $dto): JsonResponse
    {
        $now = new \DateTime('now');

        $dto = new ProjectCreateDTO('test', 'testtt', $now->format('Y-m-d'));
        $command = $dto->createCommand($this->uuidGenerator->generate());
        $this->commandBus->dispatch($command);

        return new JsonResponse(['id' => $command->id], Response::HTTP_CREATED);
    }

    #[Route('/{id}/activate/', name: 'activate', methods: ['PATCH'])]
    public function activate(string $id): JsonResponse
    {
        $this->commandBus->dispatch(new ActivateProjectCommand($id));

        return new JsonResponse();
    }

    #[Route('/{id}/close/', name: 'close', methods: ['PATCH'])]
    public function close(string $id): JsonResponse
    {
        $this->commandBus->dispatch(new CloseProjectCommand($id));

        return new JsonResponse();
    }

    #[Route('/{id}/', name: 'update', methods: ['PATCH'])]
    public function update(string $id, ProjectUpdateDTO $dto): JsonResponse
    {
        $this->commandBus->dispatch($dto->createCommand($id));

        return new JsonResponse();
    }

    #[Route('/{id}/change-owner/{ownerId}/', name: 'changeOwner', methods: ['PATCH'])]
    public function changeOwner(string $id, string $ownerId): JsonResponse
    {
        $this->commandBus->dispatch(new ChangeProjectOwnerCommand($id, $ownerId));

        return new JsonResponse();
    }

    #[Route('/{id}/leave/', name: 'leave', methods: ['PATCH'])]
    public function leave(string $id): JsonResponse
    {
        $this->commandBus->dispatch(new LeaveProjectCommand($id));

        return new JsonResponse();
    }

    #[Route('/{id}/remove-participant/{participantId}/', name: 'removeParticipant', methods: ['PATCH'])]
    public function removeParticipant(string $id, string $participantId): JsonResponse
    {
        $this->commandBus->dispatch(new RemoveProjectParticipantCommand($id, $participantId));

        return new JsonResponse();
    }

    #[Route('/{id}/requests/', name: 'createRequest', methods: ['POST'])]
    public function createRequest(string $id): JsonResponse
    {
        $command = new CreateRequestToProjectCommand(
            $this->uuidGenerator->generate(),
            $id
        );
        $this->commandBus->dispatch($command);

        return new JsonResponse(['id' => $command->id], Response::HTTP_CREATED);
    }

    #[Route('/', name: 'getAll', methods: ['GET'])]
    public function getAll(RequestCriteriaDTO $dto): JsonResponse
    {
        /** @var GetAllOwnProjectsQueryResponse $envelope */
        $envelope = $this->queryBus->dispatch(new GetAllOwnProjectsQuery($dto));

        $response = $this->responseFormatter->format($envelope->getPagination());

        return new JsonResponse($response);
    }

    #[Route('/{id}/', name: 'get', methods: ['GET'])]
    public function get(string $id): JsonResponse
    {
        /** @var GetProjectQueryResponse $envelope */
        $envelope = $this->queryBus->dispatch(new GetProjectQuery($id));

        return new JsonResponse($envelope->getProject());
    }

    #[Route('/{id}/requests/', name: 'getAllRequests', methods: ['GET'])]
    public function getAllRequests(string $id, RequestCriteriaDTO $dto): JsonResponse
    {
        /** @var GetProjectRequestsQueryResponse $envelope */
        $envelope = $this->queryBus->dispatch(new GetProjectsRequestsQuery($id, $dto));

        $response = $this->responseFormatter->format($envelope->getPagination());

        return new JsonResponse($response);
    }
}
