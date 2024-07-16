<?php

namespace App\LoadBalancer;

use App\Worker\Worker;
use App\Worker\WorkerInterface;
use Iterator;
use RuntimeException;

class WorkerPool implements Iterator
{
    /** @var WorkerInterface[] */
    private array $pool = [];
    private int $index = 0;

    public function __construct(int $workersCount)
    {
        if (1 > $workersCount) {
            throw new RuntimeException('Insufficient number of workers');
        }
        for ($count = 0; $count < $workersCount; $count++) {
            $this->pool[] = new Worker();
        }
    }

    public function current(): WorkerInterface
    {
        return $this->pool[$this->index];
    }

    public function next(): void
    {
        ++$this->index;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return isset($this->pool[$this->index]);
    }

    public function rewind(): void
    {
        $this->index = 0;
    }
}
