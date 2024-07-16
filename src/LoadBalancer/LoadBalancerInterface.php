<?php

namespace App\LoadBalancer;

use Symfony\Component\HttpFoundation\Request;

interface LoadBalancerInterface
{
    public function __construct(WorkerPool $workerPool, LoadBalancerAlgorithmEnum $algorithm);
    public function handleRequest(Request $request): void;
}
