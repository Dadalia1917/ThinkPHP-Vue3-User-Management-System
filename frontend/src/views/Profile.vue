<template>
  <div class="profile">
    <!-- 头部导航 -->
    <div class="app-header">
      <div class="logo">
        <el-icon class="logo-icon">
          <Avatar />
        </el-icon>
        用户管理系统
      </div>
      
      <div class="header-right">
        <el-button type="text" @click="$router.push('/dashboard')">
          <el-icon><House /></el-icon>
          返回首页
        </el-button>
        
        <el-dropdown @command="handleCommand">
          <span class="user-info">
            <el-avatar :size="32" :src="userStore.userInfo?.avatar">
              {{ userStore.userInfo?.nickname?.charAt(0) }}
            </el-avatar>
            <span class="username">{{ userStore.userInfo?.nickname }}</span>
            <el-icon><ArrowDown /></el-icon>
          </span>
          <template #dropdown>
            <el-dropdown-menu>
              <el-dropdown-item command="logout" divided>
                <el-icon><SwitchButton /></el-icon>
                退出登录
              </el-dropdown-item>
            </el-dropdown-menu>
          </template>
        </el-dropdown>
      </div>
    </div>
    
    <!-- 主要内容 -->
    <div class="app-main">
      <div class="app-container">
        <div class="page-header">
          <h1>个人资料</h1>
          <p>管理您的个人信息和账户设置</p>
        </div>
        
        <el-row :gutter="20">
          <!-- 个人信息卡片 -->
          <el-col :xs="24" :lg="16">
            <div class="profile-card">
              <h2>基本信息</h2>
              
              <el-form
                ref="profileFormRef"
                :model="profileForm"
                :rules="profileRules"
                label-width="100px"
                class="profile-form"
              >
                <el-form-item label="用户名">
                  <el-input
                    :value="userStore.userInfo?.username"
                    disabled
                    placeholder="用户名不可修改"
                  />
                </el-form-item>
                
                <el-form-item label="昵称" prop="nickname">
                  <el-input
                    v-model="profileForm.nickname"
                    placeholder="请输入昵称"
                    clearable
                  />
                </el-form-item>
                
                <el-form-item label="邮箱" prop="email">
                  <el-input
                    v-model="profileForm.email"
                    type="email"
                    placeholder="请输入邮箱"
                    clearable
                  />
                </el-form-item>
                
                <el-form-item label="手机号" prop="mobile">
                  <el-input
                    v-model="profileForm.mobile"
                    placeholder="请输入手机号"
                    clearable
                  />
                </el-form-item>
                
                <el-form-item label="头像" prop="avatar">
                  <el-input
                    v-model="profileForm.avatar"
                    placeholder="请输入头像URL（可选）"
                    clearable
                  />
                </el-form-item>
                
                <el-form-item>
                  <el-button
                    type="primary"
                    :loading="updateLoading"
                    @click="handleUpdateProfile"
                  >
                    {{ updateLoading ? '保存中...' : '保存修改' }}
                  </el-button>
                  <el-button @click="resetProfileForm">
                    重置
                  </el-button>
                </el-form-item>
              </el-form>
            </div>
          </el-col>
          
          <!-- 账户信息 -->
          <el-col :xs="24" :lg="8">
            <div class="account-card">
              <h2>账户信息</h2>
              
              <div class="info-item">
                <span class="label">注册时间</span>
                <span class="value">{{ formatDate(userStore.userInfo?.create_time) }}</span>
              </div>
              
              <div class="info-item">
                <span class="label">最后登录</span>
                <span class="value">
                  {{ userStore.userInfo?.last_login_time ? formatDate(userStore.userInfo.last_login_time) : '从未登录' }}
                </span>
              </div>
              
              <div class="info-item">
                <span class="label">账户状态</span>
                <el-tag :type="userStore.userInfo?.status === 1 ? 'success' : 'danger'">
                  {{ userStore.userInfo?.status === 1 ? '正常' : '禁用' }}
                </el-tag>
              </div>
            </div>
          </el-col>
        </el-row>
        
        <!-- 修改密码 -->
        <div class="password-card">
          <h2>修改密码</h2>
          
          <el-form
            ref="passwordFormRef"
            :model="passwordForm"
            :rules="passwordRules"
            label-width="100px"
            class="password-form"
          >
            <el-row :gutter="20">
              <el-col :xs="24" :md="12">
                <el-form-item label="原密码" prop="old_password">
                  <el-input
                    v-model="passwordForm.old_password"
                    type="password"
                    placeholder="请输入原密码"
                    show-password
                    clearable
                  />
                </el-form-item>
              </el-col>
              
              <el-col :xs="24" :md="12">
                <el-form-item label="新密码" prop="new_password">
                  <el-input
                    v-model="passwordForm.new_password"
                    type="password"
                    placeholder="请输入新密码"
                    show-password
                    clearable
                  />
                </el-form-item>
              </el-col>
            </el-row>
            
            <el-form-item label="确认密码" prop="confirm_password">
              <el-input
                v-model="passwordForm.confirm_password"
                type="password"
                placeholder="请确认新密码"
                show-password
                clearable
                style="max-width: 300px;"
              />
            </el-form-item>
            
            <el-form-item>
              <el-button
                type="danger"
                :loading="passwordLoading"
                @click="handleChangePassword"
              >
                {{ passwordLoading ? '修改中...' : '修改密码' }}
              </el-button>
              <el-button @click="resetPasswordForm">
                重置
              </el-button>
            </el-form-item>
          </el-form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/user'

