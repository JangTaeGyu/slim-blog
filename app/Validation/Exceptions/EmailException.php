<?php
namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\EmailException as RespectEmailException;

class EmailException extends RespectEmailException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}}은(는) 유효한 이메일 주소여야 합니다.',
        ]
    ];
}
