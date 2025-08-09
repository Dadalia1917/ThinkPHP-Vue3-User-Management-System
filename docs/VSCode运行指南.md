# VSCode 运行指南

本指南将帮助您在 VSCode 中运行基于 ThinkPHP 和 Vue3 的用户管理系统。

## 前期准备

### 1. 环境检查

确保您已安装以下软件（具体版本要求请参考 `环境要求.md`）：

```bash
# 检查 PHP 版本
php -v

# 检查 Composer
composer --version

# 检查 Node.js 和 npm
node -v
npm -v

# 检查 MySQL
mysql --version
```

### 2. VSCode 插件安装

推荐安装以下插件：

- **PHP Intelephense** - PHP 智能提示
- **Thunder Client** - API 测试工具
- **Vetur** - Vue.js 支持
- **ESLint** - JavaScript 代码检查
- **Prettier** - 代码格式化
- **GitLens** - Git 增强
- **Auto Rename Tag** - 标签自动重命名

## 数据库设置

### 1. 创建数据库

使用 Navicat 或命令行创建数据库：

**使用命令行：**
```bash
# 连接MySQL (用户名: root, 密码: 123456)
mysql -u root -p123456

# 创建数据库
CREATE DATABASE user_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 退出
exit
```

**使用Navicat：**
1. 打开Navicat
2. 连接到MySQL服务器（用户名: root, 密码: 123456）
3. 右键点击连接 → 新建数据库
4. 数据库名: `user_management`
5. 字符集: `utf8mb4`
6. 排序规则: `utf8mb4_unicode_ci`

### 2. 导入数据库

**在 Navicat 中：**
1. 右键点击 `user_management` 数据库
2. 选择 "运行SQL文件"
3. 选择项目中的 `database/user_management.sql` 文件
4. 点击执行

**使用命令行：**
```bash
mysql -u root -p123456 user_management < database/user_management.sql
```

### 3. 验证数据导入

```sql
-- 查看表结构
USE user_management;
SHOW TABLES;

-- 查看用户数据
SELECT id, username, email, nickname, status FROM user;
```

应该看到包含管理员账号 `admin` 的用户数据。

## 后端配置与运行

### 1. 进入后端目录

```bash
cd backend
```

### 2. 安装 PHP 依赖

```bash
composer install
```

如果遇到网络问题，可以使用国内镜像：

```bash
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
composer install
```

### 3. 配置数据库连接

在后端目录创建 `.env` 文件（或复制 `config.example` 文件）：

```ini
APP_DEBUG = true

[APP]
DEFAULT_TIMEZONE = Asia/Shanghai

[DATABASE]
TYPE = mysql
HOSTNAME = 127.0.0.1
DATABASE = user_management
USERNAME = root
PASSWORD = 123456
HOSTPORT = 3306
CHARSET = utf8mb4
DEBUG = true

[LANG]
default_lang = zh-cn
```

### 4. 启动后端服务

在 VSCode 终端中运行：

```bash
# 方法1：使用 PHP 内置服务器（推荐开发环境）
php -S localhost:8000 -t public

# 方法2：使用 ThinkPHP 命令
php think run
```

成功启动后，您应该看到类似输出：
```
Development Server (http://localhost:8000) started
```

### 5. 测试后端 API

访问：http://localhost:8000

您应该看到 API 响应：
```json
{
  "code": 200,
  "message": "用户管理系统API",
  "data": {
    "name": "User Management System",
    "version": "1.0.0"
  }
}
```

## 前端配置与运行

### 1. 打开新终端

在 VSCode 中按 `Ctrl + Shift + \`` 打开新终端。

### 2. 进入前端目录

```bash
cd frontend
```

### 3. 安装 Node.js 依赖

```bash
npm install
```

如果安装速度慢，可以使用淘宝镜像：

```bash
npm install --registry=https://registry.npmmirror.com
```

### 4. 启动前端开发服务器

```bash
npm run dev
```

成功启动后，您应该看到类似输出：
```
VITE v5.0.0  ready in 1234 ms

➜  Local:   http://localhost:3000/
➜  Network: use --host to expose
➜  press h to show help
```

### 5. 访问应用

打开浏览器访问：http://localhost:3000

## 账号信息

### 测试账号

| 角色 | 用户名 | 密码 | 说明 |
|------|--------|------|------|
| 管理员 | `admin` | `admin123` | 系统管理员账号 |
| 普通用户 | `test` | `password` | 测试用户账号 |
| 普通用户 | `user001` | `password` | 演示用户账号 |

### 数据库配置