const router = useRouter()
const userStore = useUserStore()

const profileFormRef = ref()
const passwordFormRef = ref()
const updateLoading = ref(false)
const passwordLoading = ref(false)

// 个人资料表单
const profileForm = reactive({
  nickname: '',
  email: '',
  mobile: '',
  avatar: ''
})

// 密码表单
const passwordForm = reactive({
  old_password: '',
  new_password: '',
  confirm_password: ''
})

// 个人资料验证规则
const profileRules = {
  nickname: [
    { required: true, message: '请输入昵称', trigger: 'blur' },
    { min: 2, max: 20, message: '昵称长度为2-20个字符', trigger: 'blur' }
  ],
  email: [
    { required: true, message: '请输入邮箱', trigger: 'blur' },
    { type: 'email', message: '请输入正确的邮箱格式', trigger: 'blur' }
  ],
  mobile: [
    { pattern: /^1[3-9]\d{9}$/, message: '请输入正确的手机号', trigger: 'blur' }
  ]
}

// 密码验证规则
const validateConfirmPassword = (rule, value, callback) => {
  if (value !== passwordForm.new_password) {
    callback(new Error('两次输入的密码不一致'))
  } else {
    callback()
  }
}

const passwordRules = {
  old_password: [
    { required: true, message: '请输入原密码', trigger: 'blur' }
  ],
  new_password: [
    { required: true, message: '请输入新密码', trigger: 'blur' },
    { min: 6, max: 20, message: '密码长度为6-20个字符', trigger: 'blur' }
  ],
  confirm_password: [
    { required: true, message: '请确认新密码', trigger: 'blur' },
    { validator: validateConfirmPassword, trigger: 'blur' }
  ]
}

// 处理下拉菜单命令
const handleCommand = (command) => {
  if (command === 'logout') {
    userStore.logout()
    router.push('/login')
  }
}

// 初始化表单数据
const initForm = () => {
  if (userStore.userInfo) {
    profileForm.nickname = userStore.userInfo.nickname || ''
    profileForm.email = userStore.userInfo.email || ''
    profileForm.mobile = userStore.userInfo.mobile || ''
    profileForm.avatar = userStore.userInfo.avatar || ''
  }
}

// 重置个人资料表单
const resetProfileForm = () => {
  initForm()
}

// 重置密码表单
const resetPasswordForm = () => {
  passwordForm.old_password = ''
  passwordForm.new_password = ''
  passwordForm.confirm_password = ''
  passwordFormRef.value?.clearValidate()
}

// 更新个人资料
const handleUpdateProfile = async () => {
  if (!profileFormRef.value) return
  
  try {
    await profileFormRef.value.validate()
    updateLoading.value = true
    
    const success = await userStore.updateUserInfo(profileForm)
    if (success) {
      // 刷新用户信息
      await userStore.fetchUserInfo()
    }
  } catch (error) {
    console.error('更新个人资料失败:', error)
  } finally {
    updateLoading.value = false
  }
}

// 修改密码
const handleChangePassword = async () => {
  if (!passwordFormRef.value) return
  
  try {
    await passwordFormRef.value.validate()
    passwordLoading.value = true
    
    const success = await userStore.changeUserPassword({
      old_password: passwordForm.old_password,
      new_password: passwordForm.new_password
    })
    
    if (success) {
      resetPasswordForm()
    }
  } catch (error) {
    console.error('修改密码失败:', error)
  } finally {
    passwordLoading.value = false
  }
}

// 格式化日期
const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return date.toLocaleString('zh-CN')
}

onMounted(() => {
  initForm()
})
</script>

<style lang="scss" scoped>
.profile {
  .page-header {
    margin-bottom: 30px;
    text-align: center;
    
    h1 {
      color: #333;
      margin-bottom: 8px;
      font-weight: 600;
    }
    
    p {
      color: #666;
      font-size: 16px;
    }
  }
  
  .profile-card,
  .account-card,
  .password-card {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    
    h2 {
      margin-bottom: 20px;
      color: #333;
      font-weight: 600;
      border-bottom: 2px solid #f0f0f0;
      padding-bottom: 10px;
    }
  }
  
  .profile-form,
  .password-form {
    .el-form-item {
      margin-bottom: 20px;
    }
  }
  
  .account-card {
    .info-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 0;
      border-bottom: 1px solid #f0f0f0;
      
      &:last-child {
        border-bottom: none;
      }
      
      .label {
        color: #666;
        font-weight: 500;
      }
      
      .value {
        color: #333;
        font-weight: 400;
      }
    }
  }
  
  .user-info {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    padding: 8px 12px;
    border-radius: 6px;
    transition: background-color 0.3s;
    
    &:hover {
      background-color: #f5f5f5;
    }
    
    .username {
      font-weight: 500;
      color: #333;
    }
  }
}

@media (max-width: 768px) {
  .profile {
    .user-info {
      .username {
        display: none;
      }
    }
    
    .profile-card,
    .account-card,
    .password-card {
      padding: 16px;
    }
    
    .el-form {
      :deep(.el-form-item__label) {
        width: 80px !important;
      }
    }
  }
}
</style>