<?php
namespace App\Validation\Rules;

use Respect\Validation\Rules\NoWhitespace as RespectNoWhitespace;

class NoWhitespace extends RespectNoWhitespace
{
    public function validate($input)
    {
        return parent::validate($input);
    }
}
