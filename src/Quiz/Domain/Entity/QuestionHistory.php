<?php

namespace App\Quiz\Domain\Entity;

use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\Quiz\Domain\Entity\Traits\Blameable;
use App\Quiz\Infrastructure\Repository\QuestionHistoryRepository;
use App\User\Domain\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: QuestionHistoryRepository::class)]
#[ORM\Table(name: 'tbl_history_question')]
class QuestionHistory
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

    #[ORM\ManyToOne(targetEntity: Workout::class, inversedBy:'questionsHistory')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Workout $workout;

    #[ORM\Column(type: 'integer')]
    private ?int $question_id;

    #[ORM\Column(type: 'text')]
    private ?string $question_text;

    #[ORM\Column(type: 'boolean')]
    private ?bool $completed;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $question_success;

    #[ORM\Column(type: 'dateinterval', nullable: true)]
    private ?\DateInterval $duration;

    #[ORM\OneToMany(mappedBy: 'question_history', targetEntity: AnswerHistory::class, orphanRemoval: true)]
    private ArrayCollection $answersHistory;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $started_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $ended_at;


    public function __construct()
    {
        $this->id = $this->createUuid();
        $this->answersHistory = new ArrayCollection();
    }

    /**
     * @return UuidInterface
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getWorkout(): ?Workout
    {
        return $this->workout;
    }

    public function setWorkout(?Workout $workout): self
    {
        $this->workout = $workout;

        return $this;
    }

    public function getQuestionId(): ?int
    {
        return $this->question_id;
    }

    public function setQuestionId(int $question_id): self
    {
        $this->question_id = $question_id;

        return $this;
    }

    public function getQuestionText(): ?string
    {
        return $this->question_text;
    }

    public function setQuestionText(string $question_text): self
    {
        $this->question_text = $question_text;

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

    public function getQuestionSuccess(): ?bool
    {
        return $this->question_success;
    }

    public function setQuestionSuccess(?bool $question_success): self
    {
        $this->question_success = $question_success;

        return $this;
    }

    public function getDuration(): ?\DateInterval
    {
        return $this->duration;
    }

    public function setDuration(?\DateInterval $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection|AnswerHistory[]
     */
    public function getAnswersHistory(): Collection
    {
        return $this->answersHistory;
    }

    public function addAnswersHistory(AnswerHistory $answersHistory): self
    {
        if (!$this->answersHistory->contains($answersHistory)) {
            $this->answersHistory[] = $answersHistory;
            $answersHistory->setQuestionHistory($this);
        }

        return $this;
    }

    public function removeAnswersHistory(AnswerHistory $answersHistory): self
    {
        if ($this->answersHistory->contains($answersHistory)) {
            $this->answersHistory->removeElement($answersHistory);
            // set the owning side to null (unless already changed)
            if ($answersHistory->getQuestionHistory() === $this) {
                $answersHistory->setQuestionHistory(null);
            }
        }

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

    public function setEndedAt(?\DateTimeInterface $ended_at): self
    {
        $this->ended_at = $ended_at;

        return $this;
    }
}
