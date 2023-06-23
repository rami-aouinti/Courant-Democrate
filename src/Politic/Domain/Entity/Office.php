<?php

namespace App\Politic\Domain\Entity;

use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\Article\Domain\Entity\Traits\Blameable;
use App\Pdf\Domain\Entity\Document;
use App\Politic\Infrastructure\Repository\OfficeRepository;
use App\User\Domain\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: OfficeRepository::class)]
#[ORM\Table(name: 'office')]
class Office implements EntityInterface
{
    final public const SET_USER_OFFICE = 'set.UserOffice';
    final public const SET_USER_BASIC = 'set.UserBasic';


    use Blameable;
    use Timestampable;
    use Uuid;

    /**
     * @OA\Property(type="string", format="uuid")
     */
    #[ORM\Id]
    #[ORM\Column(
        name: 'id',
        type: UuidBinaryOrderedTimeType::NAME,
        unique: true,
        nullable: false,
    )]
    #[Groups([
        'Office',
        'Office.id',
        self::SET_USER_OFFICE,
        self::SET_USER_BASIC,
    ])]
    private UuidInterface $id;


    #[ORM\Column(
        name: 'officeName',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'Office',
        'Office.officeName',

        self::SET_USER_OFFICE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private string $officeName = '';

    #[ORM\Column(
        name: 'officeDescription',
        type: Types::STRING,
        length: 255,
        nullable: false,
    )]
    #[Groups([
        'Office',
        'Office.officeDescription',

        self::SET_USER_OFFICE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private string $officeDescription = '';

    #[ORM\Column(
        name: 'permission',
        nullable: true
    )]
    #[Groups([
        'Office',
        'Office.permission',

        self::SET_USER_OFFICE,
        self::SET_USER_BASIC,
    ])]
    private array $permission = [];

    #[ORM\Column(
        name: 'active',
        type: Types::BOOLEAN,
        nullable: false,
    )]
    #[Groups([
        'Office',
        'Office.active',

        self::SET_USER_OFFICE,
        self::SET_USER_BASIC,
    ])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private bool $active = true;


    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, cascade: ['persist'])]
    #[ORM\JoinTable(name: 'user_office')]
    #[Groups([
        'Office',
        'Office.users'
    ])]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: Document::class, inversedBy:'offices')]
    #[ORM\JoinTable(name: 'tbl_office_pdf')]
    private Collection $documents;


    /**
     * Constructor
     *
     * @throws \Throwable
     */
    public function __construct()
    {
        $this->id = $this->createUuid();
        $this->users = new ArrayCollection();
        $this->documents = new ArrayCollection();
    }

    /**
     * @return UuidInterface
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getOfficeName(): string
    {
        return $this->officeName;
    }

    /**
     * @param string $officeName
     */
    public function setOfficeName(string $officeName): void
    {
        $this->officeName = $officeName;
    }

    /**
     * @return string
     */
    public function getOfficeDescription(): string
    {
        return $this->officeDescription;
    }

    /**
     * @param string $officeDescription
     */
    public function setOfficeDescription(string $officeDescription): void
    {
        $this->officeDescription = $officeDescription;
    }

    /**
     * @return array
     */
    public function getPermission(): array
    {
        return $this->permission;
    }

    /**
     * @param array $permission
     */
    public function setPermission(array $permission): void
    {
        $this->permission = $permission;
    }

    /**
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User ...$users): void
    {
        foreach ($users as $user) {
            if (!$this->users->contains($user)) {
                $this->users->add($user);
            }
        }
    }

    public function removeUser(User $user): void
    {
        $this->users->removeElement($user);
    }



    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): string
    {
        // This entity implements JsonSerializable (http://php.net/manual/en/class.jsonserializable.php)
        // so this method is used to customize its JSON representation when json_encode()
        // is called, for example in tags|json_encode (templates/form/fields.html.twig)

        return $this->officeName;
    }

    public function __toString(): string
    {
        return $this->officeName;
    }
}
