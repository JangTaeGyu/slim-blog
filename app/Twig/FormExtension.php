<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;

class FormExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('form_open', [$this, 'formOpen'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('form_close', [$this, 'formClose'], ['is_safe' => ['html']])
        ];
    }

    public function formOpen(string $method = 'GET', array $attributes = [])
    {
        $method = strtoupper($method);

        $html = sprintf(
            "<form method='%s' %s>",
            in_array($method, ['POST', 'PUT', 'DELETE']) ? 'POST' : $method,
            $this->getAttributeFromArray($attributes)
        );

        if (in_array($method, ['PUT', 'DELETE'])) {
            $html .= sprintf("<input type='hidden' name='_METHOD' value='%s'>", $method);
        }

        return $html;
    }

    public function formClose()
    {
        return "</form>";
    }

    private function getAttributeFromArray(array $attributes = [])
    {
        $htmlParts = [];

        foreach ($attributes as $key => $attribute) {
            array_push($htmlParts, "{$key}='{$attribute}'");
        }

        return implode(' ', $htmlParts);
    }
}
