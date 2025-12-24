<?php
declare(strict_types = 1);

namespace App\Application\Account\UseCase;

use App\Application\Account\Dto\TwitchAuthCommand;
use App\Application\Account\Dto\TwitchAuthResult;
use App\Application\Account\Exception\TwitchAuthException;
use App\Domain\Account\Account;
use App\Domain\Account\AccountRepositoryInterface;
use App\Domain\Account\ValueObject\AccessToken;
use App\Domain\Account\ValueObject\AvatarUrl;
use App\Domain\Shared\ValueObject\DisplayName;
use App\Domain\Shared\ValueObject\Email;
use App\Domain\Shared\ValueObject\Login;
use App\Domain\Account\ValueObject\RefreshToken;
use App\Domain\Shared\ValueObject\TwitchId;
use DateTimeImmutable;
use Throwable;

final readonly class TwitchAuth
{
    public function __construct(
        private AccountRepositoryInterface $accounts
    ) {}

    public function execute(TwitchAuthCommand $command): TwitchAuthResult
    {
        try {
            $twitchId = TwitchId::fromString($command->twitchId);

            if ($this->accounts->existedByTwitchId($twitchId)) {
                $account = $this->accounts->getByTwitchId($twitchId);
                $isNew = false;

                $account = $account->withUpdatedFields(
                    Email::fromString($command->email),
                    Login::fromString($command->login),
                    DisplayName::fromString($command->displayName),
                    AvatarUrl::fromString($command->avatarUrl),
                    AccessToken::fromString($command->accessToken),
                    RefreshToken::fromString($command->refreshToken),
                    new DateTimeImmutable($command->tokenExpiresAt)
                );
            } else {
                $account = Account::register(
                    twitchId: TwitchId::fromString($command->twitchId),
                    login: Login::fromString($command->login),
                    displayName: DisplayName::fromString($command->displayName),
                    email: $command->email
                        ? Email::fromString($command->email)
                        : null,
                    avatarUrl: AvatarUrl::fromString($command->avatarUrl),
                    accessToken: AccessToken::fromString($command->accessToken),
                    refreshToken: RefreshToken::fromString($command->refreshToken),
                    tokenExpiresAt: new DateTimeImmutable($command->tokenExpiresAt),
                );
                $isNew = true;
            }

            $this->accounts->save($account);

            return new TwitchAuthResult($account, $isNew);
        } catch (Throwable $e) {
            throw new TwitchAuthException('Account auth failed', $e);
        }
    }
}
