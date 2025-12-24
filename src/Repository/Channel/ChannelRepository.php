<?php

namespace App\Repository\Channel;

use App\DataClass\Channel\Channel;
use Exception;
use PDO;
use PDOException;

class ChannelRepository implements ChannelRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @throws Exception
     */
    public function getByTwitchId(string $twitchId): ?Channel
    {
        $sql = "SELECT * FROM channels WHERE twitch_id = :twitch_id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['twitch_id' => $twitchId]);
        $result = $stmt->fetch();
        if ($result) {
            return Channel::fromArray($result);
        }
        return null;
    }

    /**
     * @throws Exception
     */
    public function getById(int $id): ?Channel
    {
        $sql = "SELECT * FROM channels WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch();
        if ($result) {
            return Channel::fromArray($result);
        }
        return null;
    }

    /**
     * @param Channel $channel
     * @return void
     * @throws PDOException
     */
    public function save(Channel $channel): void
    {
        $sql = "INSERT INTO channels (
            twitch_id,
            login,
            display_name,
            is_partner,
            is_affiliate
        ) VALUES (
            :twitch_id,
            :login,
            :display_name,
            :is_partner,
            :is_affiliate
        )
        ON DUPLICATE KEY UPDATE
            login = VALUES(login),
            display_name = VALUES(display_name)
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':twitch_id' => $channel->twitchId,
            ':login' => $channel->login,
            ':display_name' => $channel->displayName,
            ':is_partner' => $channel->displayName,
            ':is_affiliate' => $channel->displayName,
        ]);
    }

    public function existedByTwitchId(string $twitchId): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT 1 FROM channels WHERE twitch_id = :twitch_id"
        );
        $stmt->execute(['twitch_id' => $twitchId]);

        return (bool)$stmt->fetchColumn();
    }


    /**
     * @param string $login
     * @return Channel|null
     * @throws PDOException
     * @throws Exception
     */
    public function findByLogin(string $login): ?Channel
    {
        $sql = "SELECT * FROM channels WHERE login = :login LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['login' => $login]);
        $result = $stmt->fetch();
        if ($result) {
            return Channel::fromArray($result);
        }
        return null;
    }
}
