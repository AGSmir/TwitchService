<?php
declare(strict_types = 1);

namespace App\Application\Account\Dto;

final readonly class TwitchAuthCommand
{
    public function __construct(
        public string $twitchId,
        public string $login,
        public string $displayName,
        public string $email,
        public string $avatarUrl,
        public string $accessToken,
        public string $refreshToken,
        public string $tokenExpiresAt
    ) {}
}
