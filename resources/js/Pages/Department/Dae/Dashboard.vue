<template>
    <DepartmentLayout :title="department.name_zh" :department="department" :breadcrumb="breadcrumb">
        <div class="pt-6">
            <!-- 歡迎橫幅 -->
            <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-cyan-600 to-teal-700 rounded-2xl shadow-2xl p-8 mb-8 text-white">
                <div class="absolute inset-0 opacity-10">
                    <svg class="absolute -top-20 -right-20 w-64 h-64 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm1-13h-2v6l5.25 3.15L17 12.23l-4-2.37V7z"/>
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="inline-flex items-center justify-center w-10 h-10 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </span>
                        <h2 class="text-2xl font-bold tracking-tight">{{ department.name_zh }} 管理儀表板</h2>
                    </div>
                    <p class="text-indigo-100 text-lg font-light max-w-2xl">
                        歡迎使用 {{ department.name_zh }} 管理系統。您可在此管理紀念品、訂單、領取作業及使用者資訊，輕鬆掌握部門運作。
                    </p>
                    <div class="flex flex-wrap items-center gap-4 mt-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/10 rounded-full text-sm text-indigo-100 backdrop-blur-sm border border-white/10">
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                            系統運作中
                        </span>
                        <span class="text-indigo-200 text-sm">最後更新：{{ currentDate }}</span>
                    </div>
                </div>
            </div>

            <!-- 數據總覽卡片 -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow duration-300 group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">總紀念品</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ stats.totalSouvenirs ?? 0 }}</p>
                            <p class="text-xs text-green-500 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                </svg>
                                較上月 +5.2%
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow duration-300 group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">待處理訂單</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ stats.pendingOrders ?? 12 }}</p>
                            <p class="text-xs text-amber-500 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                需優先處理
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow duration-300 group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">今日領取</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ stats.todayPickups ?? 8 }}</p>
                            <p class="text-xs text-blue-500 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                已預約
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-cyan-50 rounded-xl flex items-center justify-center text-cyan-600 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow duration-300 group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">使用者</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ stats.totalUsers ?? 156 }}</p>
                            <p class="text-xs text-purple-500 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                +3 新註冊
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 部門公告與資訊 -->
            <div v-if="department.landing_page" class="mb-8">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                        <span class="text-sm font-medium text-gray-600">部門公告與資訊</span>
                    </div>
                    <div class="p-6 prose prose-blue max-w-none" v-html="department.landing_page"></div>
                </div>
            </div>

            <!-- 功能快捷入口（四項主要操作） -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <inertia-link :href="route('dae.souvenir.users.index')" class="block group">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 hover:border-blue-300">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 group-hover:bg-blue-200 group-hover:scale-110 transition-all duration-300 mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h5 class="text-lg font-bold text-gray-800 group-hover:text-blue-700 transition-colors">使用者管理</h5>
                            <p class="text-sm text-gray-400 mt-1">管理部門成員帳號與權限</p>
                            <span class="mt-3 inline-flex items-center text-sm font-medium text-blue-600 group-hover:text-blue-800">
                                前往
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </inertia-link>

                <inertia-link :href="route('dae.souvenirs.index')" class="block group">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 hover:border-emerald-300">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-200 group-hover:scale-110 transition-all duration-300 mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <h5 class="text-lg font-bold text-gray-800 group-hover:text-emerald-700 transition-colors">紀念品管理</h5>
                            <p class="text-sm text-gray-400 mt-1">新增、編輯或下架紀念品</p>
                            <span class="mt-3 inline-flex items-center text-sm font-medium text-emerald-600 group-hover:text-emerald-800">
                                前往
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </inertia-link>

                <inertia-link :href="route('dae.souvenir.orders.index')" class="block group">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 hover:border-amber-300">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center text-amber-600 group-hover:bg-amber-200 group-hover:scale-110 transition-all duration-300 mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <h5 class="text-lg font-bold text-gray-800 group-hover:text-amber-700 transition-colors">訂單管理</h5>
                            <p class="text-sm text-gray-400 mt-1">檢視、處理及追蹤訂單</p>
                            <span class="mt-3 inline-flex items-center text-sm font-medium text-amber-600 group-hover:text-amber-800">
                                前往
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </inertia-link>

                <inertia-link :href="route('dae.souvenir.pickup')" class="block group">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 hover:border-purple-300">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center text-purple-600 group-hover:bg-purple-200 group-hover:scale-110 transition-all duration-300 mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                            </div>
                            <h5 class="text-lg font-bold text-gray-800 group-hover:text-purple-700 transition-colors">領取作業</h5>
                            <p class="text-sm text-gray-400 mt-1">管理紀念品領取與簽收</p>
                            <span class="mt-3 inline-flex items-center text-sm font-medium text-purple-600 group-hover:text-purple-800">
                                前往
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </inertia-link>
            </div>

            <!-- 紀念品操作手冊元件 -->
            <div class="mt-8">
                <SouvenirManual />
            </div>
        </div>
    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';
import SouvenirManual from './SouvenirManual.vue';

export default {
    components: {
        DepartmentLayout,
        SouvenirManual
    },
    props: {
        department: Object,
        // 可選：從父層傳入統計數據，若無則顯示預設值
        stats: {
            type: Object,
            default: () => ({
                totalSouvenirs: 0,
                pendingOrders: 12,
                todayPickups: 8,
                totalUsers: 156
            })
        }
    },
    data() {
        return {
            breadcrumb: [
                { label: "學生事務處", url: null },
            ],
        };
    },
    computed: {
        currentDate() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            return `${year}-${month}-${day} ${hours}:${minutes}`;
        }
    }
}
</script>

<style scoped>
.prose img {
    @apply rounded-lg shadow-md max-w-full;
}
.prose h1,
.prose h2,
.prose h3,
.prose h4 {
    @apply text-gray-800 font-semibold;
}
.prose p {
    @apply text-gray-600 leading-relaxed;
}
.prose ul,
.prose ol {
    @apply text-gray-600;
}
.prose a {
    @apply text-blue-600 hover:text-blue-800 transition-colors;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}
.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>