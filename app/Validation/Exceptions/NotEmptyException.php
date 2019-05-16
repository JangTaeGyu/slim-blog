<?php
namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\NotEmptyException as RespectNotEmptyException;

class NotEmptyException extends RespectNotEmptyException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::NAMED => '{{name}} 필드는 필수입니다.',
        ]
    ];
}
