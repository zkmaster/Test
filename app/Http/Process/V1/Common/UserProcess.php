<?php


namespace App\Http\Process\V1\Common;


use Illuminate\Support\Facades\DB;

class UserProcess
{
    public function getOne($uid)
    {
        $user =  DB::table('b_user')
            ->where('uid', $uid)
            ->select('uid', 'mobile', 'email', 'level', 'nick_name', 'avatar', 'gender', 'motto')
            ->first();
        return $user ? objToArray($user) : [];
    }
}
