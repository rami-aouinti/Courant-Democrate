<?php

namespace App\Quiz\Domain\Entity;

use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\Quiz\Domain\Entity\Traits\Blameable;
use App\Quiz\Infrastructure\Repository\GroupRepository;
use App\User\Domain\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: 'tbl_group')]
class Group
{
    final public const SET_USER_PROFILE = 'set.UserProfile';
    final public const SET_USER_EVENT = 'set.UserQuiz';


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
        'Event',
        'Event.id',
        self::SET_USER_EVENT,
        User::SET_USER_PROFILE
    ])]
    private UuidInterface $id;

    #[ORM\Column(length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: School::class, inversedBy:'groups')]
    #[ORM\JoinColumn(nullable: false)]
    private $school;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'groups', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true)]
    private Collection $users;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code;

    #[ORM\Column(length: 50, nullable: false)]
    private ?string $shortname;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $ed_id;



    public function __construct()
    {
        $this->id = $this->createUuid();
        $this->users = new ArrayCollection();
    }

    public function __toString(): string
    {
        if ($this->shortname) {
            return $this->shortname;
        } else {
            return "";
        }
    }

    /**
     * @return UuidInterface
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): self
    {
        $this->school = $school;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addGroup($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeGroup($this);
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getShortname(): ?string
    {
        return $this->shortname;
    }

    public function setShortname(?string $shortname): self
    {
        $this->shortname = $shortname;

        return $this;
    }

    public function getEdId(): ?int
    {
        return $this->ed_id;
    }

    public function setEdId(?int $ed_id): self
    {
        $this->ed_id = $ed_id;

        return $this;
    }
}
