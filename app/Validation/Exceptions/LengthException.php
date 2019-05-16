<?php
namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\LengthException as RespectLengthException;

class LengthException extends RespectLengthException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::BOTH => '{{name}}의 길이는 {{minValue}} ~ {{maxValue}} 사이 여야합니다.',
            self::LOWER => '{{name}}의 길이는 {{minValue}}보다 커야합니다.',
            self::GREATER => '{{name}}의 길이는 {{maxValue}}보다 작아야합니다.',
        ]
    ];
}
