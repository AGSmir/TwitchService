<?php
declare(strict_types=1);

namespace App\Repository\Channel;

use App\DataClass\Channel\Channel;
use PDOException;

interface ChannelRepositoryInterface
{
    public function getById(int $id): ?Channel;

    public function getByTwitchId(string $twitchId): ?Channel;

    /**
     * @param Channel $channel
     * @return void
     * @throws PDOException
     */
    public function save(Channel $channel): void;

    public function existedByTwitchId(string $twitchId): bool;

    /**
     * @param string $login
     * @return Channel|null
     * @throws PDOException
     */
    public function findByLogin(string $login): ?Channel;
}
