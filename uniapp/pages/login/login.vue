<template>
  <view class="login-container">
    <view class="login-form">
      <text class="title">用户管理系统</text>
      <text class="subtitle">欢迎使用移动端应用</text>
      
      <view class="form-item">
        <input
          v-model="form.account"
          placeholder="请输入用户名或邮箱"
          class="form-input"
          :focus="false"
        />
      </view>
      
      <view class="form-item">
        <input
          v-model="form.password"
          type="password"
          placeholder="请输入密码"
          class="form-input"
          :focus="false"
        />
      </view>
      
      <button
        class="login-btn"
        :disabled="loading"
        @click="handleLogin"
      >
        {{ loading ? '登录中...' : '登录' }}
      </button>
      
      <view class="register-link" @click="goRegister">
        还没有账户？立即注册
      </view>
      
      <view class="demo-info">
        <text class="demo-title">演示账号：</text>
        <text class="demo-account">管理员: admin / admin123</text>
        <text class="demo-account">普通用户: test / password</text>
      </view>
    </view>
  </view>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      form: {
        account: '',
        password: ''
      }
    }
  },
  
  methods: {
    async handleLogin() {
      if (!this.form.account || !this.form.password) {
        uni.showToast({
          title: '请填写完整信息',
          icon: 'none'
        })
        return
      }
      
      this.loading = true
      
      try {
        // 调用登录API
        const response = await this.loginApi(this.form)
        
        if (response.code === 200) {
          // 保存token和用户信息
          uni.setStorageSync('token', response.data.token)
          uni.setStorageSync('userInfo', response.data.user)
          
          uni.showToast({
            title: '登录成功',
            icon: 'success'
          })
          
          // 跳转到首页
          setTimeout(() => {
            uni.switchTab({
              url: '/pages/index/index'
            })
          }, 1500)
        } else {
          uni.showToast({
            title: response.message || '登录失败',
            icon: 'none'
          })
        }
        
      } catch (error) {
        console.error('登录失败:', error)
        uni.showToast({
          title: '网络错误，请重试',
          icon: 'none'
        })
      } finally {
        this.loading = false
      }
    },
    
    goRegister() {
      uni.navigateTo({
        url: '/pages/register/register'
      })
    },
    
    // 登录API调用
    loginApi(data) {
      return new Promise((resolve, reject) => {
        uni.request({
          url: 'http://localhost:8000/api/user/login', // 替换为实际API地址
          method: 'POST',
          data: data,
          header: {
            'Content-Type': 'application/json'
          },
          success: (res) => {
            resolve(res.data)
          },
          fail: (error) => {
            reject(error)
          }
        })
      })
    }
  }
}
</script>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 60rpx 40rpx;
}

.login-form {
  width: 100%;
  max-width: 600rpx;
  background: #ffffff;
  border-radius: 24rpx;
  padding: 80rpx 60rpx;
  box-shadow: 0 8rpx 32rpx rgba(0, 0, 0, 0.1);
}

.title {
  font-size: 48rpx;
  font-weight: bold;
  text-align: center;
  color: #333333;
  margin-bottom: 16rpx;
  display: block;
}

.subtitle {
  font-size: 28rpx;
  text-align: center;
  color: #666666;
  margin-bottom: 80rpx;
  display: block;
}

.form-item {
  margin-bottom: 40rpx;
}

.form-input {
  width: 100%;
  height: 88rpx;
  padding: 0 30rpx;
  border: 2rpx solid #e0e0e0;
  border-radius: 12rpx;
  font-size: 32rpx;
  box-sizing: border-box;
  background-color: #fafafa;
}

.form-input:focus {
  border-color: #667eea;
  background-color: #ffffff;
}

.login-btn {
  width: 100%;
  height: 88rpx;
  background: linear-gradient(135deg, #667eea, #764ba2);
  color: #ffffff;
  border: none;
  border-radius: 12rpx;
  font-size: 32rpx;
  font-weight: bold;
  margin-bottom: 40rpx;
  display: flex;
  align-items: center;
  justify-content: center;
}

.login-btn:disabled {
  opacity: 0.6;
}

.register-link {
  text-align: center;
  color: #667eea;
  font-size: 28rpx;
  margin-bottom: 60rpx;
}

.demo-info {
  background-color: #f8f9fa;
  padding: 30rpx;
  border-radius: 12rpx;
  border-left: 6rpx solid #667eea;
}

.demo-title {
  font-size: 26rpx;
  color: #333333;
  font-weight: bold;
  display: block;
  margin-bottom: 16rpx;
}

.demo-account {
  font-size: 24rpx;
  color: #666666;
  display: block;
  margin-bottom: 8rpx;
}
</style>
