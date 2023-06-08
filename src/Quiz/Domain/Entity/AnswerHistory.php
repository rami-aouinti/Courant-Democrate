<?php

namespace App\Quiz\Domain\Entity;

use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\Quiz\Domain\Entity\Traits\Blameable;
use App\Quiz\Infrastructure\Repository\AnswerHistoryRepository;
use App\User\Domain\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AnswerHistoryRepository::class)]
#[ORM\Table(name: 'tbl_history_answer')]
class AnswerHistory
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
        'AnswerHistory',
        'AnswerHistory.id',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private UuidInterface $id;

    #[ORM\Column(type: 'integer')]
    #[Groups([
        'AnswerHistory',
        'AnswerHistory.answer_id',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?int $answer_id;

    #[ORM\ManyToOne(targetEntity: QuestionHistory::class, inversedBy:'answersHistory')]
    #[ORM\JoinColumn(nullable: false)]
    private ?QuestionHistory $question_history;

    #[ORM\Column(type: 'text')]
    #[Groups([
        'AnswerHistory',
        'AnswerHistory.answer_text',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?string $answer_text;

    #[ORM\Column(type: 'boolean')]
    #[Groups([
        'AnswerHistory',
        'AnswerHistory.answer_correct',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?bool $answer_correct;

    #[ORM\Column(type: 'boolean')]
    #[Groups([
        'AnswerHistory',
        'AnswerHistory.correct_given',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?bool $correct_given;

    #[ORM\Column(type: 'boolean')]
    #[Groups([
        'AnswerHistory',
        'AnswerHistory.answer_succes',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?bool $answer_succes;

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

    public function getAnswerId(): ?int
    {
        return $this->answer_id;
    }

    public function setAnswerId(int $answer_id): self
    {
        $this->answer_id = $answer_id;

        return $this;
    }

    public function getQuestionHistory(): ?QuestionHistory
    {
        return $this->question_history;
    }

    public function setQuestionHistory(?QuestionHistory $question_history): self
    {
        $this->question_history = $question_history;

        return $this;
    }

    public function getAnswerText(): ?string
    {
        return $this->answer_text;
    }

    public function setAnswerText(string $answer_text): self
    {
        $this->answer_text = $answer_text;

        return $this;
    }

    public function getAnswerCorrect(): ?bool
    {
        return $this->answer_correct;
    }

    public function setAnswerCorrect(bool $answer_correct): self
    {
        $this->answer_correct = $answer_correct;

        return $this;
    }

    public function getCorrectGiven(): ?bool
    {
        return $this->correct_given;
    }

    public function setCorrectGiven(bool $correct_given): self
    {
        $this->correct_given = $correct_given;

        return $this;
    }

    public function getAnswerSucces(): ?bool
    {
        return $this->answer_succes;
    }

    public function setAnswerSucces(bool $answer_succes): self
    {
        $this->answer_succes = $answer_succes;

        return $this;
    }
}
