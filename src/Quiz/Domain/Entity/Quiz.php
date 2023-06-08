<?php

namespace App\Quiz\Domain\Entity;

use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\Quiz\Domain\Entity\Traits\Blameable;
use App\Quiz\Infrastructure\Repository\QuizRepository;
use App\User\Domain\Entity\User;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
#[ORM\Table(name: 'tbl_quiz')]
class Quiz
{
    final public const SET_USER_PROFILE = 'set.UserProfile';
    final public const SET_USER_QUIZ = 'set.UserQuiz';


    use Blameable;
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
        'Quiz',
        'Quiz.id',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private UuidInterface $id;

    #[ORM\Column(length: 255)]
    #[Groups([
        'Quiz',
        'Quiz.title',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?string $title;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups([
        'Quiz',
        'Quiz.summary',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?string $summary;

    #[ORM\Column(type: 'integer')]
    #[Groups([
        'Quiz',
        'Quiz.number_of_questions',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?int $number_of_questions;

    #[ORM\Column(type: 'boolean')]
    #[Groups([
        'Quiz',
        'Quiz.active',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private false $active;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $created_at;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updated_at;

    #[ORM\ManyToOne(inversedBy: 'quizzes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $created_by = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy:'quizzes')]
    #[ORM\JoinTable(name: 'tbl_quiz_category')]
    private ArrayCollection $categories;

    #[ORM\OneToMany(mappedBy: 'quiz', targetEntity: Workout::class, orphanRemoval: true)]
    private ArrayCollection $workouts;

    #[ORM\Column(type: 'boolean')]
    #[Groups([
        'Quiz',
        'Quiz.show_result_question',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?bool $show_result_question;

    #[ORM\Column(type: 'boolean')]
    #[Groups([
        'Quiz',
        'Quiz.show_result_quiz',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?bool $show_result_quiz;

    #[ORM\ManyToOne(targetEntity: Language::class, inversedBy:'quizzes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Language $language;

    #[ORM\Column(type: 'boolean')]
    #[Groups([
        'Quiz',
        'Quiz.allow_anonymous_workout',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?bool $allow_anonymous_workout;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups([
        'Quiz',
        'Quiz.result_quiz_comment',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?string $result_quiz_comment;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups([
        'Quiz',
        'Quiz.start_quiz_comment',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?string $start_quiz_comment;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups([
        'Quiz',
        'Quiz.default_question_max_duration',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?int $default_question_max_duration;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $actived_at;

    #[ORM\OneToMany(mappedBy: 'quiz', targetEntity: Session::class, orphanRemoval: true)]
    private ArrayCollection $sessions;

    public function __construct()
    {
        $this->active = false;
        $this->id = $this->createUuid();
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->setShowResultQuestion(false);
        $this->setShowResultQuiz(false);
        $this->setNumberOfQuestions(5);
        $this->setDefaultQuestionMaxDuration(60); //180
        $this->categories = new ArrayCollection();
        $this->workouts = new ArrayCollection();
        $this->setAllowAnonymousWorkout(false);
        $this->sessions = new ArrayCollection();
    }

    /**
     * @return UuidInterface
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $now = new DateTime();

        if ($active) {
            if (!$this->getActive()) {
                $this->actived_at = $now;
            }
        } else {
            $this->actived_at = null;
            $session = $this->getLastSession();
        }

        $this->active = $active;

        return $this;
    }

    public function setActiveInSession(bool $active, EntityManager $em): self
    {
        $now = new DateTime();

        if ($active) {
            if (!$this->getActive()) {
                $this->actived_at = $now;
                $session = new Session($this, $now);
                $em->persist($session);
            }
        } else {
            $this->actived_at = null;
            $session = $this->getLastSession();
            $session->setEndedAt($now);
            $em->persist($session);
        }

        $this->active = $active;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

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
            $workout->setQuiz($this);
        }

        return $this;
    }

    public function removeWorkout(Workout $workout): self
    {
        if ($this->workouts->contains($workout)) {
            $this->workouts->removeElement($workout);
            // set the owning side to null (unless already changed)
            if ($workout->getQuiz() === $this) {
                $workout->setQuiz(null);
            }
        }

        return $this;
    }

    public function getShowResultQuestion(): ?bool
    {
        return $this->show_result_question;
    }

    public function setShowResultQuestion(bool $show_result_question): self
    {
        $this->show_result_question = $show_result_question;

        return $this;
    }

    public function getShowResultQuiz(): ?bool
    {
        return $this->show_result_quiz;
    }

    public function setShowResultQuiz(bool $show_result_quiz): self
    {
        $this->show_result_quiz = $show_result_quiz;

        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getAllowAnonymousWorkout(): ?bool
    {
        return $this->allow_anonymous_workout;
    }

    public function setAllowAnonymousWorkout(bool $allow_anonymous_workout): self
    {
        $this->allow_anonymous_workout = $allow_anonymous_workout;

        return $this;
    }

    public function getResultQuizComment(): ?string
    {
        return $this->result_quiz_comment;
    }

    public function setResultQuizComment(?string $result_quiz_comment): self
    {
        $this->result_quiz_comment = $result_quiz_comment;

        return $this;
    }

    public function getStartQuizComment(): ?string
    {
        return $this->start_quiz_comment;
    }

    public function setStartQuizComment(?string $start_quiz_comment): self
    {
        $this->start_quiz_comment = $start_quiz_comment;

        return $this;
    }

    public function getDefaultQuestionMaxDuration(): ?int
    {
        return $this->default_question_max_duration;
    }

    public function setDefaultQuestionMaxDuration(?int $default_question_max_duration): self
    {
        $this->default_question_max_duration = $default_question_max_duration;

        return $this;
    }

    public function getActivedAt(): ?\DateTimeInterface
    {
        return $this->actived_at;
    }

    public function setActivedAt(?\DateTimeInterface $actived_at): self
    {
        $this->actived_at = $actived_at;

        return $this;
    }

    /**
     * @return Collection|Session[]
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    /**
     * @return Session
     */
    public function getLastSession(): Session
    {
        if ($this->sessions->last()) {
            return $this->sessions->last();
        } else {
            return new Session($this, new DateTime());
        }
    }

    public function addSession(Session $session): self
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions[] = $session;
            $session->setQuiz($this);
        }

        return $this;
    }

    public function removeSession(Session $session): self
    {
        if ($this->sessions->contains($session)) {
            $this->sessions->removeElement($session);
            // set the owning side to null (unless already changed)
            if ($session->getQuiz() === $this) {
                $session->setQuiz(null);
            }
        }

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }


}
