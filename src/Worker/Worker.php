<?php

namespace App\Worker;

use Symfony\Component\HttpFoundation\Request;

class Worker extends AbstractWorker
{
    private ?float $lastLoadValue = null;

    public function getLoad(): float
    {
        $load = rand(0, 100) / 100;
        $this->lastLoadValue = $load;

        return $load;
    }

    public function handleRequest(Request $request): void
    {
    }
}
