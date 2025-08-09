<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 路由器文件，用于 PHP 内置服务器

if (!defined('__ROOT__')) {
    define('__ROOT__', dirname(__DIR__));
}

$_SERVER["SCRIPT_FILENAME"] = __DIR__ . '/index.php';

// 获取请求的 URI
$requestUri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// 静态文件处理
if ($requestUri !== '/' && file_exists(__DIR__ . $requestUri)) {
    return false; // 让服务器处理静态文件
}

// 所有其他请求都重定向到 index.php
require __DIR__ . '/index.php';
