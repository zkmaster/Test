<?php


namespace App\Libraries;

use App\Enum\Table;
use App\Enum\V1\Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class Auth

{
    private static $instance = null;
    protected $init = false;

    public $uid = null;
    /**
     * @var null|User
     */
    public $user = null;
    public $role = null;
    /**
     * @var Request
     */
    public $request = null;
    /**
     * @var Jwt
     */
    protected $jwt;
    /**
     * @var Route
     */
    protected $route;
    /**
     *  获取实例
     *
     * @return Auth|null
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 不允许从外部调用， 防止被创建多个实例
     */
    private function __construct()
    {
    }

    /**
     * 初始化Auth
     *
     * @param Request $request
     *
     * @author KuanZhang
     * @time 2020/8/20
     */
    public function init(Request $request)
    {

    }

    /**
     * 防止实例被克隆
     */
    private function __clone()
    {
    }

    /**
     * 防止反序列化
     */
    private function __wakeup()
    {
    }

    /**
     * 身份认证
     *
     * @param $request
     * @return bool
     * @author KuanZhang
     * @time 2020/8/20
     */
    public function authenticate(Request $request)
    {
        $this->request = $request;
        $route_info = $this->request->route()[1];
        $route_info['path'] = $request->getPathInfo();
        $this->jwt = new Jwt();

        $this->route = Route::getInstance();
        $this->route->init($route_info);

        # 验证 Token
        $res = $this->checkToken();
        if ($res !== true) error(Error::TOKEN_ERROR);
        # 验证 路由权限
        $this->checkRoute();
        # 删除多余属性
        $this->clear();
        return true;
    }

    protected function initUser()
    {
        $user_info = $this->getUserInfo();
        if ($user_info) {
            $this->user = $user_info;
        }else {
            error(Error::USER_NOT_FOUND);
        }
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    protected function fromAltHeaders()
    {
        return $this->request->server->get('HTTP_AUTHORIZATION') ?: $this->request->server->get('REDIRECT_HTTP_AUTHORIZATION');
    }

    /**
     * 获取全局用户信息
     *
     * @return User|null
     *
     * @author KuanZhang
     * @time 2020/8/19
     */
    protected function getUserInfo()
    {
        if ($this->uid != null) {
            $user_model = DB::table(Table::B_USER)
                ->where('uid', $this->uid)
                ->select('uid', 'mobile', 'email', 'status','level')
                ->first();
            if ($user_model) {
                return new User($user_model->uid, $user_model->mobile, $user_model->email, $user_model->level);
            }else{
                return null;
            }
        }else {
            return null;
        }
    }

    /**
     * 解析并验证Token
     *
     * @return bool
     *
     * @author KuanZhang
     * @time 2020/8/20
     */
    protected function checkToken()
    {
        # 解析获取Token
        $authorization = $this->request->headers->get('Authorization') ?: $this->fromAltHeaders();
        if ($authorization && preg_match('/Bearer'. '\s*(\S+)\b/i', $authorization, $matches)) {
            $token = $matches[1] ?? false;
            if ($token) {
                # 验证Token内容
                if ($this->jwt->verify($token) === false) {
                    return false;
                }
            } else {
                # 如果没有获取到Token
                error('NO TOKEN');
            }

        }else {
            return false;
        }
        return true;
    }


    protected function checkRoute()
    {
        $this->route->check();
        $payload = json_decode($this->jwt->base64UrlDecode($this->jwt->payload), JSON_OBJECT_AS_ARRAY);
        # 设置用户唯一标识
        $this->uid = $payload['uid'];
        # 设置用户信息
        $this->initUser();
        return true;
    }

    protected function clear()
    {
        $this->request = null;
        $this->jwt = null;
        $this->route = null;
    }
}
