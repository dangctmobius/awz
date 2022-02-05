<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use NextApps\VerificationCode\VerificationCode;

class VerifyEmailCodeController extends Controller
{
    public function verify($email)
    {   
        VerificationCode::send($email);
    }
}
