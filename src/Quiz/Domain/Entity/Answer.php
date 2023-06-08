<?php

namespace App\Quiz\Domain\Entity;

use App\Quiz\Domain\Entity\Traits\Blameable;
use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\Quiz\Infrastructure\Repository\AnswerRepository;
use App\User\Domain\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: AnswerRepository::class)]
#[ORM\Table(name: 'tbl_answer')]
class Answer implements \App\General\Domain\Entity\Interfaces\EntityInterface
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
        'Answer',
        'Answer.id',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private UuidInterface $id;

    #[ORM\Column(type: 'text')]
    #[Groups([
        'Answer',
        'Answer.text',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?string $text;

    #[ORM\Column(type: 'boolean')]
    #[Groups([
        'Answer',
        'Answer.correct',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?bool $correct;

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy:'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question;

    /**
     * This field will not be persisted
     * It is used to store the answer given by student (type="boolean") in the form
     */
    private bool $workout_correct_given = false;


    public function __construct()
    {
        $this->id = $this->createUuid();
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

    public function getCorrect(): ?bool
    {
        return $this->correct;
    }

    public function setCorrect(bool $correct): self
    {
        $this->correct = $correct;

        return $this;
    }

    public function getWorkoutCorrectGiven(): ?bool
    {
        return $this->workout_correct_given;
    }

    public function setWorkoutCorrectGiven(bool $workout_correct_given): self
    {
        $this->workout_correct_given = $workout_correct_given;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

}
