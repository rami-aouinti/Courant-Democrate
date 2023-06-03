<?php

declare(strict_types=1);

namespace App\Project\Shared\Infrastructure\Persistence\Doctrine\Proxy;

use App\Project\Shared\Infrastructure\Persistence\Doctrine\PersistentCollectionLoaderInterface;

interface DoctrineProxyInterface
{
    public function refresh(PersistentCollectionLoaderInterface $loader): void;
}
