<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Interfaces\EntityInterface;
use App\Entity\Traits\Blameable;
use App\Entity\Traits\Timestampable;
use App\Entity\Traits\Uuid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\UuidInterface;
use Stringable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Throwable;

/**
 * Class UserGroup
 *
 * @package App\Entity
 */
#[ORM\Entity]
#[ORM\Table(name: 'user_group')]
class UserGroup implements EntityInterface, Stringable
{
    use Blameable;
    use Timestampable;
    use Uuid;

    public const SET_USER_PROFILE_GROUPS = 'set.UserProfileGroups';
    public const SET_USER_GROUP_BASIC = 'set.UserGroupBasic';

    /**
     * @OA\Property(type="string", format="uuid")
     */
    #[ORM\Id]
    #[ORM\Column(
        name: 'id',
        type: 'uuid_binary_ordered_time',
        unique: true,
    )]
    #[Groups([
        'UserGroup',
        'UserGroup.id',

        'ApiKey.userGroups',
        'User.userGroups',
        'Role.userGroups',

        User::SET_USER_PROFILE,
        self::SET_USER_PROFILE_GROUPS,
        self::SET_USER_GROUP_BASIC,
    ])]
    private UuidInterface $id;

    #[ORM\ManyToOne(
        targetEntity: Role::class,
        inversedBy: 'userGroups',
    )]
    #[ORM\JoinColumn(
        name: 'role',
        referencedColumnName: 'role',
        onDelete: 'CASCADE',
    )]
    #[Groups([
        'UserGroup.role',

        User::SET_USER_PROFILE,
        self::SET_USER_PROFILE_GROUPS,
        self::SET_USER_GROUP_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Valid]
    private Role $role;

    #[ORM\Column(
        name: 'name',
        type: 'string',
        length: 255,
    )]
    #[Groups([
        'UserGroup',
        'UserGroup.name',

        User::SET_USER_PROFILE,
        self::SET_USER_PROFILE_GROUPS,
        self::SET_USER_GROUP_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    private string $name = '';

    /**
     * @var Collection<int, User>|ArrayCollection<int, User>
     */
    #[ORM\ManyToMany(
        targetEntity: 'User',
        mappedBy: 'userGroups',
    )]
    #[ORM\JoinTable(name: 'user_has_user_group')]
    #[Groups([
        'UserGroup.users',
    ])]
    private Collection | ArrayCollection $users;

    /**
     * @var Collection<int, ApiKey>|ArrayCollection<int, ApiKey>
     */
    #[ORM\ManyToMany(
        targetEntity: 'ApiKey',
        mappedBy: 'userGroups',
    )]
    #[ORM\JoinTable(name: 'api_key_has_user_group')]
    #[Groups([
        'UserGroup.apiKeys',
    ])]
    private Collection | ArrayCollection $apiKeys;

    /**
     * Constructor
     *
     * @throws Throwable
     */
    public function __construct()
    {
        $this->id = $this->createUuid();
        $this->users = new ArrayCollection();
        $this->apiKeys = new ArrayCollection();
    }

    public function __toString(): string
    {
        return self::class;
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setRole(Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, User>|ArrayCollection<int, User>
     */
    public function getUsers(): Collection | ArrayCollection
    {
        return $this->users;
    }

    /**
     * @return Collection<int, ApiKey>|ArrayCollection<int, ApiKey>
     */
    public function getApiKeys(): Collection | ArrayCollection
    {
        return $this->apiKeys;
    }

    /**
     * Method to attach new user group to user.
     */
    public function addUser(User $user): self
    {
        $contains = $this->users->contains($user);

        if (!$contains) {
            $this->users->add($user);
            $user->addUserGroup($this);
        }

        return $this;
    }

    /**
     * Method to remove specified user from user group.
     */
    public function removeUser(User $user): self
    {
        $removed = $this->users->removeElement($user);

        if ($removed) {
            $user->removeUserGroup($this);
        }

        return $this;
    }

    /**
     * Method to remove all many-to-many user relations from current user group.
     */
    public function clearUsers(): self
    {
        $this->users->clear();

        return $this;
    }

    /**
     * Method to attach new user group to user.
     */
    public function addApiKey(ApiKey $apiKey): self
    {
        $contains = $this->apiKeys->contains($apiKey);

        if (!$contains) {
            $this->apiKeys->add($apiKey);
            $apiKey->addUserGroup($this);
        }

        return $this;
    }

    /**
     * Method to remove specified user from user group.
     */
    public function removeApiKey(ApiKey $apiKey): self
    {
        $removed = $this->apiKeys->removeElement($apiKey);

        if ($removed) {
            $apiKey->removeUserGroup($this);
        }

        return $this;
    }

    /**
     * Method to remove all many-to-many api key relations from current user group.
     */
    public function clearApiKeys(): self
    {
        $this->apiKeys->clear();

        return $this;
    }
}
