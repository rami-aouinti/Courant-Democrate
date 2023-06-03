<?php

declare(strict_types=1);

namespace App\Project\Shared\Domain\ValueObject;

use App\Project\Shared\Domain\Collection\Hashable;
use App\Project\Shared\Domain\Exception\InvalidArgumentException;

abstract class StringValueObject implements Hashable
{
    final public function __construct(public readonly string $value)
    {
        $this->ensureIsValid();
    }

    abstract protected function ensureIsValid(): void;

    protected function ensureValidMaxLength(string $attributeName, int $maxLength): void
    {
        if (mb_strlen($this->value) > $maxLength) {
            throw new InvalidArgumentException(sprintf('"%s" should contain at most %s characters.', $attributeName, $maxLength));
        }
    }

    protected function ensureValidMinLength(string $attributeName, int $minLength): void
    {
        if (mb_strlen($this->value) < $minLength) {
            throw new InvalidArgumentException(sprintf('"%s" should contain at least %s characters.', $attributeName, $minLength));
        }
    }

    protected function ensureNotEmpty(string $attributeName): void
    {
        if (empty($this->value)) {
            throw new InvalidArgumentException(sprintf('"%s" cannot be blank.', $attributeName));
        }
    }

    public function getHash(): string
    {
        return $this->value;
    }

    public function isEqual(object $other): bool
    {
        if (get_class($this) !== get_class($other)) {
            return false;
        }

        return $this->value === $other->value;
    }
}
