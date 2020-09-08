<?php


namespace App\Http\Controllers\V1\Common;


use App\Http\Controllers\V1\BaseController;
use App\Rules\PlainText;
use Illuminate\Http\Request;

class AccountController extends BaseController
{

    /**
     * loginIn
     * @param Request $request
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Validation\ValidationException
     * @author KuanZhang
     * @time 2020/9/2 13:20
     */
    public function accountLogin(Request $request)
    {
        $rules = [
            'account' => ['required', new PlainText()],
            'password' => 'required|max:50',
            'age' => 'required|string|min:20',
        ];

        $res = validate($request->post(), $rules);
//        $res = preg_match('/[\x{4e00}-\x{9fa5}]+/u', $res['account']);
        dd($res);

    }
}
