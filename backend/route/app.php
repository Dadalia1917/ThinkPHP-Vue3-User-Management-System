<?php
// +----------------------------------------------------------------------
// | 应用路由设置
// +----------------------------------------------------------------------

use think\facade\Route;

// API路由组
Route::group('api', function () {
    
    // 用户相关路由
    Route::group('user', function () {
        // 注册
        Route::post('register', 'User/register');
        // 登录
        Route::post('login', 'User/login');
        // 获取用户信息（需要登录）
        Route::get('info', 'User/info');
        // 用户列表
        Route::get('list', 'User/list');
        // 更新用户信息（需要登录）
        Route::put('update', 'User/update');
        // 修改密码（需要登录）
        Route::post('change-password', 'User/changePassword');
    });

    // 公共路由
    Route::get('test', function () {
        return json([
            'code' => 200,
            'message' => 'API服务正常',
            'data' => [
                'version' => '1.0.0',
                'time' => date('Y-m-d H:i:s')
            ]
        ]);
    });

    // 数据库连接测试
    Route::get('test-db', function () {
        try {
            $db = \think\facade\Db::connect();
            $users = $db->table('user')->count();
            return json([
                'code' => 200,
                'message' => '数据库连接正常',
                'data' => [
                    'database' => 'user_management',
                    'user_count' => $users,
                    'time' => date('Y-m-d H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'message' => '数据库连接失败：' . $e->getMessage(),
                'data' => null
            ]);
        }
    });

    // 登录测试（GET方式，仅用于调试）
    Route::get('test-login', function () {
        try {
            $user = \think\facade\Db::table('user')
                ->where('username', 'admin')
                ->find();
            
            if ($user) {
                $passwordOk = ($user['password'] === 'admin123');
                return json([
                    'code' => 200,
                    'message' => '登录测试',
                    'data' => [
                        'user_found' => true,
                        'password_verified' => $passwordOk,
                        'username' => $user['username'],
                        'user_id' => $user['id'],
                        'status' => $user['status'],
                        'stored_password' => $user['password']
                    ]
                ]);
            } else {
                return json([
                    'code' => 404,
                    'message' => '用户不存在',
                    'data' => null
                ]);
            }
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'message' => '登录测试失败：' . $e->getMessage(),
                'data' => null
            ]);
        }
    });

    // 简化登录API（POST方式）
    Route::post('simple-login', function () {
        try {
            $request = request();
            $account = $request->post('account');
            $password = $request->post('password');
            
            if (empty($account) || empty($password)) {
                return json([
                    'code' => 400,
                    'message' => '账号和密码不能为空',
                    'data' => null
                ]);
            }
            
            $user = \think\facade\Db::table('user')
                ->where('username', $account)
                ->whereOr('email', $account)
                ->where('status', 1)
                ->find();
            
            if (!$user) {
                return json([
                    'code' => 404,
                    'message' => '用户不存在',
                    'data' => null
                ]);
            }
            
            if ($user['password'] !== $password) {
                return json([
                    'code' => 401,
                    'message' => '密码错误',
                    'data' => null
                ]);
            }
            
            // 简单的token（实际项目中应该用JWT）
            $token = 'simple_token_' . $user['id'] . '_' . time();
            
            // 更新登录时间
            \think\facade\Db::table('user')
                ->where('id', $user['id'])
                ->update(['last_login_time' => date('Y-m-d H:i:s')]);
            
            return json([
                'code' => 200,
                'message' => '登录成功',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'nickname' => $user['nickname'],
                        'avatar' => $user['avatar'],
                        'mobile' => $user['mobile']
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'message' => '登录失败：' . $e->getMessage(),
                'data' => null
            ]);
        }
    });

})->middleware(\app\middleware\Cors::class);

// 默认路由
Route::get('/', function () {
    return json([
        'code' => 200,
        'message' => '用户管理系统API',
        'data' => [
            'name' => 'User Management System',
            'version' => '1.0.0',
            'author' => 'Developer',
            'endpoints' => [
                'POST /api/user/register' => '用户注册',
                'POST /api/user/login' => '用户登录',
                'GET /api/user/info' => '获取用户信息',
                'GET /api/user/list' => '用户列表',
                'PUT /api/user/update' => '更新用户信息',
                'POST /api/user/change-password' => '修改密码',
            ]
        ]
    ]);
});
