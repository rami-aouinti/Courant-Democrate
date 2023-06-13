<?php

namespace App\Quiz\Domain\Entity;

use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\Quiz\Domain\Entity\Traits\Blameable;
use App\Quiz\Domain\Repository\ScoreRepository;
use App\User\Domain\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ScoreRepository::class)]
#[ORM\Table(name: 'tbl_score')]
class Score
{
    final public const SET_USER_PROFILE = 'set.UserProfile';
    final public const SET_USER_QUIZ = 'set.UserQuiz';


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
        'School',
        'School.id',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private UuidInterface $id;

    #[ORM\Column()]
    #[Groups([
        'School',
        'School.score',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?int $score = 0;

    #[ORM\Column(length: 16)]
    #[Groups([
        'School',
        'School.level',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?int $level = 0;

    /** One Cart has One Customer. */
    #[OneToOne(inversedBy: 'score', targetEntity: User::class)]
    #[Gedmo\Blameable(
        on: 'create',
    )]
    private User|null $user = null;

    public function __construct()
    {
        $this->id = $this->createUuid();
    }

    public function __toString(): string
    {
        return $this->score;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getScore(): ?int
    {
        return $this->score;
    }

    /**
     * @param int|null $score
     */
    public function setScore(?int $score): void
    {
        $this->score = $score;
    }

    /**
     * @return int|null
     */
    public function getLevel(): ?int
    {
        return $this->level;
    }

    /**
     * @param int|null $level
     */
    public function setLevel(?int $level): void
    {
        $this->level = $level;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

}
