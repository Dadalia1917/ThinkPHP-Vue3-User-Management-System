<template>
	<view class="container">
		<!-- 页面内容 -->
	</view>
</template>

<script>
	export default {
		onLaunch: function() {
			console.log('App Launch')
			// 应用启动时初始化
			this.initApp()
		},
		onShow: function() {
			console.log('App Show')
		},
		onHide: function() {
			console.log('App Hide')
		},
		methods: {
			initApp() {
				// 检查登录状态
				const token = uni.getStorageSync('token')
				if (token) {
					// 验证token有效性
					this.checkTokenValid()
				}
			},
			checkTokenValid() {
				// 这里可以调用后端接口验证token
				// 如果token无效，清除本地存储并跳转到登录页
			}
		}
	}
</script>

<style lang="scss">
	/* 全局样式 */
	page {
		background-color: #f5f5f5;
		font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
	}
	
	.container {
		min-height: 100vh;
	}
	
	/* 通用样式 */
	.button-primary {
		background-color: #007aff;
		color: #ffffff;
		border: none;
		border-radius: 8rpx;
		padding: 24rpx 48rpx;
		font-size: 32rpx;
	}
	
	.button-secondary {
		background-color: #ffffff;
		color: #007aff;
		border: 2rpx solid #007aff;
		border-radius: 8rpx;
		padding: 24rpx 48rpx;
		font-size: 32rpx;
	}
	
	.form-item {
		margin-bottom: 40rpx;
	}
	
	.form-label {
		font-size: 28rpx;
		color: #333333;
		margin-bottom: 16rpx;
	}
	
	.form-input {
		width: 100%;
		padding: 24rpx;
		border: 2rpx solid #e0e0e0;
		border-radius: 8rpx;
		font-size: 32rpx;
		background-color: #ffffff;
	}
	
	.text-center {
		text-align: center;
	}
	
	.text-primary {
		color: #007aff;
	}
	
	.text-secondary {
		color: #666666;
	}
	
	.text-danger {
		color: #ff3b30;
	}
	
	.mt-20 {
		margin-top: 40rpx;
	}
	
	.mb-20 {
		margin-bottom: 40rpx;
	}
	
	.p-20 {
		padding: 40rpx;
	}
</style>
