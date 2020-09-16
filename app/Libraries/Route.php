<?php


namespace App\Libraries;

use App\Enum\Table;
use Illuminate\Redis\Connections\PhpRedisConnection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class Route

{
    # 路由节点状态
    const STATUS_DISABLED = 0; // 禁用
    const STATUS_NORMAL = 1; // 正常
    const STATUS_NOT_OPEN = 2; // 未开放

    # 路由节点类型
    const TYPE_NULL = 0; // 免登陆
    const TYPE_LOGIN_NO_GRANT  = 1; // 登录免授权
    const TYPE_LOGIN_AND_GRANT = 2; // 登录且授权

    private static $instance;
    protected $as;
    protected $route_info;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 初始化路由信息
     * @param array $route_info
     * @author KuanZhang
     * @time 2020/9/15-16:41:07
     */
    public function init(array $route_info)
    {
        $this->route_info = $route_info;
        $this->as = $route_info['as'];
    }

    /**
     * 验证路由
     * @return bool
     * @author KuanZhang
     * @time 2020/9/15-16:40:53
     */
    public function check()
    {
        /**
         * @var $client PhpRedisConnection
         */
        $client = Redis::connection();
        $key = 'Route:alias:' . $this->as;
        $route = $client->get($key);
        if ($route) {
            $route = unserialize($route);
        }
        else {
            # 查询数据库
            $model = DB::table(Table::B_ROUTE)
                ->where('alias', $this->as)
                ->select('id', 'type', 'status')
                ->first();
            if ($model) {
                $route = objToArray($model);
            }
            else {
                # 本地或开发环境路由节点自动入库
                if (app()->environment('local', 'develop')) {
                    $insert_data = [
                        'name' => $this->route_info['desc'] ?? $this->route_info['as'],
                        'path' => $this->route_info['path'],
                        'alias' => $this->route_info['as'],
                        'type' => $this->route_info['type'],
                        'status' => self::STATUS_NORMAL,
                        'created_at' => time(),
                        'updated_at' => time(),
                    ];
                    $id = DB::table(Table::B_ROUTE)
                        ->insertGetId($insert_data);
                    if ($id !== false) {
                        $route = [
                            'id' => $id,
                            'type' => $insert_data['type'],
                            'status' => $insert_data['status']
                        ];
                    }else {
                        return false;
                    }
                }else {
                    return false;
                }

                # 存入缓存
                $client->setex($key, 7200, serialize($route));
            }
        }
        if ($route['status'] != self::STATUS_NORMAL) {
            return false;
        }
        # 如果需要验证权限
        if ($route['type'] == self::TYPE_LOGIN_NO_GRANT) {
            # TODO 验证权限
        }if ($route['type'] == self::TYPE_LOGIN_AND_GRANT) {
            # TODO 验证权限
        }
    }

    /**
     * 返回路由白名单
     *
     * @return array
     *
     * @author KuanZhang
     * @time 2020/8/20
     */
    public static function getWhitelist()
    {
        $client = Redis::connection();
        $key = 'ROUTE:WHITELIST';
        $value = $client->get($key);
        if ($value) {
            return unserialize($value);
        }else {
            $data = DB::table(Table::B_ROUTE)
                ->where('type', self::TYPE_NULL)
                ->where('status', self::STATUS_NORMAL)
                ->pluck('alias')->toArray();
            return $data ? objToArray($data) : [];
        }
    }
}