- **服务器**: localhost (127.0.0.1)
- **端口**: 3306
- **用户名**: root
- **密码**: 123456
- **数据库**: user_management

## VSCode 调试配置

### 1. PHP 调试配置

创建 `.vscode/launch.json` 文件：

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/var/www/html": "${workspaceFolder}/backend"
            }
        }
    ]
}
```

### 2. 任务配置

创建 `.vscode/tasks.json` 文件：

```json
{
    "version": "2.0.0",
    "tasks": [
        {
            "label": "启动后端服务",
            "type": "shell",
            "command": "php",
            "args": ["-S", "localhost:8000", "-t", "public"],
            "options": {
                "cwd": "${workspaceFolder}/backend"
            },
            "group": "build",
            "presentation": {
                "echo": true,
                "reveal": "always",
                "focus": false,
                "panel": "new"
            }
        },
        {
            "label": "启动前端服务",
            "type": "shell",
            "command": "npm",
            "args": ["run", "dev"],
            "options": {
                "cwd": "${workspaceFolder}/frontend"
            },
            "group": "build",
            "presentation": {
                "echo": true,
                "reveal": "always",
                "focus": false,
                "panel": "new"
            }
        }
    ]
}
```

## 使用说明

### 1. 登录系统

使用预设的测试账号：

- **管理员账号**：
  - 用户名：`admin`
  - 密码：`admin123`

- **普通用户账号**：
  - 用户名：`test`
  - 密码：`password`

### 2. 功能测试

1. **用户注册**：创建新用户账号
2. **用户登录**：使用账号密码登录
3. **个人资料**：修改个人信息和密码
4. **用户管理**：查看用户列表（需要登录）

### 3. API 测试

使用 Thunder Client 插件测试 API：

1. 安装 Thunder Client 插件
2. 创建新请求
3. 测试各个接口：
   - `POST /api/user/login` - 用户登录
   - `GET /api/user/info` - 获取用户信息
   - `GET /api/user/list` - 用户列表

**登录接口测试示例：**
```json
POST http://localhost:8000/api/user/login
Content-Type: application/json

{
  "account": "admin",
  "password": "admin123"
}
```

## 常见问题

### 1. 端口被占用

如果 8000 或 3000 端口被占用，可以修改端口：

```bash
# 后端使用其他端口
php -S localhost:8001 -t public

# 前端修改 vite.config.js 中的 server.port
```

### 2. 数据库连接失败

检查：
- MySQL 服务是否启动
- 数据库账号密码是否正确（root/123456）
- `.env` 文件配置是否正确
- 防火墙是否阻止连接

**启动MySQL服务：**
```bash
# Windows
net start mysql

# Mac/Linux
sudo service mysql start
# 或
sudo systemctl start mysql
```

### 3. Composer 安装失败

尝试：
```bash
# 清除缓存
composer clear-cache

# 使用国内镜像
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

# 重新安装
composer install
```

### 4. npm 安装失败

尝试：
```bash
# 清除缓存
npm cache clean --force

# 删除 node_modules 重新安装
rm -rf node_modules
npm install

# 使用国内镜像
npm config set registry https://registry.npmmirror.com
```

### 5. 登录失败

检查：
- 后端服务是否正常启动
- 数据库连接是否正常
- 用户名和密码是否正确
- 浏览器控制台是否有错误信息

### 6. 跨域问题

如果遇到跨域问题：
1. 检查后端CORS中间件是否正常工作
2. 确认前端代理配置正确
3. 检查请求头设置

## 开发技巧

### 1. 热重载

- 前端：Vite 自动热重载
- 后端：修改代码后需要重启服务

### 2. 调试技巧

```javascript
// 前端调试
console.log('Debug info:', data)

// 使用Vue DevTools
```

```php
// 后端调试
dump($data); // ThinkPHP调试函数
error_log('Debug: ' . json_encode($data)); // 写入错误日志
```

### 3. 代码格式化

使用 Prettier 格式化代码：
```bash
# 安装全局Prettier
npm install -g prettier

# 格式化代码
prettier --write "src/**/*.{js,vue,css}"
```

## 部署准备

### 1. 生产环境配置

修改 `.env` 文件：
```ini
APP_DEBUG = false

[DATABASE]
HOSTNAME = 生产服务器IP
USERNAME = 生产数据库用户名
PASSWORD = 生产数据库密码
```

### 2. 前端构建

```bash
npm run build
```

### 3. 服务器部署

将构建后的文件上传到服务器，配置Web服务器（Apache/Nginx）。

---

现在您可以开始使用和开发这个用户管理系统了！如果遇到问题，请检查相关日志文件或联系技术支持。
