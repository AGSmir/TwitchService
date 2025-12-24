<?php
declare(strict_types = 1);

namespace App\Application\Account\Dto;

use App\Domain\Account\Account;

final readonly class TwitchAuthResult
{
    public function __construct(
        public Account $account,
        public bool $isNew,
    ) {}
}
