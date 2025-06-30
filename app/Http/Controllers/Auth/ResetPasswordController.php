<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    // untuk Reset Password Controller
    use ResetsPasswords;

    /**
     * @var string
     */
    protected $redirectTo = '/home';
}
