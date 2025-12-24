<?php
declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use App\Domain\Account\Exception\AccountException;

final class Email
{
    private string $value;

    private function __construct(string $value)
    {
        $value = mb_strtolower(trim($value));

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw AccountException::invalidEmail();
        }

        $this->value = $value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
