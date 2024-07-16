# LoadBalancer

## Solution description

Solution was divided in two separate modules representing LoadBalancer and Worker instances. 

## Requirements and Installation

The solution was prepared as PHP 8.1 Symfony 6.4 application. To install

```
    git clone https://github.com/marcin-kondera/load-balancer
    composer install 
```

## Usage

As a presentation of the solution Symfony command was introduced. You can learn more about command options by displaying help message: 

```
    php bin:console app:run -h
```

### Usage examples

#### Running RoundRobin algorithm

```
    php bin:console app:run RoundRobin
```

#### Running Adaptive algorithm

```
    php bin:console app:run Adaptive
```

#### Changing worker pool

```
    php bin:console app:run -w 100
```

#### Changing request count

```
    php bin:console app:run -r 1000
```