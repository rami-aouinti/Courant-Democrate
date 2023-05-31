<?php

declare(strict_types=1);

namespace App\Article\Transport\AutoMapper\Comment;

use App\General\Transport\AutoMapper\RestRequestMapper;
use App\User\Application\Resource\UserGroupResource;
use App\User\Domain\Entity\UserGroup;
use Throwable;

use function array_map;

/**
 * Class RequestMapper
 *
 * @package App\ApiKey
 */
class RequestMapper extends RestRequestMapper
{
    /**
     * Properties to map to destination object.
     *
     * @var array<int, non-empty-string>
     */
    protected static array $properties = [
        'content',
    ];

    public function __construct(
        private readonly UserGroupResource $userGroupResource,
    ) {
    }

    /**
     * @param array<int, string> $userGroups
     *
     * @return array<int, UserGroup>
     *
     * @throws Throwable
     */
    protected function transformUserGroups(array $userGroups): array
    {
        return array_map(
            fn (string $userGroupUuid): UserGroup => $this->userGroupResource->getReference($userGroupUuid),
            $userGroups,
        );
    }
}
