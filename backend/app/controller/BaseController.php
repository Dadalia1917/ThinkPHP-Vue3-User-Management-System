<?php

namespace app\controller;

use think\App;
use think\Response;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
        // 设置跨域响应头
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        
        // 处理OPTIONS请求
        if ($this->request->isOptions()) {
            exit();
        }
    }

    /**
     * 返回成功响应
     * @param mixed $data 数据
     * @param string $message 消息
     * @param int $code 状态码
     * @return Response
     */
    protected function success($data = [], $message = 'success', $code = 200)
    {
        return json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'timestamp' => time()
        ]);
    }

    /**
     * 返回错误响应
     * @param string $message 错误消息
     * @param int $code 错误码
     * @param mixed $data 数据
     * @return Response
     */
    protected function error($message = 'error', $code = 400, $data = [])
    {
        return json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'timestamp' => time()
        ]);
    }

    /**
     * 验证请求参数
     * @param array $rules 验证规则
     * @param array $data 数据
     * @return array|false
     */
    protected function validate($rules, $data = [])
    {
        if (empty($data)) {
            $data = $this->request->param();
        }

        foreach ($rules as $field => $rule) {
            if (isset($rule['required']) && $rule['required'] && !isset($data[$field])) {
                return false;
            }
            
            if (isset($data[$field]) && isset($rule['type'])) {
                switch ($rule['type']) {
                    case 'email':
                        if (!filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                            return false;
                        }
                        break;
                    case 'mobile':
                        if (!preg_match('/^1[3-9]\d{9}$/', $data[$field])) {
                            return false;
                        }
                        break;
                    case 'string':
                        if (!is_string($data[$field])) {
                            return false;
                        }
                        break;
                }
            }
        }

        return $data;
    }
}
