<?php
declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOException;

class ConnectionFactory
{
    public function create(DatabaseConfig $config): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $config->host,
            $config->database,
            $config->charset
        );

        try {
            return new PDO(
                $dsn,
                $config->user,
                $config->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
}
