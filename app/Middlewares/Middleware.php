<?php
namespace App\Middlewares;

use Psr\Container\ContainerInterface;

class Middleware
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
