<?php

namespace App\Command;

use App\LoadBalancer\LoadBalancerAlgorithmEnum;
use App\LoadBalancer\LoadBalancer;
use App\LoadBalancer\WorkerPool;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

class AppCommand extends Command
{
    private const BALANCING_ALGORITHM = 'balancingAlgorithm';
    private const BALANCING_ALGORITHM_DEFAULT = 'RoundRobin';
    private const WORKERS_COUNT = 'workersCount';
    private const WORKERS_DEFAULT = 10;
    private const REQUESTS_COUNT = 'requestsCount';
    private const REQUESTS_DEFAULT = 100;

    protected function configure(): void
    {
        $this
            ->setName('app:run')
            ->setDescription('Presentation of the solution')
            ->addArgument(
                self::BALANCING_ALGORITHM,
                InputArgument::OPTIONAL,
                'Set Load balancer algorithm. Allowed values: \'Adaptive\', \'RoundRobin\'.',
                self::BALANCING_ALGORITHM_DEFAULT
            )
            ->addOption(
                self::WORKERS_COUNT,
                'w',
                InputOption::VALUE_REQUIRED,
                'Number of workers to create.',
                self::WORKERS_DEFAULT
            )
            ->addOption(
                self::REQUESTS_COUNT,
                'r',
                InputOption::VALUE_REQUIRED,
                'Number of request to be processed.',
                self::REQUESTS_DEFAULT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $workersCount = $input->getOption(self::WORKERS_COUNT);
        $requestsCount = $input->getOption(self::REQUESTS_COUNT);

        switch ($input->getArgument(self::BALANCING_ALGORITHM)) {
            case 'Adaptive':
                $loadBalancer = new LoadBalancer(
                    new WorkerPool($workersCount),
                    LoadBalancerAlgorithmEnum::Adaptive
                );
                break;
            case 'RoundRobin':
                $loadBalancer = new LoadBalancer(
                    new WorkerPool($workersCount),
                    LoadBalancerAlgorithmEnum::RoundRobin
                );
                break;
            default: throw new RuntimeException('Unknown Load Balancer algorithm');
        }

        for ($i = 0; $i < $requestsCount; $i++) {
            $loadBalancer->handleRequest(new Request());
            if (0 == ($i + 1) % $workersCount) {
                $output->writeln(sprintf('Requests sent: %d', ($i + 1)));
            }
        }

        return 0;
    }
}
