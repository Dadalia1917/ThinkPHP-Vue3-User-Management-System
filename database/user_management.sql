-- 用户管理系统数据库脚本（简化版）
-- 创建时间：2024年
-- 数据库：MySQL 8.0+

-- 创建数据库
CREATE DATABASE IF NOT EXISTS `user_management` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `user_management`;

-- 删除表（如果存在）
DROP TABLE IF EXISTS `user`;

-- 创建用户表
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `email` varchar(100) NOT NULL COMMENT '邮箱',
  `password` varchar(255) NOT NULL COMMENT '密码（加密）',
  `nickname` varchar(50) NOT NULL COMMENT '昵称',
  `avatar` varchar(255) DEFAULT '' COMMENT '头像URL',
  `mobile` varchar(20) DEFAULT '' COMMENT '手机号',
  `status` tinyint(1) DEFAULT 1 COMMENT '状态：0=禁用，1=正常',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `delete_time` datetime DEFAULT NULL COMMENT '软删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户表';

-- 插入示例数据（使用明文密码）
INSERT INTO `user` (`id`, `username`, `email`, `password`, `nickname`, `avatar`, `mobile`, `status`, `last_login_time`, `create_time`, `update_time`) VALUES
(1, 'admin', 'admin@example.com', 'admin123', '系统管理员', '', '13800138000', 1, '2024-01-15 10:30:00', '2024-01-01 00:00:00', '2024-01-15 10:30:00'),
(2, 'test', 'test@example.com', 'password', '测试用户', '', '13800138001', 1, '2024-01-14 15:20:00', '2024-01-02 08:00:00', '2024-01-14 15:20:00'),
(3, 'user001', 'user001@example.com', 'password', '用户001', '', '13800138002', 1, '2024-01-13 09:45:00', '2024-01-03 12:30:00', '2024-01-13 09:45:00'),
(4, 'user002', 'user002@example.com', 'password', '用户002', '', '13800138003', 0, NULL, '2024-01-04 16:45:00', '2024-01-04 16:45:00'),
(5, 'user003', 'user003@example.com', 'password', '用户003', '', '13800138004', 1, '2024-01-12 14:15:00', '2024-01-05 11:20:00', '2024-01-12 14:15:00');

-- 数据库初始化完成
-- 注意：
-- 管理员账号: admin, 密码: admin123
-- 测试账号: test, 密码: password
-- 其他账号: user001/user002/user003, 密码: password
