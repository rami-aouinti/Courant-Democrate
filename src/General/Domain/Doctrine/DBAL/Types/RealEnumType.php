<?php

declare(strict_types=1);

namespace App\General\Domain\Doctrine\DBAL\Types;

use App\General\Domain\Enum\Interfaces\DatabaseEnumInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

use function array_map;
use function gettype;
use function implode;
use function in_array;
use function is_string;

/**
 * Class RealEnumType
 *
 * @package App\General
 */
abstract class RealEnumType extends Type
{
    protected static string $name;
    protected static string $enum;

    /**
     * @return array<int, string>
     */
    public static function getValues(): array
    {
        return static::$enum::getValues();
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $enumDefinition = implode(
            ', ',
            array_map(static fn (string $value): string => "'" . $value . "'", static::getValues()),
        );

        return 'ENUM(' . $enumDefinition . ')';
    }

    /**
     * @inheritDoc
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!in_array($value, static::$enum::cases(), true)) {

            return (string)parent::convertToDatabaseValue($value, $platform);

        }

        return (string)parent::convertToDatabaseValue($value->value, $platform);
    }

    /**
     * @inheritDoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): DatabaseEnumInterface
    {
        $value = (string)parent::convertToPHPValue($value, $platform);
        $enum = static::$enum::tryFrom($value);

        if ($enum !== null) {
            return $enum;
        }

        throw ConversionException::conversionFailedFormat(
            gettype($value),
            static::$name,
            'One of: "' . implode('", "', static::getValues()) . '"',
        );
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function getName(): string
    {
        return '';
    }
}
