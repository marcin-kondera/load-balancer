<?php

namespace App\Worker;

use Symfony\Component\HttpFoundation\Request;

interface WorkerInterface
{
    public function getLoad(): float;
    public function handleRequest(Request $request): void;
}
