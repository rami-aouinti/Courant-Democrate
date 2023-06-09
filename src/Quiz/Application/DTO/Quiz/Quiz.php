<?php

declare(strict_types=1);

namespace App\Quiz\Application\DTO\Quiz;

use App\General\Application\DTO\Interfaces\RestDtoInterface;
use App\General\Application\DTO\RestDto;
use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\Quiz\Domain\Entity\Quiz as Entity;
use Symfony\Component\Validator\Constraints as Assert;

use function array_map;

/**
 * Class Quiz
 *
 * @package App\Quiz
 *
 * @method self|RestDtoInterface get(string $id)
 * @method self|RestDtoInterface patch(RestDtoInterface $dto)
 * @method Entity|EntityInterface update(EntityInterface $entity)
 */
class Quiz extends RestDto
{

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $title = '';


    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $summary = '';


    protected int $number_of_questions;

    protected bool $active = false;


    protected bool $show_result_question = false;


    protected bool $show_result_quiz = false;


    protected bool $allow_anonymous_workout = false;


    protected string $result_quiz_comment = '';


    protected string $start_quiz_comment = '';


    protected int $default_question_max_duration;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): self
    {
        $this->setVisited('title');
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary(string $summary): self
    {
        $this->setVisited('summary');
        $this->summary = $summary;

        return $this;
    }

    /**
     * @return string
     */
    public function getNumberOfQuestions(): int
    {
        return $this->number_of_questions;
    }

    /**
     * @param int $number_of_questions
     */
    public function setNumberOfQuestions(int $number_of_questions): self
    {
        $this->setVisited('number_of_questions');
        $this->number_of_questions = $number_of_questions;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): self
    {
        $this->setVisited('active');
        $this->active = $active;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShowResultQuestion(): bool
    {
        return $this->show_result_question;
    }

    /**
     * @param bool $show_result_question
     */
    public function setShowResultQuestion(bool $show_result_question): self
    {
        $this->setVisited('show_result_question');
        $this->show_result_question = $show_result_question;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShowResultQuiz(): bool
    {
        return $this->show_result_quiz;
    }

    /**
     * @param bool $show_result_quiz
     */
    public function setShowResultQuiz(bool $show_result_quiz): self
    {
        $this->setVisited('show_result_quiz');
        $this->show_result_quiz = $show_result_quiz;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowAnonymousWorkout(): bool
    {
        return $this->allow_anonymous_workout;
    }

    /**
     * @param bool $allow_anonymous_workout
     */
    public function setAllowAnonymousWorkout(bool $allow_anonymous_workout): self
    {
        $this->setVisited('allow_anonymous_workout');
        $this->allow_anonymous_workout = $allow_anonymous_workout;
        return $this;
    }

    /**
     * @return string
     */
    public function getResultQuizComment(): string
    {
        return $this->result_quiz_comment;
    }

    /**
     * @param string $result_quiz_comment
     */
    public function setResultQuizComment(string $result_quiz_comment): self
    {
        $this->setVisited('result_quiz_comment');
        $this->result_quiz_comment = $result_quiz_comment;
        return $this;
    }

    /**
     * @return string
     */
    public function getStartQuizComment(): string
    {
        return $this->start_quiz_comment;
    }

    /**
     * @param string $start_quiz_comment
     */
    public function setStartQuizComment(string $start_quiz_comment): self
    {
        $this->setVisited('start_quiz_comment');
        $this->start_quiz_comment = $start_quiz_comment;
        return $this;
    }

    /**
     * @return int
     */
    public function getDefaultQuestionMaxDuration(): int
    {
        return $this->default_question_max_duration;
    }

    /**
     * @param int $default_question_max_duration
     */
    public function setDefaultQuestionMaxDuration(int $default_question_max_duration): self
    {
        $this->setVisited('default_question_max_duration');
        $this->default_question_max_duration = $default_question_max_duration;
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param EntityInterface|Entity $entity
     */
    public function load(EntityInterface $entity): self
    {
        if ($entity instanceof Entity) {
            $this->id = $entity->getId();
            $this->title = $entity->getTitle();
            $this->summary = $entity->getSummary();
            $this->number_of_questions = $entity->getNumberOfQuestions();
            $this->active = $entity->getActive();
            $this->show_result_quiz = $entity->getShowResultQuiz();
            $this->show_result_question = $entity->getShowResultQuestion();
            $this->allow_anonymous_workout = $entity->getAllowAnonymousWorkout();
            $this->result_quiz_comment = $entity->getResultQuizComment();
            $this->start_quiz_comment = $entity->getStartQuizComment();
            $this->default_question_max_duration = $entity->getDefaultQuestionMaxDuration();
        }

        return $this;
    }
}
