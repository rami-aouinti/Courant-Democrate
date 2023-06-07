<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Event\Domain\Entity;

use App\Event\Domain\Entity\Traits\Blameable;
use App\General\Domain\Entity\Interfaces\EntityInterface;
use App\Event\Infrastructure\Repository\EventRepository;
use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\User\Domain\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Defines the properties of the Post entity to represent the blog posts.
 *
 * See https://symfony.com/doc/current/doctrine.html#creating-an-entity-class
 *
 * Tip: if you have an existing database, you can generate these entity class automatically.
 * See https://symfony.com/doc/current/doctrine/reverse_engineering.html
 *

 * @author Rami Aouinti <rami.aouinti@gmail.com>
 */
#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(name: 'event')]
class Event implements EntityInterface
{

    final public const SET_USER_PROFILE = 'set.UserProfile';
    final public const SET_USER_EVENT = 'set.UserEvent';


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

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Groups([
        'Event',
        'Event.title',
        self::SET_USER_EVENT,
        User::SET_USER_PROFILE
    ])]
    private ?string $title = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Groups([
        'Event',
        'Event.description',
        self::SET_USER_EVENT,
        User::SET_USER_PROFILE
    ])]
    private ?string $description = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Assert\NotBlank]
    #[Groups([
        'Event',
        'Event.allDays',
        self::SET_USER_EVENT,
        User::SET_USER_PROFILE
    ])]
    private ?bool $allDays = false;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Groups([
        'Event',
        'Event.backgroundColor',
        self::SET_USER_EVENT,
        User::SET_USER_PROFILE
    ])]
    private ?string $backgroundColor = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Groups([
        'Event',
        'Event.borderColor',
        self::SET_USER_EVENT,
        User::SET_USER_PROFILE
    ])]
    private ?string $borderColor = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Groups([
        'Event',
        'Event.textColor',
        self::SET_USER_EVENT,
        User::SET_USER_PROFILE
    ])]
    private ?string $textColor = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Groups([
        'Event',
        'Event.class_name',
        self::SET_USER_EVENT,
        User::SET_USER_PROFILE
    ])]
    private ?string $className = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Groups([
        'Event',
        'Event.start',
        self::SET_USER_EVENT,
        User::SET_USER_PROFILE
    ])]
    private \DateTime $start;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Groups([
        'Event',
        'Event.end',
        self::SET_USER_EVENT,
        User::SET_USER_PROFILE
    ])]
    private \DateTime $end;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups([
        'Event',
        'Event.publishedAt',
        self::SET_USER_EVENT,
        User::SET_USER_PROFILE
    ])]
    private \DateTime $publishedAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    #[Gedmo\Blameable(
        on: 'create',
    )]
    #[Groups([
        'Event',
        'Event.user',
        self::SET_USER_EVENT
    ])]
    private ?User $user = null;


    public function __construct()
    {
        $this->publishedAt = new \DateTime();
        $this->id = $this->createUuid();
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

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return bool|null
     */
    public function getAllDays(): ?bool
    {
        return $this->allDays;
    }

    /**
     * @param bool|null $allDays
     */
    public function setAllDays(?bool $allDays): void
    {
        $this->allDays = $allDays;
    }

    /**
     * @return string|null
     */
    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    /**
     * @param string|null $backgroundColor
     */
    public function setBackgroundColor(?string $backgroundColor): void
    {
        $this->backgroundColor = $backgroundColor;
    }

    /**
     * @return string|null
     */
    public function getBorderColor(): ?string
    {
        return $this->borderColor;
    }

    /**
     * @param string|null $borderColor
     */
    public function setBorderColor(?string $borderColor): void
    {
        $this->borderColor = $borderColor;
    }

    /**
     * @return string|null
     */
    public function getTextColor(): ?string
    {
        return $this->textColor;
    }

    /**
     * @param string|null $textColor
     */
    public function setTextColor(?string $textColor): void
    {
        $this->textColor = $textColor;
    }

    /**
     * @return \DateTime
     */
    public function getStart(): \DateTime
    {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     */
    public function setStart(\DateTime $start): void
    {
        $this->start = $start;
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
     */
    public function setEnd(\DateTime $end): void
    {
        $this->end = $end;
    }

    /**
     * @return \DateTime
     */
    public function getPublishedAt(): \DateTime
    {
        return $this->publishedAt;
    }

    /**
     * @param \DateTime $publishedAt
     */
    public function setPublishedAt(\DateTime $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(#[CurrentUser]?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string|null
     */
    public function getClassName(): ?string
    {
        return $this->className;
    }

    /**
     * @param string|null $className
     */
    public function setClassName(?string $className): void
    {
        $this->className = $className;
    }
}
