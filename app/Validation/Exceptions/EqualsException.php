<?php
namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\EqualsException as RespectEqualsException;

class EqualsException extends RespectEqualsException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}}은 {{compareTo}}와 같아야합니다.',
        ]
    ];
}
