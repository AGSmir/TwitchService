<?php

namespace App\Repository\Account;

use App\DataClass\Account\Account;
use Exception;
use PDO;
use PDOException;

class AccountRepository implements AccountRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @throws Exception
     */
    public function getByTwitchId(string $twitchId): ?Account
    {
        $sql = "SELECT * FROM accounts WHERE twitch_id = :twitch_id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['twitch_id' => $twitchId]);
        $result = $stmt->fetch();
        if ($result) {
            return Account::fromArray($result);
        }
        return null;
    }

    /**
     * @throws Exception
     */
    public function getById(int $id): ?Account
    {
        $sql = "SELECT * FROM accounts WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch();
        if ($result) {
            return Account::fromArray($result);
        }
        return null;
    }

    /**
     * @param Account $account
     * @return void
     * @throws PDOException
     */
    public function save(Account $account): void
    {
        $sql = "INSERT INTO accounts (
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
            ':twitch_id' => $account->twitchId,
            ':login' => $account->login,
            ':display_name' => $account->displayName,
            ':email' => $account->email,
            ':avatar_url' => $account->avatarUrl,
            ':access_token' => $account->accessToken,
            ':refresh_token' => $account->refreshToken,
            ':token_expires_at' => $account->tokenExpiresAt
        ]);
    }

    public function existedByTwitchId(string $twitchId): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT 1 FROM accounts WHERE twitch_id = :twitch_id"
        );
        $stmt->execute(['twitch_id' => $twitchId]);

        return (bool)$stmt->fetchColumn();
    }


    /**
     * @param string $login
     * @return Account|null
     * @throws PDOException
     * @throws Exception
     */
    public function findByLogin(string $login): ?Account
    {
        $sql = "SELECT * FROM accounts WHERE login = :login LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['login' => $login]);
        $result = $stmt->fetch();
        if ($result) {
            return Account::fromArray($result);
        }
        return null;
    }
}
