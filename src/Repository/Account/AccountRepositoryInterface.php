<?php
declare(strict_types=1);

namespace App\Repository\Account;


use App\DataClass\Account\Account;
use PDOException;

interface AccountRepositoryInterface
{
    public function getById(int $id): ?Account;

    public function getByTwitchId(string $twitchId): ?Account;

    /**
     * @param Account $account
     * @return void
     * @throws PDOException
     */
    public function save(Account $account): void;

    public function existedByTwitchId(string $twitchId): bool;

    /**
     * @param string $login
     * @return Account|null
     * @throws PDOException
     */
    public function findByLogin(string $login): ?Account;
}
