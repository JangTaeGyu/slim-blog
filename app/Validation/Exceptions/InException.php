<?php
namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\InException as RespectInException;

class InException extends RespectInException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}}은 {{haystack}}에 있어야합니다.',
        ]
    ];
}
