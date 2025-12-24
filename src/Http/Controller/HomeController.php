<?php
declare(strict_types=1);

namespace App\Http\Controller;

class HomeController
{
    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $streamers = [
            [
                'name' => 'Streamer_1',
                'title' => 'Title_1',
                'viewers' => 10000,
                'is_live' => true,
                'category' => 'Just Chatting',
            ],
            [
                'name' => 'Streamer_2',
                'title' => 'Title_2',
                'viewers' => 5000,
                'is_live' => true,
                'category' => 'Counter Strike 2',
            ],
            [
                'name' => 'Streamer_3',
                'title' => 'Title_1',
                'viewers' => 1000,
                'is_live' => false,
                'category' => 'Dota 2',
            ],
        ];

        $user = [];
        if (isset($_SESSION['user_id'])) {
            $user = [
                'login' => $_SESSION['user_login'],
                'id' => $_SESSION['user_id']
            ];
        }

        ob_start();
        if (!isset($_SESSION['user_id'])) { ?>
            <a href="/auth/login">Login</a>
        <?php } else { ?>
            <a href="/logout">Logout</a>
        <?php }
        $viewData = ['user' => $user, 'streamers' => $streamers];
        echo '<pre>'; print_r($viewData); echo '</pre>';
        echo ob_get_clean();
    }
}
