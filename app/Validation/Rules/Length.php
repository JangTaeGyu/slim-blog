<?php
namespace App\Validation\Rules;

use Respect\Validation\Rules\Length as RespectLength;

class Length extends RespectLength
{
    public function validate($input)
    {
        return parent::validate($input);
    }
}
