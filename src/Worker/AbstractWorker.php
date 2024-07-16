<?php

namespace App\Worker;

abstract class AbstractWorker implements WorkerInterface
{
    private static int $workersCount = 0;
    private int $workerId;

    public function __construct()
    {
        $this->workerId = ++self::$workersCount;
    }

    protected static function getWorkersCount(): int
    {
        return self::$workersCount;
    }

    protected function getWorkerId(): int
    {
        return $this->workerId;
    }
}
