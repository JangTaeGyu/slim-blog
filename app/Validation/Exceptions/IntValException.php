<?php
namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\IntValException as RespectIntValException;

class IntValException extends RespectIntValException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}}은(는) 숫자여야 합니다.',
        ]
    ];
}
