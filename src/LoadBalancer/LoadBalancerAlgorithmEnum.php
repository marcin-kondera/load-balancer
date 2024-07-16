<?php

namespace App\LoadBalancer;

enum LoadBalancerAlgorithmEnum
{
    case RoundRobin;
    case Adaptive;
}
