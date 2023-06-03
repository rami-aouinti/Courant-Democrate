<?php

declare(strict_types=1);

namespace App\Project\Tasks\Infrastructure\Persistence\Doctrine\Proxy;

use App\Project\Tasks\Domain\Collection\TaskCollection;
use App\Project\Tasks\Domain\Entity\TaskManager;
use App\Project\Tasks\Domain\ValueObject\TaskManagerId;
use App\Project\Tasks\Domain\ValueObject\Tasks;
use App\Project\Shared\Domain\Collection\UserIdCollection;
use App\Project\Shared\Domain\ValueObject\DateTime;
use App\Project\Shared\Domain\ValueObject\Owner;
use App\Project\Shared\Domain\ValueObject\Participants;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectId;
use App\Project\Shared\Domain\ValueObject\Projects\ProjectStatus;
use App\Project\Shared\Domain\ValueObject\Users\UserId;

final class TaskManagerProxyFactory
{
    public function __construct(
        private readonly TaskManagerParticipantProxyFactory $participantProxyFactory,
        private readonly TaskProxyFactory $taskProxyFactory
    ) {
    }

    public function createEntity(?TaskManagerProxy $proxy): ?TaskManager
    {
        if (null === $proxy) {
            return null;
        }

        $participants = new UserIdCollection(array_map(function (TaskManagerParticipantProxy $item) {
            return $this->participantProxyFactory->createEntity($item);
        }, $proxy->getParticipants()->toArray()));
        $tasks = new TaskCollection(array_map(function (TaskProxy $item) {
            return $this->taskProxyFactory->createEntity($item);
        }, $proxy->getTasks()->toArray()));

        $entity = new TaskManager(
            new TaskManagerId($proxy->getId()),
            new ProjectId($proxy->getProjectId()),
            ProjectStatus::createFromScalar($proxy->getStatus()),
            new Owner(
                new UserId($proxy->getOwnerId())
            ),
            DateTime::createFromPhpDateTime($proxy->getFinishDate()),
            new Participants($participants),
            new Tasks($tasks)
        );

        $proxy->changeEntity($entity);

        return $entity;
    }
}
