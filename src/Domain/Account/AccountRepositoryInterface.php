<?php
declare(strict_types=1);

namespace App\Domain\Account;

use App\Domain\Shared\ValueObject\TwitchId;

interface AccountRepositoryInterface
{
    public function getById(int $id): ?Account;

    public function getByTwitchId(TwitchId $twitchId): ?Account;

    public function save(Account $account): void;

    public function existedByTwitchId(TwitchId $twitchId): bool;
}
