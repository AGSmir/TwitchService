<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\MySQL\Account;

use App\Domain\Account\Account;
use App\Domain\Account\ValueObject\AccessToken;
use App\Domain\Account\ValueObject\AvatarUrl;
use App\Domain\Account\ValueObject\RefreshToken;
use App\Domain\Account\ValueObject\Role;
use App\Domain\Shared\ValueObject\TwitchId;
use App\Domain\Shared\ValueObject\Login;
use App\Domain\Shared\ValueObject\DisplayName;
use App\Domain\Shared\ValueObject\Email;
use App\Infrastructure\Persistence\Exception\InvalidDatabaseRowException;
use DateTimeImmutable;
use Throwable;

final class AccountMapper
{
    public static function fromRow(array $row): Account
    {
        return Account::restore(
            id: (int)$row['id'],
            twitchId: TwitchId::fromString($row['twitch_id']),
            login: Login::fromString($row['login']),
            displayName: DisplayName::fromString($row['display_name']),
            email: isset($row['email']) ? Email::fromString($row['email']) : null,
            avatarUrl: AvatarUrl::fromString($row['avatar_url']),
            role: Role::fromString($row['role']),
            accessToken: AccessToken::fromString($row['access_token']),
            refreshToken: RefreshToken::fromString($row['refresh_token']),
            tokenExpiresAt: self::toDate($row['token_expires_at'])
        );
    }

    private static function toDate(string $value): DateTimeImmutable
    {
        try {
            return new DateTimeImmutable($value);
        } catch (Throwable $e) {
            throw InvalidDatabaseRowException::invalidValue(
                $value,
                $e
            );
        }
    }
}
