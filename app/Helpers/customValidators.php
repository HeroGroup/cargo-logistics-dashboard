<?php
/**
 * Created by IntelliJ IDEA.
 * User: navid
 * Date: 8/27/2019
 * Time: 10:37 AM
 */

namespace App\Helpers;


use Illuminate\Support\Facades\Validator;

class customValidators
{
    public static function passwordValidator(array $data)
    {
        return Validator::make($data, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
}
