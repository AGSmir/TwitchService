<?php
declare(strict_types=1);

namespace App\Infrastructure\Database;

final readonly class DatabaseConfig
{
    public function __construct(
        private string $host,
        private string $database,
        private string $user,
        private string $password,
        private string $charset = 'utf8mb4',
    ) {}

    public function getHost(): string
    {
        return $this->host;
    }
    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCharset(): string
    {
        return $this->charset;
    }
}
