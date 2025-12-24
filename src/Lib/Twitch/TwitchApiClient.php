<?php
declare(strict_types=1);

namespace App\Lib\Twitch;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;

class TwitchApiClient
{
    private const AUTH_URL = 'https://id.twitch.tv/oauth2/authorize';
    private const TOKEN_URL = 'https://id.twitch.tv/oauth2/token';
    private const HELIX_USERS_URL = 'https://api.twitch.tv/helix/users';

    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $redirectUri,
        private readonly Client $httpClient
    ) {}

    public function getAuthUrl(): string
    {
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'user:read:email',
        ];

        return self::AUTH_URL . '?' . http_build_query($params);
    }

    /**
     * @throws GuzzleException
     */
    public function exchangeCodeForToken(string $code): array
    {
        $response = $this->httpClient->post(self::TOKEN_URL, [
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code' => $code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->redirectUri,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function getUserInfo(string $accessToken): array
    {
        $response = $this->httpClient->get(self::HELIX_USERS_URL, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Client-Id' => $this->clientId,
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['data'][0])) {
            throw new RuntimeException('Failed to fetch user info from Twitch.');
        }

        return $data['data'][0];
    }
}
