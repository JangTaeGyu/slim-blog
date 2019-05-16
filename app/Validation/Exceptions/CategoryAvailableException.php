<?php
namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class CategoryAvailableException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '이미 등록된 카테고리 입니다.'
        ]
    ];
}
