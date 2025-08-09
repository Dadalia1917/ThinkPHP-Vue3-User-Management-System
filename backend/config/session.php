<?php

return [
    // session name
    'name'           => 'PHPSESSID',
    // SESSION_ID的提交变量,解决flash上传跨域
    'var_session_id' => '',
    // 驱动方式 支持file cache
    'type'           => 'file',
    // 存储连接标识 当type使用cache的时候有效
    'store'          => null,
    // 过期时间
    'expire'         => 1440,
    // 前缀
    'prefix'         => '',
    // 是否自动开启 SESSION
    'auto_start'     => true,
    // httponly设置
    'httponly'       => true,
    // 是否使用 use_strict_mode
    'use_strict_mode' => false,
    // 是否使用 use_cookies
    'use_cookies'     => true,
    // 是否使用 use_only_cookies
    'use_only_cookies' => true,
    // 是否使用 use_trans_sid
    'use_trans_sid'   => false,
    // SESSION 保存路径
    'save_path'       => '',
    // SESSION 垃圾回收概率 [1, 1000]
    'gc_probability'  => 1,
    // SESSION 垃圾回收除数 [1, 1000]
    'gc_divisor'      => 1000,
    // SESSION 垃圾回收最大生存时间
    'gc_maxlifetime'  => 1440,
    // session 域名
    'cookie_domain'   => '',
    // session 路径
    'cookie_path'     => '/',
    // session 安全传输
    'cookie_secure'   => false,
    // httponly设置
    'cookie_httponly' => '',
    // 是否使用 cookie_lifetime
    'cookie_lifetime' => 0,
];
