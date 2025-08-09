# 基于ThinkPHP和Vue3的用户管理系统

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue.svg)](https://www.php.net/)
[![Vue Version](https://img.shields.io/badge/Vue-3.3.8-green.svg)](https://vuejs.org/)
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

## 🎯 项目介绍

这是一个专为面试展示设计的现代化全栈Web应用系统，采用前后端分离架构，展现了当前主流技术栈的最佳实践。

### 🚀 技术栈

**前端技术**
- **Vue 3.3.8**: 采用Composition API
- **Element Plus**: 企业级UI组件库
- **Pinia**: 新一代状态管理
- **Vue Router 4**: 路由管理
- **Axios**: HTTP客户端
- **Vite**: 现代化构建工具

**后端技术**
- **ThinkPHP 8.0**: 现代化PHP框架
- **PHP 8.0+**: 最新PHP特性
- **JWT**: 身份认证
- **MySQL 8.0**: 数据库存储

**开发工具**
- **ESLint + Prettier**: 代码规范
- **SCSS**: CSS预处理器
- **Git**: 版本控制

## ✨ 功能特性

### 🔐 用户系统
- [x] 用户注册（用户名/邮箱验证）
- [x] 用户登录（JWT身份认证）
- [x] 个人资料管理
- [x] 密码修改
- [x] 用户状态管理

### 📊 管理功能
- [x] 用户列表展示
- [x] 用户搜索/筛选
- [x] 分页查询
- [x] 数据统计

### 🎨 界面设计
- [x] 响应式布局
- [x] 现代化UI设计
- [x] 移动端适配
- [x] 暗色主题支持

### 🛡️ 安全特性
- [x] JWT身份验证
- [x] 密码加密存储
- [x] XSS防护
- [x] CSRF防护
- [x] SQL注入防护

## 🎪 在线演示

### 演示地址
- **前端应用**: http://localhost:3000
- **后端API**: http://localhost:8000

### 测试账号
| 角色 | 用户名 | 密码 | 描述 |
|------|--------|------|------|
| 管理员 | `admin` | `admin123` | 完整权限 |
| 普通用户 | `test` | `password` | 基础权限 |

## 🚀 快速开始

### 🔧 环境要求

- **PHP**: 8.0+
- **Node.js**: 16.0+
- **MySQL**: 8.0+
- **Composer**: 2.0+

### 📖 手动安装

1. **克隆项目**
   ```bash
   git clone <repository-url>
   cd 基于ThinkPHP和Vue3的用户管理系统
   ```

2. **数据库配置**
   ```bash
   # 使用Navicat或命令行导入数据库
   mysql -u root -p123456 < database/user_management.sql
   ```

3. **后端配置**
   ```bash
   cd backend
   composer install
   # 配置数据库连接
   php -S localhost:8000 -t public
   ```

4. **前端配置**
   ```bash
   cd frontend
   npm install
   npm run dev
   ```

## 📁 项目结构

```
基于ThinkPHP和Vue3的用户管理系统/
├── 📂 backend/                 # ThinkPHP后端
│   ├── 📂 app/                # 应用代码
│   │   ├── 📂 controller/     # 控制器
│   │   ├── 📂 model/         # 数据模型
│   │   └── 📂 middleware/    # 中间件
│   ├── 📂 config/            # 配置文件
│   ├── 📂 public/            # 入口文件
│   └── 📂 route/             # 路由定义
├── 📂 frontend/               # Vue3前端
│   ├── 📂 src/               # 源代码
│   │   ├── 📂 api/          # API接口
│   │   ├── 📂 components/   # 公共组件
│   │   ├── 📂 views/        # 页面组件
│   │   ├── 📂 router/       # 路由配置
│   │   ├── 📂 stores/       # 状态管理
│   │   └── 📂 styles/       # 样式文件
│   └── 📂 public/            # 静态资源
├── 📂 uniapp/                # UniApp移动端项目
├── 📂 database/              # 数据库相关
├── 📂 docs/                  # 文档说明
└── 📋 README.md             # 项目说明
```

## 🌐 API 文档

### 用户认证
```http
POST /api/user/register    # 用户注册
POST /api/user/login       # 用户登录
GET  /api/user/info        # 获取用户信息
PUT  /api/user/update      # 更新用户信息
POST /api/user/change-password  # 修改密码
```

### 用户管理
```http
GET /api/user/list         # 用户列表
```

## 📱 移动端支持

项目支持使用 **UniApp** 打包为多端应用：

- 📱 Android APP
- 🍎 iOS APP  
- 🔍 微信小程序
- 💻 H5应用

详见：[UniApp打包指南](docs/UniApp打包指南.md)

## 📚 文档

- [🔧 环境要求](docs/环境要求.md)
- [💻 VSCode运行指南](docs/VSCode运行指南.md)
- [📱 UniApp打包指南](docs/UniApp打包指南.md)
- [📊 项目总览](项目总览.md)

## 🎯 面试亮点

### 技术能力展示
- ✅ **全栈开发**: 前后端完整实现
- ✅ **现代化技术**: Vue3 + ThinkPHP8
- ✅ **工程化**: 规范的项目结构
- ✅ **安全意识**: 多层安全防护
- ✅ **用户体验**: 响应式设计

### 代码质量
- ✅ **代码规范**: ESLint + 详细注释
- ✅ **模块化**: 组件化开发
- ✅ **可维护**: 清晰的架构设计
- ✅ **可扩展**: 易于功能扩展

## 🤝 贡献

欢迎提交 Issue 和 Pull Request 来改进项目。

## 📄 许可证

本项目基于 [MIT](LICENSE) 许可证开源。

## 🙏 致谢

感谢以下开源项目：
- [Vue.js](https://vuejs.org/)
- [Element Plus](https://element-plus.org/)
- [ThinkPHP](https://www.thinkphp.cn/)

---

**🎉 本项目专为面试展示设计，展现了现代Web开发的最佳实践！**
