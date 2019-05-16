<?php
namespace App\Validation\Rules;

use Respect\Validation\Rules\NotEmpty as RespectNotEmpty;

class NotEmpty extends RespectNotEmpty
{
    public function validate($input)
    {
        return parent::validate($input);
    }
}
