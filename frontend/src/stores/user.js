import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import Cookies from 'js-cookie'
import { login, register, getUserInfo, updateUser, changePassword } from '@/api/user'
import { ElMessage } from 'element-plus'

export const useUserStore = defineStore('user', () => {
  const token = ref(Cookies.get('token') || '')
  const userInfo = ref(null)

  // 计算属性：是否已登录
  const isLoggedIn = computed(() => !!token.value && !!userInfo.value)

  // 设置token
  const setToken = (newToken) => {
    token.value = newToken
    if (newToken) {
      Cookies.set('token', newToken, { expires: 7 }) // 7天过期
    } else {
      Cookies.remove('token')
    }
  }

  // 设置用户信息
  const setUserInfo = (info) => {
    userInfo.value = info
  }

  // 登录
  const userLogin = async (loginForm) => {
    try {
      const response = await login(loginForm)
      if (response.code === 200) {
        setToken(response.data.token)
        setUserInfo(response.data.user)
        ElMessage.success(response.message || '登录成功')
        return true
      } else {
        ElMessage.error(response.message || '登录失败')
        return false
      }
    } catch (error) {
      ElMessage.error(error.message || '登录失败')
      return false
    }
  }

  // 注册
  const userRegister = async (registerForm) => {
    try {
      const response = await register(registerForm)
      if (response.code === 200) {
        ElMessage.success(response.message || '注册成功')
        return true
      } else {
        ElMessage.error(response.message || '注册失败')
        return false
      }
    } catch (error) {
      ElMessage.error(error.message || '注册失败')
      return false
    }
  }

  // 获取用户信息
  const fetchUserInfo = async () => {
    try {
      const response = await getUserInfo()
      if (response.code === 200) {
        setUserInfo(response.data)
        return true
      } else {
        // token可能过期，清除登录状态
        logout()
        return false
      }
    } catch (error) {
      // token可能过期，清除登录状态
      logout()
      return false
    }
  }

  // 更新用户信息
  const updateUserInfo = async (updateForm) => {
    try {
      const response = await updateUser(updateForm)
      if (response.code === 200) {
        setUserInfo(response.data.user)
        ElMessage.success(response.message || '更新成功')
        return true
      } else {
        ElMessage.error(response.message || '更新失败')
        return false
      }
    } catch (error) {
      ElMessage.error(error.message || '更新失败')
      return false
    }
  }

  // 修改密码
  const changeUserPassword = async (passwordForm) => {
    try {
      const response = await changePassword(passwordForm)
      if (response.code === 200) {
        ElMessage.success(response.message || '密码修改成功')
        return true
      } else {
        ElMessage.error(response.message || '密码修改失败')
        return false
      }
    } catch (error) {
      ElMessage.error(error.message || '密码修改失败')
      return false
    }
  }

  // 登出
  const logout = () => {
    setToken('')
    setUserInfo(null)
    ElMessage.success('已退出登录')
  }

  // 检查token有效性
  const checkToken = async () => {
    if (token.value && !userInfo.value) {
      await fetchUserInfo()
    }
  }

  return {
    token: readonly(token),
    userInfo: readonly(userInfo),
    isLoggedIn,
    setToken,
    setUserInfo,
    userLogin,
    userRegister,
    fetchUserInfo,
    updateUserInfo,
    changeUserPassword,
    logout,
    checkToken
  }
})
