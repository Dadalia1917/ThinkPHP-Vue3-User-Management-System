<template>
  <view class="index-container">
    <!-- 顶部状态栏 -->
    <view class="status-bar"></view>
    
    <!-- 头部区域 -->
    <view class="header">
      <view class="header-content">
        <view class="user-info">
          <image 
            v-if="userInfo.avatar" 
            :src="userInfo.avatar" 
            class="avatar"
          ></image>
          <view v-else class="avatar-placeholder">
            {{ userInfo.nickname ? userInfo.nickname.charAt(0) : 'U' }}
          </view>
          <view class="user-text">
            <text class="greeting">{{ greeting }}</text>
            <text class="nickname">{{ userInfo.nickname || '未登录' }}</text>
          </view>
        </view>
        <view class="header-actions">
          <view class="action-btn" @click="goToProfile">
            <text class="iconfont">⚙️</text>
          </view>
        </view>
      </view>
    </view>
    
    <!-- 统计卡片 -->
    <view class="stats-section">
      <view class="stats-grid">
        <view class="stat-item">
          <text class="stat-number">{{ stats.totalUsers }}</text>
          <text class="stat-label">总用户</text>
        </view>
        <view class="stat-item">
          <text class="stat-number">{{ stats.activeUsers }}</text>
          <text class="stat-label">活跃用户</text>
        </view>
        <view class="stat-item">
          <text class="stat-number">{{ stats.todayLogin }}</text>
          <text class="stat-label">今日登录</text>
        </view>
        <view class="stat-item">
          <text class="stat-number">{{ stats.newUsers }}</text>
          <text class="stat-label">新用户</text>
        </view>
      </view>
    </view>
    
    <!-- 功能菜单 -->
    <view class="menu-section">
      <text class="section-title">功能菜单</text>
      <view class="menu-grid">
        <view class="menu-item" @click="goToUsers">
          <view class="menu-icon">👥</view>
          <text class="menu-text">用户管理</text>
        </view>
        <view class="menu-item" @click="goToProfile">
          <view class="menu-icon">👤</view>
          <text class="menu-text">个人资料</text>
        </view>
        <view class="menu-item" @click="testApi">
          <view class="menu-icon">🔗</view>
          <text class="menu-text">API测试</text>
        </view>
        <view class="menu-item" @click="showAbout">
          <view class="menu-icon">ℹ️</view>
          <text class="menu-text">关于系统</text>
        </view>
      </view>
    </view>
    
    <!-- 最近活动 -->
    <view class="activity-section">
      <text class="section-title">最近活动</text>
      <view class="activity-list">
        <view 
          class="activity-item" 
          v-for="activity in activities" 
          :key="activity.id"
        >
          <view class="activity-icon">{{ activity.icon }}</view>
          <view class="activity-content">
            <text class="activity-title">{{ activity.title }}</text>
            <text class="activity-time">{{ activity.time }}</text>
          </view>
        </view>
      </view>
    </view>
  </view>
</template>

