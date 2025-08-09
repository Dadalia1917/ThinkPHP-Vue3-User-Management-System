<template>
  <div class="not-found">
    <div class="not-found-container">
      <div class="error-content">
        <div class="error-number">404</div>
        <div class="error-title">页面未找到</div>
        <div class="error-description">
          抱歉，您访问的页面不存在或已被移除
        </div>
        
        <div class="error-actions">
          <el-button type="primary" @click="goHome">
            <el-icon><House /></el-icon>
            返回首页
          </el-button>
          <el-button @click="goBack">
            <el-icon><Back /></el-icon>
            返回上页
          </el-button>
        </div>
      </div>
      
      <div class="error-image">
        <svg
          viewBox="0 0 404 404"
          width="300"
          height="300"
          fill="currentColor"
          style="color: #e6e7eb;"
        >
          <g fill-rule="evenodd">
            <g transform="translate(44 44)">
              <rect width="316" height="316" rx="158" fill="#e6e7eb" opacity="0.3"/>
              <circle cx="158" cy="158" r="120" fill="#fff"/>
              <circle cx="120" cy="120" r="15" fill="#333"/>
              <circle cx="196" cy="120" r="15" fill="#333"/>
              <path d="M120 180 Q158 220 196 180" stroke="#333" stroke-width="4" fill="none"/>
            </g>
          </g>
        </svg>
      </div>
    </div>
    
    <!-- 建议链接 -->
    <div class="suggestions">
      <h3>您可能想要：</h3>
      <div class="suggestion-links">
        <el-link @click="$router.push('/dashboard')" class="suggestion-item">
          <el-icon><Odometer /></el-icon>
          查看仪表盘
        </el-link>
        <el-link @click="$router.push('/users')" class="suggestion-item">
          <el-icon><User /></el-icon>
          用户管理
        </el-link>
        <el-link @click="$router.push('/profile')" class="suggestion-item">
          <el-icon><Setting /></el-icon>
          个人资料
        </el-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

// 返回首页
const goHome = () => {
  router.push('/dashboard')
}

// 返回上一页
const goBack = () => {
  router.go(-1)
}
</script>

<style lang="scss" scoped>
.not-found {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: #fff;
  padding: 20px;
  
  .not-found-container {
    display: flex;
    align-items: center;
    gap: 60px;
    max-width: 800px;
    width: 100%;
    
    .error-content {
      flex: 1;
      text-align: left;
      
      .error-number {
        font-size: 120px;
        font-weight: bold;
        line-height: 1;
        margin-bottom: 20px;
        background: linear-gradient(45deg, #fff, #e0e0e0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }
      
      .error-title {
        font-size: 32px;
        font-weight: 600;
        margin-bottom: 16px;
        color: #fff;
      }
      
      .error-description {
        font-size: 18px;
        margin-bottom: 40px;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.6;
      }
      
      .error-actions {
        display: flex;
        gap: 16px;
        
        .el-button {
          padding: 12px 24px;
          font-size: 16px;
          border-radius: 8px;
          
          &.el-button--primary {
            background: #fff;
            color: #667eea;
            border: none;
            
            &:hover {
              background: #f0f0f0;
            }
          }
          
          &:not(.el-button--primary) {
            background: transparent;
            color: #fff;
            border: 2px solid rgba(255, 255, 255, 0.3);
            
            &:hover {
              background: rgba(255, 255, 255, 0.1);
              border-color: rgba(255, 255, 255, 0.5);
            }
          }
        }
      }
    }
    
    .error-image {
      flex-shrink: 0;
    }
  }
  
  .suggestions {
    margin-top: 60px;
    text-align: center;
    
    h3 {
      margin-bottom: 20px;
      color: rgba(255, 255, 255, 0.9);
      font-weight: 500;
    }
    
    .suggestion-links {
      display: flex;
      gap: 24px;
      justify-content: center;
      
      .suggestion-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        color: #fff;
        text-decoration: none;
        transition: all 0.3s;
        
        &:hover {
          background: rgba(255, 255, 255, 0.2);
          transform: translateY(-2px);
        }
        
        .el-icon {
          font-size: 16px;
        }
      }
    }
  }
}

@media (max-width: 768px) {
  .not-found {
    .not-found-container {
      flex-direction: column;
      gap: 40px;
      text-align: center;
      
      .error-content {
        .error-number {
          font-size: 80px;
        }
        
        .error-title {
          font-size: 24px;
        }
        
        .error-description {
          font-size: 16px;
          margin-bottom: 30px;
        }
        
        .error-actions {
          justify-content: center;
          flex-wrap: wrap;
        }
      }
      
      .error-image {
        svg {
          width: 200px;
          height: 200px;
        }
      }
    }
    
    .suggestions {
      margin-top: 40px;
      
      .suggestion-links {
        flex-direction: column;
        align-items: center;
        gap: 12px;
        
        .suggestion-item {
          width: 200px;
          justify-content: center;
        }
      }
    }
  }
}
</style>
