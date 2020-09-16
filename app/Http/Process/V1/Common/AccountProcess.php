<?php


namespace App\Http\Process\V1\Common;


use App\Libraries\Jwt;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enum\Table;

class AccountProcess
{
    //region 登录
    public function accountLogin(string $account, string $password)
    {
        $user = DB::table(Table::B_USER)->where(function($query) use ($account) {
            $query->where('email', $account)
                ->orWhere('mobile', $account);
        })->select([
            'uid',
            'password',
            'salt'
        ])->sharedLock()->first();
        if (is_null($user)) {
            error("ACCOUNT_NOT_FOUND");
        }else {
            list($decrypt_password, $salt) = self::decryptPassword($user->password);
            if($decrypt_password == $password && $salt == $user->salt) {
                return (new Jwt())->getToken($user->uid);
            }else {
                error('账户或密码有误！');
            }
        }
    }

    //endregion

    //region 注册
    public function emailRegister(string $email, string $password)
    {
        $salt = Str::random(4);
        $user_data = [
            'uid' => Str::uuid()->toString(),
            'email' => $email,
            'salt' => $salt,
            'password' => self::encryptPassword($password, $salt),
            'status' => 0, # 未激活
        ];
        $res = DB::table(Table::B_USER)->insertGetId($user_data);
        if ($res === false) {
            error("ACCOUNT_NOT_FOUND");
        }
    }
    //endregion

    /**
     *  用户密码加密
     *
     * @param string $pass 密码明文
     * @param string $salt 密码盐
     * @return string
     *
     * @author KuanZhang
     * @time 2020/7/21
     */
    public static function encryptPassword($pass, $salt)
    {
        $pass = $pass . '.' . $salt;
        return Crypt::encrypt($pass);
    }

    /**
     * 用户密码解密
     *
     * @param $password
     * @return string 密码明文
     *
     * @author KuanZhang
     * @time 2020/7/21
     */
    /**
     * 用户密码解密
     *
     * @param string $password
     * @return array [密码明文, 加密盐]
     *
     * @author KuanZhang
     * @time 2020/7/21
     */
    public static function decryptPassword($password)
    {
        $str = Crypt::decrypt($password);
        $index = strrpos($str, '.');
        if ($index) {
            $pass = substr($str, 0, $index);
            $salt = substr($str, $index + 1);
        }else {
            $pass = '';
            $salt = '';
        }
        return [$pass, $salt];
    }
}
