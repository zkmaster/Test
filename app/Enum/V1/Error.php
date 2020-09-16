<?php


namespace App\Enum\V1;


class Error
{
// region 1
    const TEXT_ERROR = 10000;
// endregion

// region 2
    const NO_LOGIN = 20000;
    const AUTH_ERROR = 20001;
    const TOKEN_ERROR = 20002;
    const USER_NOT_FOUND = 20010;
// endregion

// region 2
    const VALIDATE_ERROR = 30000;
    const NOT_FOUND = 30010;
// endregion


}
