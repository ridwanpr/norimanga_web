<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserAccountController extends Controller
{
    public function myAccount()
    {
        return view('user-account.dashboard');
    }
}
