<?php

namespace App;

use Request;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function has_username_and_password()
    {
        $username = Request::get('username');
        $password = Request::get('password');

        if ($username && $password)
            return $user = ['username' => $username, 'password'=>$password];
        return false;
    }

    /**
     * 注册API
     * @return array
     */
    public function signup()
    {
        $user = $this->has_username_and_password();

        #检查用户名和密码是否为空
        if (!$user)
            return ['status' => 0, 'msg' => '用户名和密码不可为空'];

        #检查用户名是否存在
        $user_exists = $this->where('username', $user['username'])->exists();
        if ($user_exists)
            return ['status' => 0, 'msg' => '用户名已存在'];

        #加密密码
        $hashed_password = bcrypt($user['password']);

        #存入数据库
        $this->username = $user['username'];
        $this->password = $hashed_password;
        if ($this->save())
            return ['status' => 1, 'msg' => '用户保存成功', 'id' => $this->id];
        else
            return ['status' => 0, 'msg' => '用户保存失败'];

    }

    /**
     * 登录API
     * @return array
     */
    public function login()
    {
        #检查用户名和密码是否为空
        if (!$this->has_username_and_password())
            return ['status' => 0, 'msg' => '用户名和密码不可为空'];
    }
}