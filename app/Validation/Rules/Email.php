<?php
namespace App\Validation\Rules;

use Respect\Validation\Rules\Email as RespectEmail;

class Email extends RespectEmail
{
    public function validate($input)
    {
        return parent::validate($input);
    }
}
