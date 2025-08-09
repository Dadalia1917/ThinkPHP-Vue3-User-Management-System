<template>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <h2>用户管理系统</h2>
        <p>欢迎回来，请登录您的账户</p>
      </div>
      
      <el-form
        ref="loginFormRef"
        :model="loginForm"
        :rules="loginRules"
        class="login-form"
        @keyup.enter="handleLogin"
      >
        <el-form-item prop="account">
          <el-input
            v-model="loginForm.account"
            placeholder="请输入用户名或邮箱"
            size="large"
            prefix-icon="User"
            clearable
          />
        </el-form-item>
        
        <el-form-item prop="password">
          <el-input
            v-model="loginForm.password"
            type="password"
            placeholder="请输入密码"
            size="large"
            prefix-icon="Lock"
            show-password
            clearable
          />
        </el-form-item>
        
        <el-form-item>
          <el-button
            type="primary"
            size="large"
            :loading="loading"
            @click="handleLogin"
            class="login-button"
          >
            {{ loading ? '登录中...' : '登录' }}
          </el-button>
        </el-form-item>
      </el-form>
      
      <div class="login-footer">
        <el-link @click="$router.push('/register')">
          还没有账户？立即注册
        </el-link>
      </div>
    </div>
    
    <!-- 演示账号提示 -->
    <div class="demo-tips">
      <el-alert
        title="演示账号"
        type="info"
        :closable="false"
        show-icon
      >
        <template #default>
          <p><strong>管理员：</strong>用户名: admin，密码: admin123</p>
          <p><strong>普通用户：</strong>用户名: test，密码: password</p>
        </template>
      </el-alert>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/user'

const router = useRouter()
const userStore = useUserStore()

const loginFormRef = ref()
const loading = ref(false)

// 登录表单数据
const loginForm = reactive({
  account: '',
  password: ''
})

// 表单验证规则
const loginRules = {
  account: [
    { required: true, message: '请输入用户名或邮箱', trigger: 'blur' }
  ],
  password: [
    { required: true, message: '请输入密码', trigger: 'blur' },
    { min: 6, message: '密码长度不能少于6位', trigger: 'blur' }
  ]
}

// 处理登录
const handleLogin = async () => {
  if (!loginFormRef.value) return
  
  try {
    await loginFormRef.value.validate()
    loading.value = true
    
    const success = await userStore.userLogin(loginForm)
    if (success) {
      router.push('/dashboard')
    }
  } catch (error) {
    console.error('登录失败:', error)
  } finally {
    loading.value = false
  }
}
</script>

<style lang="scss" scoped>
.login-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 20px;
  
  .login-card {
    width: 100%;
    max-width: 400px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    padding: 40px;
    margin-bottom: 20px;
    
    .login-header {
      text-align: center;
      margin-bottom: 30px;
      
      h2 {
        color: #333;
        margin-bottom: 8px;
        font-weight: 600;
      }
      
      p {
        color: #666;
        font-size: 14px;
      }
    }
    
    .login-form {
      .el-form-item {
        margin-bottom: 24px;
      }
      
      .login-button {
        width: 100%;
        height: 44px;
        font-size: 16px;
        font-weight: 500;
      }
    }
    
    .login-footer {
      text-align: center;
      margin-top: 20px;
    }
  }
  
  .demo-tips {
    width: 100%;
    max-width: 400px;
    
    :deep(.el-alert__content) {
      p {
        margin: 4px 0;
        font-size: 13px;
        
        strong {
          color: #409eff;
        }
      }
    }
  }
}

@media (max-width: 480px) {
  .login-container {
    padding: 10px;
    
    .login-card {
      padding: 30px 20px;
    }
  }
}
</style>
