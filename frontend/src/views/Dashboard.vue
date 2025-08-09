<template>
  <div class="dashboard">
    <!-- 头部导航 -->
    <div class="app-header">
      <div class="logo">
        <el-icon class="logo-icon">
          <Avatar />
        </el-icon>
        用户管理系统
      </div>
      
      <div class="header-right">
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
              <el-dropdown-item command="profile">
                <el-icon><User /></el-icon>
                个人资料
              </el-dropdown-item>
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
        <!-- 欢迎信息 -->
        <div class="welcome-section">
          <h1>欢迎回来，{{ userStore.userInfo?.nickname }}！</h1>
          <p>今天是 {{ currentDate }}，祝您工作愉快！</p>
        </div>
        
        <!-- 统计卡片 -->
        <el-row :gutter="20" class="stats-row">
          <el-col :xs="24" :sm="12" :md="6">
            <div class="stat-card">
              <div class="stat-number">{{ stats.totalUsers }}</div>
              <div class="stat-label">总用户数</div>
            </div>
          </el-col>
          <el-col :xs="24" :sm="12" :md="6">
            <div class="stat-card success">
              <div class="stat-number">{{ stats.activeUsers }}</div>
              <div class="stat-label">活跃用户</div>
            </div>
          </el-col>
          <el-col :xs="24" :sm="12" :md="6">
            <div class="stat-card warning">
              <div class="stat-number">{{ stats.todayLogin }}</div>
              <div class="stat-label">今日登录</div>
            </div>
          </el-col>
          <el-col :xs="24" :sm="12" :md="6">
            <div class="stat-card danger">
              <div class="stat-number">{{ stats.newUsers }}</div>
              <div class="stat-label">新注册用户</div>
            </div>
          </el-col>
        </el-row>
        
        <!-- 功能导航 -->
        <div class="feature-section">
          <h2>功能导航</h2>
          <el-row :gutter="20">
            <el-col :xs="24" :sm="12" :md="8">
              <div class="feature-card" @click="$router.push('/users')">
                <el-icon class="feature-icon"><User /></el-icon>
                <h3>用户管理</h3>
                <p>查看和管理系统用户</p>
              </div>
            </el-col>
            <el-col :xs="24" :sm="12" :md="8">
              <div class="feature-card" @click="$router.push('/profile')">
                <el-icon class="feature-icon"><Setting /></el-icon>
                <h3>个人资料</h3>
                <p>修改个人信息和密码</p>
              </div>
            </el-col>
            <el-col :xs="24" :sm="12" :md="8">
              <div class="feature-card" @click="testApiConnection">
                <el-icon class="feature-icon"><Connection /></el-icon>
                <h3>API测试</h3>
                <p>测试API接口连接</p>
              </div>
            </el-col>
          </el-row>
        </div>
        
        <!-- 最近活动 -->
        <div class="activity-section">
          <h2>最近活动</h2>
          <div class="activity-list">
            <div class="activity-item" v-for="activity in activities" :key="activity.id">
              <div class="activity-icon">
                <el-icon>
                  <component :is="activity.icon" />
                </el-icon>
              </div>
              <div class="activity-content">
                <div class="activity-title">{{ activity.title }}</div>
                <div class="activity-time">{{ activity.time }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/user'
import { testApi } from '@/api/user'

const router = useRouter()
const userStore = useUserStore()

// 统计数据
const stats = reactive({
  totalUsers: 0,
  activeUsers: 0,
  todayLogin: 0,
  newUsers: 0
})

// 活动数据
const activities = ref([
  {
    id: 1,
    title: '您修改了个人资料',
    time: '2小时前',
    icon: 'User'
  },
  {
    id: 2,
    title: '新用户注册成功',
    time: '3小时前',
    icon: 'Plus'
  },
  {
    id: 3,
    title: '系统数据更新',
    time: '5小时前',
    icon: 'Refresh'
  },
  {
    id: 4,
    title: '用户登录系统',
    time: '1天前',
    icon: 'Lock'
  }
])

// 当前日期
const currentDate = computed(() => {
  return new Date().toLocaleDateString('zh-CN', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    weekday: 'long'
  })
})

// 处理下拉菜单命令
const handleCommand = (command) => {
  switch (command) {
    case 'profile':
      router.push('/profile')
      break
    case 'logout':
      userStore.logout()
      router.push('/login')
      break
  }
}

// 测试API连接
const testApiConnection = async () => {
  try {
    const response = await testApi()
    ElMessage.success('API连接正常')
    console.log('API测试结果:', response)
  } catch (error) {
    ElMessage.error('API连接失败')
    console.error('API测试失败:', error)
  }
}

// 加载统计数据
const loadStats = () => {
  // 模拟数据，实际项目中应该从API获取
  stats.totalUsers = 156
  stats.activeUsers = 124
  stats.todayLogin = 45
  stats.newUsers = 12
}

onMounted(() => {
  loadStats()
})
</script>

<style lang="scss" scoped>
.dashboard {
  .welcome-section {
    margin-bottom: 30px;
    padding: 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    color: white;
    text-align: center;
    
    h1 {
      margin-bottom: 8px;
      font-size: 28px;
      font-weight: 600;
    }
    
    p {
      font-size: 16px;
      opacity: 0.9;
    }
  }
  
  .stats-row {
    margin-bottom: 30px;
  }
  
  .feature-section,
  .activity-section {
    margin-bottom: 30px;
    
    h2 {
      margin-bottom: 20px;
      color: #333;
      font-weight: 600;
    }
  }
  
  .feature-card {
    background: #fff;
    border-radius: 12px;
    padding: 30px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    
    &:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .feature-icon {
      font-size: 48px;
      color: var(--app-primary-color);
      margin-bottom: 16px;
    }
    
    h3 {
      margin-bottom: 8px;
      color: #333;
      font-size: 18px;
      font-weight: 600;
    }
    
    p {
      color: #666;
      font-size: 14px;
      margin: 0;
    }
  }
  
  .activity-list {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
    
    .activity-item {
      display: flex;
      align-items: center;
      padding: 16px 0;
      border-bottom: 1px solid #f0f0f0;
      
      &:last-child {
        border-bottom: none;
      }
      
      .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f0f9ff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        
        .el-icon {
          color: var(--app-primary-color);
          font-size: 18px;
        }
      }
      
      .activity-content {
        flex: 1;
        
        .activity-title {
          color: #333;
          font-weight: 500;
          margin-bottom: 4px;
        }
        
        .activity-time {
          color: #999;
          font-size: 12px;
        }
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
  .dashboard {
    .welcome-section {
      padding: 20px;
      
      h1 {
        font-size: 24px;
      }
      
      p {
        font-size: 14px;
      }
    }
    
    .feature-card {
      padding: 20px 15px;
    }
    
    .user-info {
      .username {
        display: none;
      }
    }
  }
}
</style>
