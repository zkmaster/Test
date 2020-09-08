<?php


namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PlainText implements Rule
{

    /**
     * 判断验证规则是否通过
     *
     * regex:
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^[\w-.\x{4e00}-\x{9fa5}]*$/u', $value) ? true : false;
    }

    /**
     * 获取验证错误消息
     *
     * @return string|array
     */
    public function message()
    {
        return ':attribute 只能包含中文、英文字母、-、_、.';
    }
}
