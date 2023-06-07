<?php

declare(strict_types=1);

namespace App\Event\Application\DTO\Event;

use App\General\Application\DTO\Interfaces\RestDtoInterface;
use App\General\Application\DTO\RestDto;
use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\Event\Domain\Entity\Event as Entity;
use App\User\Domain\Entity\User;
use DateTime;
use DateTimeImmutable;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\Constraints as Assert;

use function array_map;

/**
 * Class Event
 *
 * @package App\Event
 *
 * @method self|RestDtoInterface get(string $id)
 * @method self|RestDtoInterface patch(RestDtoInterface $dto)
 * @method Entity|EntityInterface update(EntityInterface $entity)
 */
class Event extends RestDto
{

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $title = '';


    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $description = '';


    #[Assert\NotNull]
    protected bool $allDays = false;


    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $backgroundColor = '';


    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $borderColor = '';


    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $textColor = '';

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $className = '';


    #[Assert\NotBlank]
    #[Assert\NotNull]
    protected \DateTime $start;


    #[Assert\NotBlank]
    #[Assert\NotNull]
    protected \DateTime $end;


    protected User $user;

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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): self
    {
        $this->setVisited('description');
        $this->description = $description;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAllDays(): bool
    {
        return $this->allDays;
    }

    /**
     * @param bool $allDays
     */
    public function setAllDays(bool $allDays): self
    {
        $this->setVisited('allDays');
        $this->allDays = $allDays;

        return $this;
    }

    /**
     * @return string
     */
    public function getBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    /**
     * @param string $backgroundColor
     */
    public function setBackgroundColor(string $backgroundColor): self
    {
        $this->setVisited('backgroundColor');
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    /**
     * @return string
     */
    public function getBorderColor(): string
    {
        return $this->borderColor;
    }

    /**
     * @param string $borderColor
     */
    public function setBorderColor(string $borderColor): self
    {
        $this->setVisited('borderColor');
        $this->borderColor = $borderColor;

        return $this;
    }

    /**
     * @return string
     */
    public function getTextColor(): string
    {
        return $this->textColor;
    }

    /**
     * @param string $textColor
     */
    public function setTextColor(string $textColor): self
    {
        $this->setVisited('textColor');
        $this->textColor = $textColor;

        return $this;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName(string $className): self
    {
        $this->setVisited('className');
        $this->className = $className;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStart(): \DateTime
    {
        return $this->start;
    }

    /**
     * @param \DateTime|string $start
     * @throws \Exception
     */
    public function setStart(\DateTime|string $start): self
    {
        $this->setVisited('start');
        if(is_string($start)) {
            $timestamp = new DateTimeImmutable($start);
            $this->start = DateTime::createFromImmutable($timestamp);
        }
        else {
            $this->start = $start;
        }

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEnd(): \DateTime
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     * @throws \Exception
     */
    public function setEnd(\DateTime|string $end): self
    {
        $this->setVisited('end');

        if(is_string($end)) {
            $timestamp = new DateTimeImmutable($end);
            $this->end = DateTime::createFromImmutable($timestamp);
        }
        else {
            $this->end = $end;
        }

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Event
     */
    public function setUser(#[CurrentUser] User $user): self
    {
        $this->setVisited('user');

        $this->user = $user;

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
            $this->description = $entity->getDescription();
            $this->allDays = $entity->getAllDays();
            $this->backgroundColor = $entity->getBackgroundColor();
            $this->borderColor = $entity->getBorderColor();
            $this->textColor = $entity->getTextColor();
            $this->className = $entity->getClassName();
            $this->start = $entity->getStart();
            $this->end = $entity->getEnd();
            $this->user = $entity->getUser();
        }

        return $this;
    }
}
