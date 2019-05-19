<?php
namespace App\Validation\Rules;

use Respect\Validation\Rules\In as RespectIn;

class In extends RespectIn
{
    public function __construct($haystack, $compareIdentical = false)
    {
        parent::__construct($haystack, $compareIdentical);
    }
}
