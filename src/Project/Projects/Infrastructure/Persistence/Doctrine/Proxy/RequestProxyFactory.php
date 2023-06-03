<?php

declare(strict_types=1);

namespace App\Project\Projects\Infrastructure\Persistence\Doctrine\Proxy;

use App\Project\Shared\Domain\ValueObject\DateTime;
use App\Project\Shared\Domain\ValueObject\Requests\RequestStatus;
use App\Project\Shared\Domain\ValueObject\Users\UserId;
use App\Project\Projects\Domain\Entity\Request;
use App\Project\Projects\Domain\ValueObject\RequestId;

final class RequestProxyFactory
{
    public function createEntity(RequestProxy $proxy): Request
    {
        $entity = new Request(
            new RequestId($proxy->getId()),
            new UserId($proxy->getUserId()),
            RequestStatus::createFromScalar($proxy->getStatus()),
            DateTime::createFromPhpDateTime($proxy->getChangeDate())
        );

        $proxy->changeEntity($entity);

        return $entity;
    }
}
