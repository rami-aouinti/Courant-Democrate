<?php

declare(strict_types=1);

namespace App\Project\Tasks\Infrastructure\Persistence\Doctrine\Proxy;

use App\Project\Tasks\Domain\ValueObject\TaskLink;
use App\Project\Shared\Domain\ValueObject\Tasks\TaskId;

final class TaskLinkProxyFactory
{
    public function createEntity(TaskLinkProxy $proxy): TaskLink
    {
        $entity = new TaskLink(
            new TaskId($proxy->getToTaskId())
        );

        $proxy->changeEntity($entity);

        return $entity;
    }
}
