<?php

declare(strict_types=1);

namespace App\User\Application\Security;

use App\ApiKey\Application\Security\ApiKeyUser;
use App\ApiKey\Application\Security\Provider\ApiKeyUserProvider;
use App\ApiKey\Domain\Entity\ApiKey;
use App\Project\Shared\Application\Service\AuthenticatorServiceInterface;
use App\Project\Shared\Infrastructure\Service\ValueObject\AuthUser;
use App\Project\Shared\Infrastructure\Service\ValueObject\SymfonySecurityUser;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\Interfaces\UserRepositoryInterface;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserTypeIdentification
 *
 * @package App\User
 */
class UserTypeIdentification implements AuthenticatorServiceInterface
{
    /**
     * @param \App\User\Infrastructure\Repository\UserRepository $userRepository
     */
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly UserRepositoryInterface $userRepository,
        private readonly ApiKeyUserProvider $apiKeyUserProvider,
        private readonly JWTTokenManagerInterface $tokenManager,
    ) {
    }

    /**
     * Helper method to get current logged in ApiKey entity via token storage.
     */
    public function getApiKey(): ?ApiKey
    {
        $apiKeyUser = $this->getApiKeyUser();

        return $apiKeyUser === null
            ? null
            : $this->apiKeyUserProvider->getApiKeyForToken($apiKeyUser->getUserIdentifier());
    }

    /**
     * Helper method to get current logged in User entity via token storage.
     *
     * @throws NonUniqueResultException
     */
    public function getUser(): ?User
    {
        $user = $this->getSecurityUser();

        return $user === null ? null : $this->userRepository->loadUserByIdentifier($user->getUserIdentifier(), true);
    }

    public function getAuthUser(): ?User
    {
        $user = $this->getSecurityUser();
        return $user === null ? null : $this->userRepository->loadUserByIdentifier($user->getUserIdentifier(), true);
    }

    public function getToken(string $id): string
    {
        return $this->tokenManager->create(new SymfonySecurityUser($id));
    }

    /**
     * Helper method to get user identity object via token storage.
     */
    public function getIdentity(): ?UserInterface
    {
        return $this->getSecurityUser() ?? $this->getApiKeyUser();
    }

    /**
     * Helper method to get current logged in ApiKeyUser via token storage.
     */
    public function getApiKeyUser(): ?ApiKeyUser
    {
        $apiKeyUser = $this->getUserToken();

        return $apiKeyUser instanceof ApiKeyUser ? $apiKeyUser : null;
    }

    /**
     * Helper method to get current logged in SecurityUser via token storage.
     */
    public function getSecurityUser(): ?SecurityUser
    {
        $securityUser = $this->getUserToken();

        return $securityUser instanceof SecurityUser ? $securityUser : null;
    }

    /**
     * Returns a user representation. Can be a UserInterface instance, an object
     * implementing a __toString method, or the username as a regular string.
     */
    private function getUserToken(): UserInterface | null
    {
        $token = $this->tokenStorage->getToken();

        return $token?->getUser();
    }
}
