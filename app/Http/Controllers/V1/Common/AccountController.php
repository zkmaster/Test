<?php


namespace App\Http\Controllers\V1\Common;


use App\Http\Controllers\V1\V1BaseController;
use Illuminate\Http\Request;

class AccountController extends V1BaseController
{
    public function loginIn(Request $request)
    {
        dd($request->all());
    }
}
