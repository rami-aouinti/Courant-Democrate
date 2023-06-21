<?php

declare(strict_types=1);

namespace App\Project\Projects\Infrastructure\Persistence\Doctrine\Proxy;

use App\Project\Shared\Domain\Collection\UserIdCollection;
use App\Project\Shared\Domain\ValueObject\DateTime;
use App\Project\Shared\Domain\ValueObject\Owner;
use App\Project\Shared\Domain\ValueObject\Participants;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectStatus;
use App\Project\Shared\Domain\ValueObject\Users\UserId;
use App\Project\Projects\Domain\Collection\ProjectTaskCollection;
use App\Project\Projects\Domain\Collection\RequestCollection;
use App\Project\Projects\Domain\Entity\Project;
use App\Project\Projects\Domain\ValueObject\ProjectDescription;
use App\Project\Projects\Domain\ValueObject\ProjectInformation;
use App\Project\Projects\Domain\ValueObject\ProjectName;
use App\Project\Projects\Domain\ValueObject\ProjectTasks;
use App\Project\Projects\Domain\ValueObject\Requests;

final class ProjectProxyFactory
{
    public function __construct(
        private readonly ProjectParticipantProxyFactory $participantProxyFactory,
        private readonly ProjectTaskProxyFactory $taskProxyFactory,
        private readonly RequestProxyFactory $requestProxyFactory,
    ) {
    }

    public function createEntity(?ProjectProxy $proxy): ?Project
    {
        if (null === $proxy) {
            return null;
        }

        $participants = new UserIdCollection(array_map(function (ProjectParticipantProxy $item) {
            return $this->participantProxyFactory->createEntity($item);
        }, $proxy->getParticipants()->toArray()));
        $tasks = new ProjectTaskCollection(array_map(function (ProjectTaskProxy $item) {
            return $this->taskProxyFactory->createEntity($item);
        }, $proxy->getTasks()->toArray()));
        $requests = new RequestCollection(array_map(function (RequestProxy $item) {
            return $this->requestProxyFactory->createEntity($item);
        }, $proxy->getRequests()->toArray()));

        $entity = new Project(
            new ProjectId($proxy->getId()),
            new ProjectInformation(
                new ProjectName($proxy->getName()),
                new ProjectDescription($proxy->getDescription()),
                DateTime::createFromPhpDateTime($proxy->getFinishDate())
            ),
            ProjectStatus::createFromScalar($proxy->getStatus()),
            new Owner(
                $proxy->getOwnerId()
            ),
            new Participants($participants),
            new ProjectTasks($tasks),
            new Requests($requests)
        );

        $proxy->changeEntity($entity);

        return $entity;
    }
}
