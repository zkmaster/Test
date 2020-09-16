<?php


namespace App\Http\Controllers\V1\Common;

use App\Http\Controllers\V1\BaseController;
use App\Http\Process\V1\Common\AccountProcess;
use App\Rules\PlainText;
use Illuminate\Http\Request;

class AccountController extends BaseController
{
    protected $process;
    public function __construct()
    {
        $this->process = new AccountProcess();
    }

    //region 注册
    public function emailRegister(Request $request)
    {
        $input_data = validate($request->all(), [
            'email' => 'required|email|unique:b_user,email',
            'password' => 'required|string|min:6|max:16',
        ]);
        $email = $input_data['email'];
        $password = $input_data['password'];
        $this->process->emailRegister($email, $password);
        return successResponse();
    }

    //endregion

    //region 登录
    /**
     * accountLogin
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author KuanZhang
     * @time 2020/9/15-18:17:50
     */
    public function accountLogin(Request $request)
    {
        $rules = [
            'account' => ['required', new PlainText()],
            'password' => 'required|string',
        ];

        $input_data = validate($request->post(), $rules);
        $account = $input_data['account'];
        $password = $input_data['password'];
        $res = $this->process->accountLogin($account, $password);
        header('Authorization:'. $res);
        return successResponse();
    }
    //endregion
}
