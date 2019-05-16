<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Psr\Container\ContainerInterface;

class SessionExtension extends AbstractExtension implements GlobalsInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getGlobals()
    {
        return [
            'session' => $this->container->get('session')
        ];
    }
}
