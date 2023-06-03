<?php

declare(strict_types=1);

namespace App\Project\Projects\Infrastructure\Persistence\Doctrine\Proxy;

use App\Project\Shared\Domain\ValueObject\Users\UserId;

final class ProjectParticipantProxyFactory
{
    public function createEntity(ProjectParticipantProxy $proxy): UserId
    {
        $entity = new UserId($proxy->getUserId());

        $proxy->changeEntity($entity);

        return $entity;
    }
}
