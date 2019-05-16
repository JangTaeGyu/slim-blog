<?php
namespace App\Validation\Rules;

use Respect\Validation\Rules\IntVal as RespectIntVal;

class IntVal extends RespectIntVal
{
    public function validate($input)
    {
        return parent::validate($input);
    }
}
