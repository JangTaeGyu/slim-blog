<?php
namespace App\Validation;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;

class Validator
{
    private $container;

    private $errors;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function validate(Request $request, array $rules): self
    {
        foreach ($rules as $field => $rule) {
            try {
                $rule->setName(ucfirst($field))->assert($request->getParam($field));
            } catch (\Respect\Validation\Exceptions\NestedValidationException $e) {
                $this->errors[$field] = $e->getMessages();
            }
        }

        $session = $this->container->get('session');
        $session->set('errors', $this->errors);

        return $this;
    }

    public function failed(): bool
    {
        return !empty($this->errors);
    }
}
