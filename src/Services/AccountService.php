<?php

namespace App\Services;

use App\DataClass\Account\Account;
use App\Repository\Account\AccountRepositoryInterface;

class AccountService
{
    private AccountRepositoryInterface $accountRepository;

    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    function addAccount(Account $account): void
    {
        $this->accountRepository->save($account);
    }

    public function findByLogin(string $login): ?Account
    {
        return $this->accountRepository->findByLogin($login);
    }
}