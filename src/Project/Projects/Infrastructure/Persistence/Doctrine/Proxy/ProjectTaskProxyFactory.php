<?php

declare(strict_types=1);

namespace App\Project\Projects\Infrastructure\Persistence\Doctrine\Proxy;

use App\Project\Shared\Domain\ValueObject\Tasks\TaskId;
use App\Project\Shared\Domain\ValueObject\Users\UserId;
use App\Project\Projects\Domain\Entity\ProjectTask;
use App\Project\Projects\Domain\ValueObject\ProjectTaskId;

final class ProjectTaskProxyFactory
{
    public function createEntity(ProjectTaskProxy $proxy): ProjectTask
    {
        $entity = new ProjectTask(
            new ProjectTaskId($proxy->getId()),
            new TaskId($proxy->getTaskId()),
            new UserId($proxy->getOwnerId()),
        );

        $proxy->changeEntity($entity);

        return $entity;
    }
}
