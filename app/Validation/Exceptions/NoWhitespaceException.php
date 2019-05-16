<?php
namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\NoWhitespaceException as RespectNoWhitespaceException;

class NoWhitespaceException extends RespectNoWhitespaceException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}}에는 공백이 없어야합니다.',
        ]
    ];
}
