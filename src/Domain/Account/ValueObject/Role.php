<?php
declare(strict_types=1);

namespace App\Domain\Account\ValueObject;

use App\Domain\Account\Exception\AccountException;

final class Role
{
    private const USER  = 'user';
    private const ADMIN = 'admin';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $value): self
    {
        return match ($value) {
            self::USER  => self::user(),
            self::ADMIN => self::admin(),
            default     => throw AccountException::invalidRole(),
        };
    }

    public static function user(): self
    {
        return new self(self::USER);
    }

    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    public function isAdmin(): bool
    {
        return $this->value === self::ADMIN;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
