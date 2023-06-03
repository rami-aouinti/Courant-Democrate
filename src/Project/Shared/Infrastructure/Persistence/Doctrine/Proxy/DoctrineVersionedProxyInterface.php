<?php

declare(strict_types=1);

namespace App\Project\Shared\Infrastructure\Persistence\Doctrine\Proxy;

interface DoctrineVersionedProxyInterface extends DoctrineProxyInterface
{
    public function getVersion(): int;
}
