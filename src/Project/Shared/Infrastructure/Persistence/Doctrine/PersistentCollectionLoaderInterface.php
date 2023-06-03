<?php

declare(strict_types=1);

namespace App\Project\Shared\Infrastructure\Persistence\Doctrine;

use App\Project\Shared\Domain\Collection\CollectionInterface;
use App\Project\Shared\Infrastructure\Persistence\Doctrine\Proxy\DoctrineProxyInterface;
use Doctrine\Common\Collections\Collection;

interface PersistentCollectionLoaderInterface
{
    public function loadInto(Collection $target, CollectionInterface $source, DoctrineProxyInterface $owner): void;
}
