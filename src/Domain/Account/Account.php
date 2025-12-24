<?php
declare(strict_types=1);

namespace App\Domain\Account;

use App\Domain\Account\ValueObject\AccessToken;
use App\Domain\Account\ValueObject\AvatarUrl;
use App\Domain\Shared\ValueObject\DisplayName;
use App\Domain\Shared\ValueObject\Login;
use App\Domain\Account\ValueObject\RefreshToken;
use App\Domain\Shared\ValueObject\TwitchId;
use App\Domain\Shared\ValueObject\Email;
use App\Domain\Account\ValueObject\Role;
use DateTimeImmutable;

final readonly class Account
{
    private function __construct(
        private TwitchId $twitchId,
        private Login $login,
        private DisplayName $displayName,
        private Email $email,
        private AvatarUrl $avatarUrl,
        private Role $role,
        private AccessToken $accessToken,
        private RefreshToken $refreshToken,
        private DateTimeImmutable $tokenExpiresAt,
        private ?int $id = null,
    ) {}

    public static function register(
        TwitchId $twitchId,
        Login $login,
        DisplayName $displayName,
        Email $email,
        AvatarUrl $avatarUrl,
        AccessToken $accessToken,
        RefreshToken $refreshToken,
        DateTimeImmutable $tokenExpiresAt
    ): self {
        return new self(
            twitchId: $twitchId,
            login: $login,
            displayName: $displayName,
            email: $email,
            avatarUrl: $avatarUrl,
            role: Role::user(),
            accessToken: $accessToken,
            refreshToken: $refreshToken,
            tokenExpiresAt: $tokenExpiresAt
        );
    }

    public static function restore(
        int $id,
        TwitchId $twitchId,
        Login $login,
        DisplayName $displayName,
        Email $email,
        AvatarUrl $avatarUrl,
        Role $role,
        AccessToken $accessToken,
        RefreshToken $refreshToken,
        DateTimeImmutable $tokenExpiresAt
    ): self {
        return new self(
            twitchId: $twitchId,
            login: $login,
            displayName: $displayName,
            email: $email,
            avatarUrl: $avatarUrl,
            role: $role,
            accessToken: $accessToken,
            refreshToken: $refreshToken,
            tokenExpiresAt: $tokenExpiresAt,
            id: $id
        );
    }

    public function withUpdatedFields(
        Email $email,
        Login $login,
        DisplayName $displayName,
        AvatarUrl $avatarUrl,
        AccessToken $accessToken,
        RefreshToken $refreshToken,
        DateTimeImmutable $tokenExpiresAt
    ): self {
        return new self(
            twitchId: $this->twitchId,
            login: $login,
            displayName: $displayName,
            email: $email,
            avatarUrl: $avatarUrl,
            role: $this->role,
            accessToken: $accessToken,
            refreshToken: $refreshToken,
            tokenExpiresAt: $tokenExpiresAt,
            id: $this->id
        );
    }

    public function hasId(): bool
    {
        return $this->id !== null;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function twitchId(): TwitchId
    {
        return $this->twitchId;
    }

    public function login(): Login
    {
        return $this->login;
    }

    public function displayName(): DisplayName
    {
        return $this->displayName;
    }

    public function email(): ?Email
    {
        return $this->email;
    }

    public function role(): Role
    {
        return $this->role;
    }

    public function avatarUrl(): AvatarUrl
    {
        return $this->avatarUrl;
    }

    public function accessToken(): AccessToken
    {
        return $this->accessToken;
    }

    public function refreshToken(): RefreshToken
    {
        return $this->refreshToken;
    }

    public function tokenExpiresAt(): DateTimeImmutable
    {
        return $this->tokenExpiresAt;
    }
}
