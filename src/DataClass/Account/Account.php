<?php
declare(strict_types=1);

namespace App\DataClass\Account;

use DateTime;
use DomainException;
use Exception;

final readonly class Account
{
    private const USER = 'user';
    private const ADMIN = 'admin';

    private function __construct(
        public string   $twitchId,
        public string   $login,
        public string   $displayName,
        public string   $email,
        public string   $avatarUrl,
        public string   $role,
        public string   $accessToken,
        public string   $refreshToken,
        public DateTime $tokenExpiresAt,
        public ?int     $id = null,
    ) {}

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): self
    {
        self::validate($data);
        return new self(
            twitchId: $data['twitch_id'],
            login: $data['login'],
            displayName: $data['display_name'],
            email: $data['email'] ?? null,
            avatarUrl: $data['avatar_url'],
            role: $data['role'],
            accessToken: $data['access_token'],
            refreshToken: $data['refresh_token'],
            tokenExpiresAt: new DateTime($data['token_expires_at']),
            id: (int)$data['id']
        );
    }

    private static function validate(array $data): void
    {
        if (empty($data['display_name'])) {
            throw new DomainException('Invalid Display name');
        }

        if (empty($data['twitch_id'])) {
            throw new DomainException('Invalid Twitch ID');
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new DomainException('Invalid email');
        }

        match ($data['role']) {
            self::USER, self::ADMIN => null,
            default => throw new DomainException('Invalid role'),
        };
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ADMIN;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
