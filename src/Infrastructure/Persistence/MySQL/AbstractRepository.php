<?php
declare(strict_types = 1);

namespace App\Infrastructure\Persistence\MySQL;

use PDO;

abstract class AbstractRepository
{
    protected PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}
