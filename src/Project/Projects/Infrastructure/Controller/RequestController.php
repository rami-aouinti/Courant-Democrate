<?php

declare(strict_types=1);

namespace App\Project\Projects\Infrastructure\Controller;

use App\Project\Shared\Application\Bus\Command\CommandBusInterface;
use App\Project\Projects\Application\Command\ConfirmRequestCommand;
use App\Project\Projects\Application\Command\RejectRequestCommand;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RequestController
 *
 * @OA\Tag(name="Project")
 *
 * @package App\Project
 */
#[AsController]
#[Route('/requests', name: 'request.')]
final class RequestController
{
    public function __construct(
        private CommandBusInterface $commandBus
    ) {
    }

    #[Route('/{id}/confirm/', name: 'confirm', methods: ['PATCH'])]
    public function confirm(string $id): JsonResponse
    {
        $this->commandBus->dispatch(new ConfirmRequestCommand($id));

        return new JsonResponse();
    }

    #[Route('/{id}/reject/', name: 'reject', methods: ['PATCH'])]
    public function reject(string $id): JsonResponse
    {
        $this->commandBus->dispatch(new RejectRequestCommand($id));

        return new JsonResponse();
    }
}
