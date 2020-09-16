<?php


namespace App\Http\Controllers\V1\Common;


use App\Enum\V1\Error;
use App\Http\Controllers\V1\BaseController;
use App\Http\Process\V1\Common\UserProcess;
use App\Libraries\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{

    public function __construct()
    {
        $this->process = new UserProcess();
    }

    public function getInfo()
    {
        $auth = Auth::getInstance();
        $user = $this->process->getOne($auth->uid);
        if ($user) {
            return successResponse($user);
        }else {
            error(Error::NOT_FOUND);
        }
    }

}
