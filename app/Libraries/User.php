<?php


namespace App\Libraries;


class User

{
    public $uid = '';
    public $mobile = '';
    public $email = '';
    public $status = 0;
    public $level = 0;

    public $other = [];

    public function __construct(string $uid = null, string $mobile = null, string $email = null, $status = null, int $level = null, array $other = [])
    {
        if (!is_null($uid)) $this->uid = $uid;
        if (!is_null($mobile)) $this->mobile = $mobile;
        if (!is_null($email)) $this->email = $email;
        if (!is_null($level)) $this->level = $level;
        if (!is_null($status)) $this->level = $status;
        if (!is_null($other)) $this->other = $other;
    }

    public function setUid(string $value)
    {
        $this->uid = $value;
    }

    public function setMobile(string $value)
    {
        $this->mobile = $value;
    }

    public function setEmail(string $value)
    {
        $this->email = $value;
    }

    public function setLevel(int $value)
    {
        $this->level = $value;
    }

    public function setStatus(int $value)
    {
        $this->status = $value;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function __set($name, $value)
    {
        $this->other[$name] = $value;
    }

    public function __get($name)
    {
        $other = $this->other;
        if (isset($other[$name])) {
            return $other[$name];
        }else {
            return null;
        }
    }
}
