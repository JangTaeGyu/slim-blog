<?php
namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\User;

class EmailAvailable extends AbstractRule
{
    public function validate($input)
    {
        if ($input === '') {
            return false;
        }

        return User::where('email', $input)->count() === 0;
    }
}
