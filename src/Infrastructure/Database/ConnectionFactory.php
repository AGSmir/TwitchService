<?php
declare(strict_types=1);

namespace App\Infrastructure\Database;

use PDO;
use PDOException;

class ConnectionFactory
{
    public function create(): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_CONFIG['host'], DB_CONFIG['database'], DB_CONFIG['charset']
        );

        try {
            return new PDO(
                $dsn,
                DB_CONFIG['user'],
                DB_CONFIG['password'],
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
