<?php


namespace App\Libraries;


use Illuminate\Support\Facades\Redis;

class Jwt

{
    public $header = null;
    public $payload = null;
    public $signature = null;
    /**
     *  生成一个 JSON Web Token
     *
     * @param string $uid 用户ID
     * @param array $other
     * @return bool|string
     *
     * @author KuanZhang
     * @time 2020/7/21
     */
    public function getToken(string $uid, array $other = [])
    {
        if (!$uid)
            return false;

        $algo = $this->getAlgo();
        $header = $this->makeHeader($algo);
        $payload = $this->makePayload($uid, $other);
        $signature = $this->makeSignature($header, $payload, $algo);

        $token = $header . '.' . $payload . '.' . $signature;
        $this->rememberRedisToken($uid, $token);
        return $token;
    }

    /**
     * 随机获取一个哈希算法名称， hash_hmac()的第一个参数
     *
     * @return string
     *
     * @author KuanZhang
     * @time 2020/7/21
     */
    public function getAlgo()
    {
        $algo_arr = hash_hmac_algos();

        return $algo_arr[array_rand($algo_arr)];
    }

    public function makeHeader($algo)
    {
        $arr = [
            'alg' => $algo,
            'typ' => 'JWT'
        ];

        return $this->base64UrlEncode(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    public function makePayload(string $uid, array $other = [])
    {
        $arr = [
            'uid' => $uid,
            'iat' => time() + config('jwt.ttl', 86400)
        ];

        if ($other) {
            $arr = array_merge($arr, $other);
        }

        return $this->base64UrlEncode(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    public function makeSignature($header, $payload, $algo)
    {
        $secret = config('app.key', null);

        return $this->base64UrlEncode(hash_hmac($algo, $header . '.' . $payload, $secret, true));
    }

    /**
     * base64UrlEncode   https://jwt.io/  中base64UrlEncode编码实现
     * @param string $input 需要编码的字符串
     * @return string
     */
    public function base64UrlEncode(string $input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    /**
     * base64UrlEncode  https://jwt.io/  中base64UrlEncode解码实现
     * @param string $input 需要解码的字符串
     * @return bool|string
     */
    public function base64UrlDecode(string $input)
    {
        $remainder = strlen($input) % 4;

        if ($remainder) {
            $addlen = 4 - $remainder;
            $input .= str_repeat('=', $addlen);
        }

        return base64_decode(strtr($input, '-_', '+/'));
    }

    public function verify(string $token)
    {
        $token_arr = explode('.', $token);
        $this->header = $token_arr[0];
        $this->payload = $token_arr[1];
        $this->signature = $token_arr[2];
        // 验证token格式
        if (count($token_arr) != 3) {
            return false;
        }

        // 验证加密算法
        $header = json_decode($this->base64UrlDecode($this->header), JSON_OBJECT_AS_ARRAY);
        if (empty($header['alg'])) {
//            if (app()->environment('local')) error( 'alg error!');
            return false;
        }

        // 验证 uid,iat
        $payload = json_decode($this->base64UrlDecode($this->payload), JSON_OBJECT_AS_ARRAY);
        if (empty($payload['uid']) || empty($payload['iat']) || $payload['iat'] > (time() + config('jwt.ttl', 86400))) {
//            if (app()->environment('local')) error( 'uid,iat error!');
            return false;
        }

        // 验证 加密规则
        if ($this->signature != $this->makeSignature($this->header, $this->payload, $header['alg'])) {
//            if (app()->environment('local')) error( '加密规则 error!');
            return false;
        }

        $have_token = $this->checkRedisToken($payload['uid'], $token);
        if ($have_token === false) {
//            if (app()->environment('local')) error( 'checkRedisToken error!');
            return false;
        }
        // 验证 token 有效期
        if (($payload['iat'] + config('jwt.ttl')) < time()) {
            // 在换签有效期内
            if ($payload['iat'] + config('jwt.ttl') + config('jwt.flu') < time()) {
                // 换签
                $new_token = $this->getToken($payload['uid']);
                header('Authorization:' . $new_token);
                return true;
            }
//            if (app()->environment('local')) error( 'checkRedisToken error!');

            return false;
        }

        return true;
    }

    /**
     *  检查Redis是否存在该用户token
     *
     * @param $uid
     * @param $token
     * @return bool
     */
    protected function checkRedisToken($uid, $token)
    {
        $key = "sso:" . md5($uid);
        $redis = Redis::connection('sso');
        $res = $redis->get($key);

        if ($token != $res) {
            return false;
        }

        return true;
    }

    /**
     * 更新或记录用户token
     *
     * @param $uid
     * @param $token
     *
     * @author KuanZhang
     * @time 2020/8/19
     */
    protected function rememberRedisToken($uid, $token)
    {
        $key = "sso:" . md5($uid);
        $redis = Redis::connection('sso');
        $redis->set($key, $token);
    }

}
