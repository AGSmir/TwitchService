<?php
declare(strict_types=1);

namespace App\Domain\Account\Exception;

use DomainException;

final class AccountException extends DomainException
{
    public static function invalidTwitchId(): self
    {
        return new self('Invalid Twitch ID');
    }

    public static function invalidEmail(): self
    {
        return new self('Invalid email');
    }

    public static function invalidRole(): self
    {
        return new self('Invalid role');
    }

    public static function invalidLogin(): self
    {
        return new self('Invalid login');
    }

    public static function invalidDisplayName(): self
    {
        return new self('Invalid Display name');
    }
}
