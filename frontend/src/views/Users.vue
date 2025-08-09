<template>
  <div class="users">
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
        <div class="page-header">
          <h1>用户管理</h1>
          <p>管理系统中的所有用户</p>
        </div>
        
        <!-- 搜索和筛选 -->
        <div class="search-box">
          <el-input
            v-model="searchForm.keyword"
            placeholder="搜索用户名、邮箱或昵称"
            prefix-icon="Search"
            clearable
            @clear="handleSearch"
            @keyup.enter="handleSearch"
          />
          <el-select
            v-model="searchForm.status"
            placeholder="用户状态"
            clearable
            @change="handleSearch"
          >
            <el-option label="全部" value="" />
            <el-option label="正常" :value="1" />
            <el-option label="禁用" :value="0" />
          </el-select>
          <el-button type="primary" @click="handleSearch">
            <el-icon><Search /></el-icon>
            搜索
          </el-button>
          <el-button @click="resetSearch">
            <el-icon><Refresh /></el-icon>
            重置
          </el-button>
        </div>
        
        <!-- 用户表格 -->
        <div class="page-container">
          <el-table
            v-loading="loading"
            :data="userList"
            stripe
            border
            style="width: 100%"
          >
            <el-table-column prop="id" label="ID" width="80" />
            <el-table-column prop="username" label="用户名" min-width="120" />
            <el-table-column prop="email" label="邮箱" min-width="180" />
            <el-table-column prop="nickname" label="昵称" min-width="120" />
            <el-table-column prop="mobile" label="手机号" width="130">
              <template #default="{ row }">
                {{ row.mobile || '-' }}
              </template>
            </el-table-column>
            <el-table-column prop="status" label="状态" width="80">
              <template #default="{ row }">
                <el-tag :type="row.status === 1 ? 'success' : 'danger'">
                  {{ row.status === 1 ? '正常' : '禁用' }}
                </el-tag>
              </template>
            </el-table-column>
            <el-table-column prop="last_login_time" label="最后登录" width="160">
              <template #default="{ row }">
                {{ row.last_login_time ? formatDate(row.last_login_time) : '从未登录' }}
              </template>
            </el-table-column>
            <el-table-column prop="create_time" label="注册时间" width="160">
              <template #default="{ row }">
                {{ formatDate(row.create_time) }}
              </template>
            </el-table-column>
          </el-table>
          
          <!-- 分页 -->
          <div class="pagination-container">
            <el-pagination
              v-model:current-page="pagination.page"
              v-model:page-size="pagination.limit"
              :page-sizes="[10, 20, 50, 100]"
              :total="pagination.total"
              layout="total, sizes, prev, pager, next, jumper"
              @size-change="handleSizeChange"
              @current-change="handleCurrentChange"
            />
          </div>
        </div>
        
        <!-- 空状态 -->
        <div v-if="!loading && userList.length === 0" class="empty-state">
          <el-icon class="empty-icon"><User /></el-icon>
          <div class="empty-text">暂无用户数据</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/user'
import { getUserList } from '@/api/user'

const router = useRouter()
const userStore = useUserStore()

const loading = ref(false)
const userList = ref([])

// 搜索表单
const searchForm = reactive({
  keyword: '',
  status: ''
})

// 分页数据
const pagination = reactive({
  page: 1,
  limit: 10,
  total: 0
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

// 获取用户列表
const fetchUsers = async () => {
  loading.value = true
  try {
    const params = {
      page: pagination.page,
      limit: pagination.limit,
      ...searchForm
    }
    
    const response = await getUserList(params)
    if (response.code === 200) {
      userList.value = response.data.list
      pagination.total = response.data.total
    }
  } catch (error) {
    console.error('获取用户列表失败:', error)
    ElMessage.error('获取用户列表失败')
  } finally {
    loading.value = false
  }
}

// 搜索用户
const handleSearch = () => {
  pagination.page = 1
  fetchUsers()
}

// 重置搜索
const resetSearch = () => {
  searchForm.keyword = ''
  searchForm.status = ''
  pagination.page = 1
  fetchUsers()
}

// 处理页码变化
const handleCurrentChange = () => {
  fetchUsers()
}

// 处理每页数量变化
const handleSizeChange = () => {
  pagination.page = 1
  fetchUsers()
}

// 格式化日期
const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return date.toLocaleString('zh-CN')
}

onMounted(() => {
  fetchUsers()
})
</script>

<style lang="scss" scoped>
.users {
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
  
  .pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
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
  .users {
    .search-box {
      .el-input,
      .el-select {
        margin-bottom: 10px;
      }
    }
    
    .user-info {
      .username {
        display: none;
      }
    }
    
    .el-table {
      font-size: 12px;
    }
    
    .pagination-container {
      :deep(.el-pagination) {
        .el-pagination__sizes,
        .el-pagination__jump {
          display: none;
        }
      }
    }
  }
}
</style>
