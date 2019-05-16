<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Psr\Container\ContainerInterface;

class CsrfExtension extends AbstractExtension
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('csrf_input', [$this, 'csrfInput'], ['is_safe' => ['html']])
        ];
    }

    public function csrfInput()
    {
        $csrf = $this->container->get('csrf');

        return "
            <input type='hidden' name='{$csrf->getTokenNameKey()}' value='{$csrf->getTokenName()}' />
            <input type='hidden' name='{$csrf->getTokenValueKey()}' value='{$csrf->getTokenValue()}' />
        ";
    }
}
