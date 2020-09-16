<?php

if (!function_exists('validate') ) {
    /**
     * 验证字段并返回验证成功字段
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return array
     * @author KuanZhang
     * @time 2020/9/2
     */
    function validate(array $data, array $rules, array $messages = [], array $customAttributes = []) {
        /**
         * @var $res \Illuminate\Validation\Validator
         */
        $res = Illuminate\Support\Facades\Validator::make($data, $rules, $messages, $customAttributes);
        if ($res->fails()) {
            $errors = $res->errors()->toArray();
            $first_error = $errors ? array_shift($errors)[0] : '输入参数格式有误';
            validateError($first_error);
        }
        # 返回验证字段所需数据
        return app('Illuminate\Http\Request')->only(collect($rules)->keys()->map(function ($rule) {
            return Illuminate\Support\Str::contains($rule, '.') ? explode('.', $rule)[0] : $rule;
        })->unique()->toArray());
    }
}

if (!function_exists('error')) {

    /**
     * error 抛出API自定义异常消息
     * @param mixed $status_code
     * @param string|null $message
     * @param Throwable|null $previous
     * @param array $headers
     * @author KuanZhang
     * @time 2020/9/3 13:20
     */
    function error($status_code = null, string $message = null, \Throwable $previous = null, array $headers = [])
    {
        # 无参数时默认报错
        if ($status_code === null) {
            $status_code = \App\Enum\V1\Error::TEXT_ERROR;
        } else {
            # 无状态码时
            if (!is_numeric($status_code)) {
                $message = is_string($status_code) ? $status_code : config('error.' . \App\Enum\V1\Error::TEXT_ERROR);
                $status_code = \App\Enum\V1\Error::TEXT_ERROR;
            }else {
                # 不传提示内容时返回默认错误内容
                if ($message === null) $message = config('error.' . $status_code) ?: '未知错误0，请及时反馈~';
            }
        }

        Throw new App\Exceptions\ApiException($status_code, $message, $previous, $headers);
    }

}

if (!function_exists('validateError')) {

    /**
     * 字段验证异常
     * @param string $message
     * @author KuanZhang
     * @time 2020/9/3 13:21
     */
    function validateError(string $message)
    {
        error(\App\Enum\V1\Error::VALIDATE_ERROR, $message);
    }

}

if (!function_exists('objToArray')) {

    /**
     * 对象转数组（仅支持简单对象）
     * @param mixed $data
     * @return array
     * @author KuanZhang
     * @time 2020/9/10-15:43:15
     */
    function objToArray($data)
    {
        if (empty($data)) return [];
        return json_decode(json_encode($data), true);
    }

}

if (!function_exists('successResponse')) {

    function successResponse($data = null)
    {
        return response()->json([
            'status_code' => 200,
            'code' => 200,
            'data' => $data,
        ]);
    }

}



