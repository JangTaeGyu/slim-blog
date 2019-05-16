<?php
namespace App\Validation\Rules;

use Respect\Validation\Rules\Equals as RespectEquals;

class Equals extends RespectEquals
{
    public function validate($input)
    {
        return parent::validate($input);
    }
}
