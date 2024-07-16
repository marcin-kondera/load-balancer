<?php

namespace App\LoadBalancer;

use Symfony\Component\HttpFoundation\Request;

class LoadBalancer implements LoadBalancerInterface
{
    private const LOAD_LIMIT = 0.75;
    private WorkerPool $pool;
    private LoadBalancerAlgorithmEnum $algorithm;

    public function __construct(WorkerPool $workerPool, LoadBalancerAlgorithmEnum $algorithm)
    {
        $this->pool = $workerPool;
        $this->algorithm = $algorithm;
    }

    public function handleRequest(Request $request): void
    {
        match ($this->algorithm) {
            LoadBalancerAlgorithmEnum::Adaptive => $this->handleAdaptive($request),
            LoadBalancerAlgorithmEnum::RoundRobin => $this->handleRoundRobin($request)
        };
    }

    protected  function handleAdaptive(Request $request): void
    {
        $lowestLoad = null;
        $indicatedWorker = null;

        foreach ($this->pool as $worker) {
            $workerLoad = $worker->getLoad();

            if ($workerLoad < self::LOAD_LIMIT) {
                $indicatedWorker = $worker;
                break;
            } elseif (is_null($lowestLoad) || $lowestLoad > $workerLoad) {
                $lowestLoad = $workerLoad;
                $indicatedWorker = $worker;
            }
        }

        $indicatedWorker->handleRequest($request);
    }

    protected function handleRoundRobin(Request $request): void
    {
        $indicatedWorker = $this->pool->current();
        $this->pool->next();
        if (!$this->pool->valid()) {
            $this->pool->rewind();
        }

        $indicatedWorker->handleRequest($request);
    }
}