<?php

declare(strict_types=1);

namespace App\Article\Application\Resource;

use App\General\Application\DTO\Interfaces\RestDtoInterface;
use App\General\Application\Rest\RestResource;
use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\Article\Domain\Entity\Post as Entity;
use App\Article\Domain\Repository\Interfaces\PostRepositoryInterface as RepositoryInterface;

/**
 * Class SettingResource
 *
 * @package App\Setting
 *
 * @psalm-suppress LessSpecificImplementedReturnType
 * @codingStandardsIgnoreStart
 *
 * @method Entity getReference(string $id, ?string $entityManagerName = null)
 * @method \App\Article\Infrastructure\Repository\PostRepository getRepository()
 * @method Entity[] find(?array $criteria = null, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?array $search = null, ?string $entityManagerName = null)
 * @method Entity|null findOne(string $id, ?bool $throwExceptionIfNotFound = null, ?string $entityManagerName = null)
 * @method Entity|null findOneBy(array $criteria, ?array $orderBy = null, ?bool $throwExceptionIfNotFound = null, ?string $entityManagerName = null)
 * @method Entity create(RestDtoInterface $dto, ?bool $flush = null, ?bool $skipValidation = null, ?string $entityManagerName = null)
 * @method Entity update(string $id, RestDtoInterface $dto, ?bool $flush = null, ?bool $skipValidation = null, ?string $entityManagerName = null)
 * @method Entity patch(string $id, RestDtoInterface $dto, ?bool $flush = null, ?bool $skipValidation = null, ?string $entityManagerName = null)
 * @method Entity delete(string $id, ?bool $flush = null, ?string $entityManagerName = null)
 * @method Entity save(EntityInterface $entity, ?bool $flush = null, ?bool $skipValidation = null, ?string $entityManagerName = null)
 *
 * @codingStandardsIgnoreEnd
 */
class PostResource extends RestResource
{
    /**
     * @param \App\Article\Infrastructure\Repository\PostRepository $repository
     */
    public function __construct(
        RepositoryInterface $repository,
    ) {
        parent::__construct($repository);
    }
}
