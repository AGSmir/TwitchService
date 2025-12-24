<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Exception;

use RuntimeException;
use Throwable;

final class InvalidDatabaseRowException extends RuntimeException
{
    public static function missingField(string $field, array $row): self
    {
        return new self(
            sprintf(
                'Missing required DB field "%s". Row: %s',
                $field,
                json_encode($row, JSON_THROW_ON_ERROR)
            )
        );
    }

    public static function invalidValue(
        mixed $value,
        ?Throwable $previous = null
    ): self {
        return new self(
            sprintf(
                'Invalid value for DB field: %s',
                var_export($value, true)
            ),
            0,
            $previous
        );
    }
}
