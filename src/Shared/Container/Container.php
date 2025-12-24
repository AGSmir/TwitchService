<?php
declare(strict_types=1);

namespace App\Shared\Container;


use Psr\Container\ContainerInterface;
use RuntimeException;

final class Container implements ContainerInterface
{
    private array $definitions = [];
    private array $instances = [];

    public function set(string $id, callable $factory): void
    {
        $this->definitions[$id] = $factory;
    }

    public function has(string $id): bool
    {
        return isset($this->definitions[$id]) || isset($this->instances[$id]);
    }

    public function get(string $id): mixed
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }
        if (!isset($this->definitions[$id])) {
            throw new RuntimeException("Service $id not found in container");
        }

        $this->instances[$id] = ($this->definitions[$id])($this);

        return $this->instances[$id];
    }
}
