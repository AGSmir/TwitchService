<?php
declare(strict_types=1);

namespace App\Database;

final readonly class DatabaseConfig
{
    public function __construct(
        public string $host,
        public string $database,
        public string $user,
        public string $password,
        public string $charset = 'utf8mb4',
    ) {}
}
