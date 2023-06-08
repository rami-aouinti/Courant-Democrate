<?php

namespace App\Quiz\Domain\Entity;

use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\Quiz\Domain\Entity\Traits\Blameable;
use App\Quiz\Infrastructure\Repository\WorkoutRepository;
use App\User\Domain\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WorkoutRepository::class)]
#[ORM\Table(name: 'tbl_workout')]
class Workout
{
    final public const SET_USER_PROFILE = 'set.UserProfile';
    final public const SET_USER_QUIZ= 'set.UserQuiz';


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
        'Workout',
        'Workout.id',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private UuidInterface $id;

    #[ORM\ManyToOne(inversedBy: 'workouts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $student = null;

    #[ORM\ManyToOne(targetEntity: Quiz::class, inversedBy:'workouts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quiz $quiz;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $started_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $ended_at;

    #[ORM\Column(type: 'integer')]
    #[Groups([
        'Workout',
        'Workout.number_of_questions',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?int $number_of_questions;

    #[ORM\Column(type: 'boolean')]
    #[Groups([
        'Workout',
        'Workout.completed',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private false $completed;

    #[ORM\OneToMany(mappedBy: 'workout', targetEntity: QuestionHistory::class, orphanRemoval: true)]
    private ArrayCollection $questionsHistory;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups([
        'Workout',
        'Workout.score',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?float $score;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups([
        'Workout',
        'Workout.comment',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?string $comment;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        'Workout',
        'Workout.token',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?string $token;

    #[ORM\ManyToOne(targetEntity: Session::class, inversedBy:'workouts')]
    private ?Session $session;

    public function __construct()
    {
        $this->completed = false;
        $this->questionsHistory = new ArrayCollection();
        $this->id = $this->createUuid();
    }


    /**
     * @return UuidInterface
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getStudent(): ?User
    {
        return $this->student;
    }

    public function setStudent(?User $student): self
    {
        $this->student = $student;

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

    public function setEndedAt(\DateTimeInterface $ended_at): self
    {
        $this->ended_at = $ended_at;

        return $this;
    }

    public function getDuration(): ?\DateInterval
    {
        return $this->ended_at->diff($this->started_at);
    }

    public function getNumberOfQuestions(): ?int
    {
        return $this->number_of_questions;
    }

    public function setNumberOfQuestions(int $number_of_questions): self
    {
        $this->number_of_questions = $number_of_questions;

        return $this;
    }

    public function getCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): self
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * @return Collection|QuestionHistory[]
     */
    public function getQuestionsHistory(): Collection
    {
        return $this->questionsHistory;
    }

    public function addQuestionsHistory(QuestionHistory $questionsHistory): self
    {
        if (!$this->questionsHistory->contains($questionsHistory)) {
            $this->questionsHistory[] = $questionsHistory;
            $questionsHistory->setWorkout($this);
        }

        return $this;
    }

    public function removeQuestionsHistory(QuestionHistory $questionsHistory): self
    {
        if ($this->questionsHistory->contains($questionsHistory)) {
            $this->questionsHistory->removeElement($questionsHistory);
            // set the owning side to null (unless already changed)
            if ($questionsHistory->getWorkout() === $this) {
                $questionsHistory->setWorkout(null);
            }
        }

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(?float $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;

        return $this;
    }
}
