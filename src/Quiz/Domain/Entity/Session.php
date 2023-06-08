<?php

namespace App\Quiz\Domain\Entity;

use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\Quiz\Domain\Entity\Traits\Blameable;
use App\Quiz\Infrastructure\Repository\SessionRepository;
use App\User\Domain\Entity\User;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
#[ORM\Table(name: 'tbl_session')]
class Session
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

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $started_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $ended_at;

    #[ORM\ManyToOne(targetEntity: Quiz::class, inversedBy:'sessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quiz $quiz;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Workout::class, orphanRemoval: true)]
    private ArrayCollection $workouts;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $sended_to_ed;

    public function __construct(Quiz $quiz, DateTime $started_at) {
        $this->setQuiz($quiz);
        $this->setStartedAt($started_at);
        $this->workouts = new ArrayCollection();
        $this->id = $this->createUuid();
    }

    /**
     * @return UuidInterface
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->started_at;
    }

    public function setStartedAt(\DateTimeInterface $started_at): self
    {
        $this->started_at = $started_at;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeInterface
    {
        return $this->ended_at;
    }

    public function setEndedAt(?\DateTimeInterface $ended_at): self
    {
        $this->ended_at = $ended_at;

        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * @return Collection|Workout[]
     */
    public function getWorkouts(): Collection
    {
        return $this->workouts;
    }

    public function addWorkout(Workout $workout): self
    {
        if (!$this->workouts->contains($workout)) {
            $this->workouts[] = $workout;
            $workout->setSession($this);
        }

        return $this;
    }

    public function removeWorkout(Workout $workout): self
    {
        if ($this->workouts->removeElement($workout)) {
            // set the owning side to null (unless already changed)
            if ($workout->getSession() === $this) {
                $workout->setSession(null);
            }
        }

        return $this;
    }

    public function getSendedToED(): ?bool
    {
        return $this->sended_to_ed;
    }

    public function setSendedToED(?bool $sended_to_ed): self
    {
        $this->sended_to_ed = $sended_to_ed;

        return $this;
    }

}
