<?php

namespace app\controller;

use app\model\User as UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use think\exception\ValidateException;

/**
 * 用户控制器
 */
class User extends BaseController
{
    // JWT密钥
    private $jwtKey = 'user_management_jwt_key_2024';

    /**
     * 用户注册
     */
    public function register()
    {
        // 验证参数
        $rules = [
            'username' => ['required' => true, 'type' => 'string'],
            'email' => ['required' => true, 'type' => 'email'],
            'password' => ['required' => true, 'type' => 'string'],
            'nickname' => ['required' => true, 'type' => 'string'],
        ];

        $data = $this->validate($rules);
        if (!$data) {
            return $this->error('参数验证失败');
        }

        // 检查用户名是否存在
        if (UserModel::usernameExists($data['username'])) {
            return $this->error('用户名已存在');
        }

        // 检查邮箱是否存在
        if (UserModel::emailExists($data['email'])) {
            return $this->error('邮箱已存在');
        }

        // 密码长度验证
        if (strlen($data['password']) < 6) {
            return $this->error('密码长度不能少于6位');
        }

        try {
            // 创建用户
            $user = UserModel::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => $data['password'],
                'nickname' => $data['nickname'],
                'status' => 1,
                'avatar' => '',
                'mobile' => $data['mobile'] ?? '',
            ]);

            return $this->success([
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'nickname' => $user->nickname,
            ], '注册成功');

        } catch (\Exception $e) {
            return $this->error('注册失败：' . $e->getMessage());
        }
    }

    /**
     * 用户登录
     */
    public function login()
    {
        // 验证参数
        $rules = [
            'account' => ['required' => true, 'type' => 'string'],
            'password' => ['required' => true, 'type' => 'string'],
        ];

        $data = $this->validate($rules);
        if (!$data) {
            return $this->error('参数验证失败');
        }

        // 查找用户
        $user = UserModel::findByAccount($data['account']);
        if (!$user) {
            return $this->error('用户不存在');
        }

        // 验证密码
        if (!UserModel::verifyPassword($data['password'], $user->getData('password'))) {
            return $this->error('密码错误');
        }

        // 检查用户状态
        if ($user->status != 1) {
            return $this->error('账号已被禁用');
        }

        // 生成JWT令牌
        $payload = [
            'user_id' => $user->id,
            'username' => $user->username,
            'exp' => time() + 7 * 24 * 3600, // 7天过期
        ];

        $token = JWT::encode($payload, $this->jwtKey, 'HS256');

        // 更新最后登录时间
        $user->save(['last_login_time' => date('Y-m-d H:i:s')]);

        return $this->success([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'nickname' => $user->nickname,
                'avatar' => $user->avatar,
                'mobile' => $user->mobile,
            ]
        ], '登录成功');
    }

    /**
     * 获取用户信息
     */
    public function info()
    {
        $user = $this->getCurrentUser();
        if (!$user) {
            return $this->error('请先登录', 401);
        }

        return $this->success([
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'nickname' => $user->nickname,
            'avatar' => $user->avatar,
            'mobile' => $user->mobile,
            'status' => $user->status,
            'create_time' => $user->create_time,
            'last_login_time' => $user->last_login_time,
        ]);
    }

    /**
     * 用户列表
     */
    public function list()
    {
        $page = $this->request->param('page', 1);
        $limit = $this->request->param('limit', 10);
        $keyword = $this->request->param('keyword', '');
        $status = $this->request->param('status');

        $where = [];
        if ($keyword) {
            $where['keyword'] = $keyword;
        }
        if ($status !== null && $status !== '') {
            $where['status'] = $status;
        }

        $result = UserModel::getList($where, $page, $limit);

        return $this->success($result);
    }

    /**
     * 更新用户信息
     */
    public function update()
    {
        $user = $this->getCurrentUser();
        if (!$user) {
            return $this->error('请先登录', 401);
        }

        $data = $this->request->only(['nickname', 'email', 'mobile', 'avatar']);

        // 邮箱唯一性检查
        if (!empty($data['email']) && UserModel::emailExists($data['email'], $user->id)) {
            return $this->error('邮箱已存在');
        }

        // 手机号格式验证
        if (!empty($data['mobile']) && !preg_match('/^1[3-9]\d{9}$/', $data['mobile'])) {
            return $this->error('手机号格式不正确');
        }

        try {
            $user->save($data);
            return $this->success(['user' => $user], '更新成功');
        } catch (\Exception $e) {
            return $this->error('更新失败：' . $e->getMessage());
        }
    }

    /**
     * 修改密码
     */
    public function changePassword()
    {
        $user = $this->getCurrentUser();
        if (!$user) {
            return $this->error('请先登录', 401);
        }

        $oldPassword = $this->request->param('old_password');
        $newPassword = $this->request->param('new_password');

        if (empty($oldPassword) || empty($newPassword)) {
            return $this->error('参数不完整');
        }

        // 验证原密码
        if (!UserModel::verifyPassword($oldPassword, $user->getData('password'))) {
            return $this->error('原密码错误');
        }

        // 新密码长度验证
        if (strlen($newPassword) < 6) {
            return $this->error('新密码长度不能少于6位');
        }

        try {
            $user->save(['password' => $newPassword]);
            return $this->success([], '密码修改成功');
        } catch (\Exception $e) {
            return $this->error('密码修改失败：' . $e->getMessage());
        }
    }

    /**
     * 获取当前登录用户
     * @return UserModel|null
     */
    private function getCurrentUser()
    {
        $token = $this->request->header('Authorization');
        if (empty($token)) {
            return null;
        }

        // 移除Bearer前缀
        $token = str_replace('Bearer ', '', $token);

        try {
            $decoded = JWT::decode($token, new Key($this->jwtKey, 'HS256'));
            $user = UserModel::find($decoded->user_id);
            return $user && $user->status == 1 ? $user : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
