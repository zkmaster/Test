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
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
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
     * @param int|null $status_code
     * @param string|null $message
     * @param Throwable|null $previous
     * @param array $headers
     * @author KuanZhang
     * @time 2020/9/3 13:20
     */
    function error(int $status_code = null, string $message = null, \Throwable $previous = null, array $headers = [])
    {
        if ($status_code === null) {
            $status_code = \App\Enum\V1\Error::TEXT_ERROR;
        } else {
            if ($message === null) $message = config('error' . $status_code) ?: '未知错误0，请及时反馈~';
        }

        Throw new App\Exceptions\ApiException($status_code, $message, $previous, $headers);
    }

}

if (!function_exists('validateError')) {

    /**
     * validateError
     * @param string $message
     * @author KuanZhang
     * @time 2020/9/3 13:21
     */
    function validateError(string $message)
    {
        error(\App\Enum\V1\Error::VALIDATE_ERROR, $message);
    }

}



