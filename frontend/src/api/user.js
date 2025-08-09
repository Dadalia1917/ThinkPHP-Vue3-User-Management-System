import request from './request'

/**
 * 用户登录
 * @param {Object} data 登录数据
 * @param {string} data.account 账号（用户名或邮箱）
 * @param {string} data.password 密码
 */
export const login = (data) => {
  return request({
    url: '/api/simple-login',
    method: 'POST',
    data
  })
}

/**
 * 用户注册
 * @param {Object} data 注册数据
 * @param {string} data.username 用户名
 * @param {string} data.email 邮箱
 * @param {string} data.password 密码
 * @param {string} data.nickname 昵称
 * @param {string} [data.mobile] 手机号
 */
export const register = (data) => {
  return request({
    url: '/api/user/register',
    method: 'POST',
    data
  })
}

/**
 * 获取用户信息
 */
export const getUserInfo = () => {
  return request({
    url: '/api/user/info',
    method: 'GET'
  })
}

/**
 * 更新用户信息
 * @param {Object} data 用户数据
 * @param {string} [data.nickname] 昵称
 * @param {string} [data.email] 邮箱
 * @param {string} [data.mobile] 手机号
 * @param {string} [data.avatar] 头像
 */
export const updateUser = (data) => {
  return request({
    url: '/api/user/update',
    method: 'PUT',
    data
  })
}

/**
 * 修改密码
 * @param {Object} data 密码数据
 * @param {string} data.old_password 原密码
 * @param {string} data.new_password 新密码
 */
export const changePassword = (data) => {
  return request({
    url: '/api/user/change-password',
    method: 'POST',
    data
  })
}

/**
 * 获取用户列表
 * @param {Object} params 查询参数
 * @param {number} [params.page=1] 页码
 * @param {number} [params.limit=10] 每页数量
 * @param {string} [params.keyword] 搜索关键词
 * @param {number} [params.status] 用户状态
 */
export const getUserList = (params) => {
  return request({
    url: '/api/user/list',
    method: 'GET',
    params
  })
}

/**
 * 测试API连接
 */
export const testApi = () => {
  return request({
    url: '/api/test',
    method: 'GET'
  })
}
