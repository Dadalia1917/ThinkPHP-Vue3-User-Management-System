<?php

namespace app\model;

use think\Model;

/**
 * 用户模型
 */
class User extends Model
{
    // 表名
    protected $name = 'user';

    // 自动时间戳
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    // 定义字段类型
    protected $type = [
        'id' => 'integer',
        'status' => 'integer',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];

    // 隐藏字段
    protected $hidden = ['password', 'delete_time'];

    // 字段类型转换
    protected $json = [];

    // 只读字段
    protected $readonly = ['id', 'create_time'];

    /**
     * 密码修改器（明文存储）
     * @param string $value
     * @return string
     */
    public function setPasswordAttr($value)
    {
        return $value; // 直接返回明文密码
    }

    /**
     * 验证密码（明文比较）
     * @param string $password 明文密码
     * @param string $storedPassword 存储的密码
     * @return bool
     */
    public static function verifyPassword($password, $storedPassword)
    {
        return $password === $storedPassword;
    }

    /**
     * 根据用户名或邮箱查找用户
     * @param string $account 账号（用户名或邮箱）
     * @return User|null
     */
    public static function findByAccount($account)
    {
        return self::where('username', $account)
            ->whereOr('email', $account)
            ->where('status', 1)
            ->find();
    }

    /**
     * 检查用户名是否存在
     * @param string $username
     * @param int $excludeId 排除的用户ID
     * @return bool
     */
    public static function usernameExists($username, $excludeId = 0)
    {
        $query = self::where('username', $username);
        if ($excludeId > 0) {
            $query->where('id', '<>', $excludeId);
        }
        return $query->count() > 0;
    }

    /**
     * 检查邮箱是否存在
     * @param string $email
     * @param int $excludeId 排除的用户ID
     * @return bool
     */
    public static function emailExists($email, $excludeId = 0)
    {
        $query = self::where('email', $email);
        if ($excludeId > 0) {
            $query->where('id', '<>', $excludeId);
        }
        return $query->count() > 0;
    }

    /**
     * 获取用户列表
     * @param array $where 查询条件
     * @param int $page 页码
     * @param int $limit 每页数量
     * @return array
     */
    public static function getList($where = [], $page = 1, $limit = 10)
    {
        $query = self::where('status', '>=', 0);

        // 搜索条件
        if (!empty($where['keyword'])) {
            $query->where(function ($query) use ($where) {
                $query->whereOr('username', 'like', '%' . $where['keyword'] . '%')
                    ->whereOr('email', 'like', '%' . $where['keyword'] . '%')
                    ->whereOr('nickname', 'like', '%' . $where['keyword'] . '%');
            });
        }

        if (isset($where['status']) && $where['status'] !== '') {
            $query->where('status', $where['status']);
        }

        // 获取总数
        $total = $query->count();

        // 获取列表
        $list = $query->page($page, $limit)
            ->order('id desc')
            ->select()
            ->toArray();

        return [
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'pages' => ceil($total / $limit)
        ];
    }
}
