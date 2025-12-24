<?php

namespace App\Infrastructure\Persistence\MySQL\Account;

use App\Domain\Account\Account;
use App\Domain\Account\AccountRepositoryInterface;
use App\Domain\Shared\ValueObject\TwitchId;
use App\Infrastructure\Persistence\MySQL\AbstractRepository;

class AccountRepository extends AbstractRepository implements AccountRepositoryInterface
{
    private string $table = 'accounts';

    public function getByTwitchId(TwitchId $twitchId): ?Account
    {
        $sql = "SELECT * FROM $this->table WHERE twitch_id = :twitch_id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['twitch_id' => $twitchId->value()]);
        $result = $stmt->fetch();
        if ($result) {
            return AccountMapper::fromRow($result);
        }
        return null;
    }

    public function getById(int $id): ?Account
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch();
        if ($result) {
            return AccountMapper::fromRow($result);
        }
        return null;
    }

    public function save(Account $account): void
    {
        $sql = "INSERT INTO $this->table (
            twitch_id,
            login,
            display_name,
            email,
            avatar_url,
            access_token,
            refresh_token,
            token_expires_at
        ) VALUES (
            :twitch_id,
            :login,
            :display_name,
            :email,
            :avatar_url,
            :access_token,
            :refresh_token,
            :token_expires_at
        )
        ON DUPLICATE KEY UPDATE
            login = VALUES(login),
            display_name = VALUES(display_name),
            email = VALUES(email),
            avatar_url = VALUES(avatar_url),
            access_token = VALUES(access_token),
            refresh_token = VALUES(refresh_token),
            token_expires_at = VALUES(token_expires_at)
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':twitch_id' => $account->twitchId()->value(),
            ':login' => $account->login()->value(),
            ':display_name' => $account->displayName()->value(),
            ':email' =>$account->email()->value(),
            ':avatar_url' => $account->avatarUrl(),
            ':access_token' => $account->accessToken(),
            ':refresh_token' => $account->refreshToken(),
            ':token_expires_at' =>  $account->tokenExpiresAt()->format('Y-m-d H:i:s')
        ]);
    }

    public function existedByTwitchId(TwitchId $twitchId): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT 1 FROM $this->table WHERE twitch_id = :twitch_id"
        );
        $stmt->execute(['twitch_id' => $twitchId->value()]);

        return (bool)$stmt->fetchColumn();
    }
}