<script>
export default {
  data() {
    return {
      userInfo: {},
      stats: {
        totalUsers: 156,
        activeUsers: 124,
        todayLogin: 45,
        newUsers: 12
      },
      activities: [
        {
          id: 1,
          title: '用户登录系统',
          time: '刚刚',
          icon: '🔐'
        },
        {
          id: 2,
          title: '新用户注册',
          time: '5分钟前',
          icon: '👤'
        },
        {
          id: 3,
          title: '数据同步完成',
          time: '10分钟前',
          icon: '🔄'
        },
        {
          id: 4,
          title: '系统状态正常',
          time: '1小时前',
          icon: '✅'
        }
      ]
    }
  },
  
  computed: {
    greeting() {
      const hour = new Date().getHours()
      if (hour < 12) return '早上好'
      if (hour < 18) return '下午好'
      return '晚上好'
    }
  },
  
  onLoad() {
    this.loadUserInfo()
    this.loadStats()
  },
  
  onShow() {
    // 页面显示时刷新数据
    this.loadUserInfo()
  },
  
  methods: {
    loadUserInfo() {
      // 从本地存储获取用户信息
      const userInfo = uni.getStorageSync('userInfo')
      if (userInfo) {
        this.userInfo = userInfo
      } else {
        // 未登录，跳转到登录页
        uni.navigateTo({
          url: '/pages/login/login'
        })
      }
    },
    
    loadStats() {
      // 这里可以调用API获取实际统计数据
      // 目前使用模拟数据
    },
    
    goToUsers() {
      uni.switchTab({
        url: '/pages/users/users'
      })
    },
    
    goToProfile() {
      uni.switchTab({
        url: '/pages/profile/profile'
      })
    },
    
    testApi() {
      uni.showLoading({
        title: '测试中...'
      })
      
      // 测试API连接
      uni.request({
        url: 'http://localhost:8000/api/test',
        method: 'GET',
        success: (res) => {
          uni.hideLoading()
          if (res.data.code === 200) {
            uni.showToast({
              title: 'API连接正常',
              icon: 'success'
            })
          } else {
            uni.showToast({
              title: 'API连接异常',
              icon: 'none'
            })
          }
        },
        fail: (error) => {
          uni.hideLoading()
          uni.showToast({
            title: '网络连接失败',
            icon: 'none'
          })
        }
      })
    },
    
    showAbout() {
      uni.showModal({
        title: '关于系统',
        content: '用户管理系统 v1.0.0\n基于ThinkPHP + Vue3 + UniApp开发\n专为面试展示设计',
        showCancel: false
      })
    }
  }
}
</script>

<style scoped>
.index-container {
  min-height: 100vh;
  background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
}

.status-bar {
  height: var(--status-bar-height);
}

.header {
  padding: 40rpx 30rpx;
  color: white;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 24rpx;
}

.avatar {
  width: 80rpx;
  height: 80rpx;
  border-radius: 50%;
}

.avatar-placeholder {
  width: 80rpx;
  height: 80rpx;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 32rpx;
  font-weight: bold;
}

.user-text {
  display: flex;
  flex-direction: column;
  gap: 8rpx;
}

.greeting {
  font-size: 28rpx;
  opacity: 0.8;
}

.nickname {
  font-size: 36rpx;
  font-weight: bold;
}

.header-actions {
  display: flex;
  gap: 20rpx;
}

.action-btn {
  width: 60rpx;
  height: 60rpx;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24rpx;
}

.stats-section {
  margin: 40rpx 30rpx;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20rpx;
}

.stat-item {
  background: rgba(255, 255, 255, 0.15);
  border-radius: 16rpx;
  padding: 30rpx 20rpx;
  text-align: center;
  color: white;
}

.stat-number {
  display: block;
  font-size: 48rpx;
  font-weight: bold;
  margin-bottom: 8rpx;
}

.stat-label {
  font-size: 24rpx;
  opacity: 0.8;
}

.menu-section,
.activity-section {
  margin: 40rpx 30rpx;
}

.section-title {
  display: block;
  font-size: 32rpx;
  font-weight: bold;
  color: white;
  margin-bottom: 24rpx;
}

.menu-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24rpx;
}

.menu-item {
  background: rgba(255, 255, 255, 0.15);
  border-radius: 16rpx;
  padding: 30rpx 20rpx;
  text-align: center;
  color: white;
}

.menu-icon {
  font-size: 48rpx;
  margin-bottom: 12rpx;
}

.menu-text {
  display: block;
  font-size: 24rpx;
}

.activity-list {
  background: rgba(255, 255, 255, 0.15);
  border-radius: 16rpx;
  padding: 20rpx;
}

.activity-item {
  display: flex;
  align-items: center;
  gap: 24rpx;
  padding: 20rpx 0;
  border-bottom: 1rpx solid rgba(255, 255, 255, 0.1);
  color: white;
}

.activity-item:last-child {
  border-bottom: none;
}

.activity-icon {
  width: 60rpx;
  height: 60rpx;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24rpx;
}

.activity-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 6rpx;
}

.activity-title {
  font-size: 28rpx;
  font-weight: 500;
}

.activity-time {
  font-size: 24rpx;
  opacity: 0.7;
}

@media (max-width: 750rpx) {
  .stats-grid,
  .menu-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
