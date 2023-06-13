<?php

namespace App\Quiz\Domain\Entity;

use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\Quiz\Domain\Entity\Traits\Blameable;
use App\Quiz\Infrastructure\Repository\QuestionRepository;
use App\User\Domain\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ORM\Table(name: 'tbl_question')]
class Question
{

    public const NUM_ITEMS = 10;

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
        'Question',
        'Question.id',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private UuidInterface $id;

    #[ORM\Column(type: 'text')]
    #[Groups([
        'Question',
        'Question.text',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?string $text;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $created_at;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updated_at;

    // #[ORM\ManyToOne(targetEntity: User::class, inversedBy:'questions')]
    // private $created_by;
    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?User $created_by = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'questions')]
    #[ORM\JoinTable(name: 'tbl_question_category')]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answer::class, orphanRemoval: true)]
    private Collection $answers;

    #[ORM\ManyToOne(targetEntity: Language::class, inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Language $language;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups([
        'Question',
        'Question.max_duration',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?int $max_duration;


    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups([
        'Question',
        'Question.complicated',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?int $complicated;


    public function __construct()
    {
        $this->id = $this->createUuid();
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->categories = new ArrayCollection();
        $this->answers = new ArrayCollection();
    }

    /**
     * @return UuidInterface
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

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

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @return Category
     */
    public function getFirstCategory(): ?Category
    {
        if (sizeof($this->categories) > 0) {
            return $this->categories[0];
        }
        else {
            return null;
        }
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
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

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

    public function getMaxDuration(): ?int
    {
        return $this->max_duration;
    }

    public function setMaxDuration(?int $max_duration): self
    {
        $this->max_duration = $max_duration;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getComplicated(): ?int
    {
        return $this->complicated;
    }

    /**
     * @param int|null $complicated
     */
    public function setComplicated(?int $complicated): void
    {
        $this->complicated = $complicated;
    }

}
