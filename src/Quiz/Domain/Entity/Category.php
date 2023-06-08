<?php

namespace App\Quiz\Domain\Entity;

use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\Quiz\Domain\Entity\Traits\Blameable;
use App\Quiz\Infrastructure\Repository\CategoryRepository;
use App\User\Domain\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'tbl_category')]
class Category
{

    final public const SET_USER_PROFILE = 'set.UserProfile';
    final public const SET_USER_QUIZ= 'set.UserQuiz';


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
        'Category',
        'Category.id',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private UuidInterface $id;

    #[ORM\Column(length: 50)]
    #[Groups([
        'Category',
        'Category.shortname',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private mixed $shortname;

    #[ORM\Column(length: 255)]
    #[Groups([
        'Category',
        'Category.longname',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private mixed $longname;

    #[ORM\ManyToMany(targetEntity: Quiz::class, mappedBy: 'categories')]
    private Collection $quizzes;

    #[ORM\ManyToMany(targetEntity: Question::class, mappedBy: 'categories')]
    private Collection $questions;

    #[ORM\ManyToOne(targetEntity: Language::class, inversedBy:'categories')]
    #[ORM\JoinColumn(nullable: false)]
    private $language;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updated_at;

    #[ORM\ManyToOne(inversedBy: 'categories')]
    private ?User $created_by = null;

    // #[ORM\ManyToOne(targetEntity: User::class, inversedBy:'categories')]
    // private $created_by;

    public function __construct($shortname = null)
    {
        $this->id = $this->createUuid();
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->quizzes = new ArrayCollection();
        $this->questions = new ArrayCollection();
        if ($shortname) {
            $this->shortname = $shortname;
            $this->longname = $shortname;
        }
    }

    /**
     * @return UuidInterface
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getShortname(): ?string
    {
        return $this->shortname;
    }

    public function setShortname(string $shortname): self
    {
        $this->shortname = $shortname;

        return $this;
    }

    public function getLongname(): ?string
    {
        return $this->longname;
    }

    public function setLongname(string $longname): self
    {
        $this->longname = $longname;

        return $this;
    }

    /**
     * @return Collection|Quiz[]
     */
    public function getQuizzes(): Collection
    {
        return $this->quizzes;
    }

    public function addQuiz(Quiz $quiz): self
    {
        if (!$this->quizzes->contains($quiz)) {
            $this->quizzes[] = $quiz;
            $quiz->addCategory($this);
        }

        return $this;
    }

    public function removeQuiz(Quiz $quiz): self
    {
        if ($this->quizzes->contains($quiz)) {
            $this->quizzes->removeElement($quiz);
            $quiz->removeCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->addCategory($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            $question->removeCategory($this);
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
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
}
