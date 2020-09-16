<?php

namespace App\Http\Middleware;

use App\Enum\V1\Error;
use App\Libraries\Auth;
use App\Libraries\Route;
use Closure;
use Illuminate\Http\Request;

class Access
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        # 路由已经存在
        $route = $request->route()[1];
        $as = $route['as'] ?? false;
        if (!$as) error('路由格式不正确!');

        # 白名单直接跳过验证
        if ($this->checkWhitelist($as)) return $next($request);

        $res = Auth::getInstance()->authenticate($request);
        if ($res !== true) error(Error::AUTH_ERROR);

        return $next($request);
    }

    public function checkWhitelist($as)
    {
        $white_list = Route::getWhitelist();
        if (in_array($as, $white_list)) {
            return true;
        }else {
            return false;
        }
    }

}
