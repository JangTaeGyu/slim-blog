<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;

class Controller
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __get($property)
    {
        if ($this->container->has($property)) {
            return $this->container->get($property);
        }

        throw new Exception("No matching containers found.");
    }
}
