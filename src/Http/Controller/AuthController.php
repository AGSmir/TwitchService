<?php
declare(strict_types=1);

namespace App\Http\Controller;

use App\Application\Account\Dto\TwitchAuthCommand;
use App\Application\Account\UseCase\TwitchAuth;
use App\Infrastructure\Twitch\TwitchApiClient;
use RuntimeException;

readonly class AuthController
{
    public function __construct(
        private TwitchApiClient $twitchApi,
        private TwitchAuth      $twitchAuth
    ) {
    }

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

            $command = new TwitchAuthCommand(
                twitchId: (string) $userInfo['id'],
                login: $userInfo['login'],
                displayName: $userInfo['display_name'],
                email: $userInfo['email'] ?? null,
                avatarUrl: $userInfo['profile_image_url'],
                accessToken: $accessToken,
                refreshToken: $refreshToken,
                tokenExpiresAt: $tokenExpiresAt
            );

            $result = $this->twitchAuth->execute($command);
            $account = $result->account;

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $account->id();
            $_SESSION['user_login'] = $account->login()->value();

            header('Location: /');
            exit;

        } catch (RuntimeException $e) {
            die('Auth Error: ' . $e->getMessage());
        } catch (\Throwable $e) {
            die('System Error: ' . $e->getMessage());
        }
    }
}
