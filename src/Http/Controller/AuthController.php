<?php
declare(strict_types=1);

namespace App\Http\Controller;

use App\DataClass\Account\Account;
use App\Lib\Twitch\TwitchApiClient;
use App\Services\AccountService;
use JetBrains\PhpStorm\NoReturn;
use RuntimeException;
use Throwable;

readonly class AuthController
{
    public function __construct(
        private TwitchApiClient $twitchApi,
        private AccountService  $accountService
    )
    {
    }

    #[NoReturn]
    public function login(): void
    {
        $url = $this->twitchApi->getAuthUrl();
        header('Location: ' . $url);
        exit;
    }

    public function callback(): void
    {
        $code = $_GET['code'] ?? null;
        if (!$code) {
            die('Error: Missing authorization code');
        }

        try {
            $tokens = $this->twitchApi->exchangeCodeForToken($code);
            $accessToken = $tokens['access_token'];
            $refreshToken = $tokens['refresh_token'];
            $expiresIn = $tokens['expires_in'];
            $tokenExpiresAt = date('Y-m-d H:i:s', time() + $expiresIn);

            $userInfo = $this->twitchApi->getUserInfo($accessToken);
            $account = $this->accountService->findByLogin($userInfo['login']);
            $accountData = $userInfo;
            if ($account === null) {
                $accountData['access_token'] = $accessToken;
                $accountData['refresh_token'] = $refreshToken;
                $accountData['token_expires_at'] = $tokenExpiresAt;
                $account = Account::fromArray($accountData);
                $this->accountService->addAccount($account);
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $account->id;
            $_SESSION['user_login'] = $account->login;

            header('Location: /');
            exit;

        } catch (RuntimeException $e) {
            die('Auth Error: ' . $e->getMessage());
        } catch (Throwable $e) {
            die('System Error: ' . $e->getMessage());
        }
    }
}
