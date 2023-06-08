<?php

namespace App\Quiz\Domain\Entity;

use App\General\Domain\Entity\Traits\Timestampable;
use App\General\Domain\Entity\Traits\Uuid;
use App\Quiz\Domain\Entity\Traits\Blameable;
use App\Quiz\Infrastructure\Repository\ConfigurationRepository;
use App\User\Domain\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ConfigurationRepository::class)]
#[ORM\Table(name: 'tbl_configuration')]
class Configuration
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
        'Configuration',
        'Configuration.id',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private UuidInterface $id;

    #[ORM\Column(length: 255)]
    #[Groups([
        'Configuration',
        'Configuration.const',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?string $const = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Groups([
        'Configuration',
        'Configuration.type',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([
        'Configuration',
        'Configuration.description',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?string $description = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups([
        'Configuration',
        'Configuration.value',
        self::SET_USER_QUIZ,
        User::SET_USER_PROFILE
    ])]
    private ?string $value = null;

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

    public function getConst(): ?string
    {
        return $this->const;
    }

    public function setConst(string $const): self
    {
        $this->const = $const;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
