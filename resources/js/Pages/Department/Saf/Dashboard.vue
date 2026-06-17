<template>
    <DepartmentLayout :title="department.name_zh" :department="department" :breadcrumb="breadcrumb">
        <!-- Hero / Welcome Section -->
        <div class="pt-6">
            <!-- Welcome Banner -->
            <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 rounded-2xl shadow-2xl p-8 mb-8 text-white">
                <div class="absolute inset-0 opacity-10">
                    <svg class="absolute -top-20 -right-20 w-64 h-64 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm1-13h-2v6l5.25 3.15L17 12.23l-4-2.37V7z"/>
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="inline-flex items-center justify-center w-10 h-10 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </span>
                        <h2 class="text-2xl font-bold tracking-tight">{{ department.name_zh }} 儀表板</h2>
                    </div>
                    <p class="text-indigo-100 text-lg font-light max-w-2xl">
                        歡迎來到 {{ department.name_zh }} 部門管理系統。在這裡您可以查看部門概況、管理訂單並執行日常運營任務。
                    </p>
                    <div class="flex flex-wrap items-center gap-4 mt-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/10 rounded-full text-sm text-indigo-100 backdrop-blur-sm border border-white/10">
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                            系統在線
                        </span>
                        <span class="text-indigo-200 text-sm">最後更新：{{ currentDate }}</span>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Souvenir Order Report Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-lg text-gray-800">紀念品訂單報告</h4>
                                        <p class="text-sm text-gray-400">匯出部門所有紀念品訂單數據</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    即時
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="flex -space-x-2">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 text-xs font-medium border-2 border-white">{{ $page.props?.currentUser?.name.slice(0,2) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">本月訂單 <span class="text-indigo-600 font-bold">---</span></p>
                                        <p class="text-xs text-gray-400">上次匯出：2026-06-16 14:32</p>
                                    </div>
                                </div>
                                <a :href="route('dae.souvenir.order.export')" class="w-full sm:w-auto">
                                    <a-button class="!bg-indigo-600 !border-indigo-600 !text-white hover:!bg-indigo-700 hover:!border-indigo-700 !shadow-lg !shadow-indigo-100 !px-6 !py-2.5 !rounded-xl !font-medium !transition-all !duration-300 !flex !items-center !justify-center gap-2 group">
                                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        匯出報告
                                    </a-button>
                                </a>
                            </div>
                            <!-- Progress / quick info -->
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">匯出格式</span>
                                    <span class="font-medium text-gray-700">Excel (.xlsx)</span>
                                </div>
                                <div class="flex items-center justify-between text-sm mt-1">
                                    <span class="text-gray-500">數據範圍</span>
                                    <span class="font-medium text-gray-700">2026年6月 1日 - 16日</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions / Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow duration-300">
                        <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            快捷操作
                        </h4>
                        <div class="space-y-3">
                            <button class="w-full flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-indigo-50 rounded-xl transition-all duration-200 group border border-transparent hover:border-indigo-200">
                                <span class="w-9 h-9 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 group-hover:bg-indigo-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700">新建訂單(demo)</span>
                                <svg class="w-4 h-4 ml-auto text-gray-300 group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                            <button class="w-full flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-indigo-50 rounded-xl transition-all duration-200 group border border-transparent hover:border-indigo-200">
                                <span class="w-9 h-9 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 group-hover:bg-amber-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-amber-700">待審批 (3)(demo)</span>
                                <svg class="w-4 h-4 ml-auto text-gray-300 group-hover:text-amber-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                            <button class="w-full flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-indigo-50 rounded-xl transition-all duration-200 group border border-transparent hover:border-indigo-200">
                                <span class="w-9 h-9 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 group-hover:bg-emerald-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-emerald-700">生成報告(demo)</span>
                                <svg class="w-4 h-4 ml-auto text-gray-300 group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                            <button class="w-full flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-indigo-50 rounded-xl transition-all duration-200 group border border-transparent hover:border-indigo-200">
                                <span class="w-9 h-9 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 group-hover:bg-purple-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">部門設定(demo)</span>
                                <svg class="w-4 h-4 ml-auto text-gray-300 group-hover:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                        <p>The features here are for demo purposes, no actual action yet.</p>
                    </div>
                </div>
            </div>

            <!-- Department Landing Page Content -->
            <div v-if="department.landing_page" class="mb-8">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                        <span class="text-sm font-medium text-gray-600">部門公告與資訊</span>
                    </div>
                    <div class="p-6 prose prose-indigo max-w-none" v-html="department.landing_page"></div>
                </div>
            </div>
        </div>
    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';

export default {
    components: {
        DepartmentLayout,
    },
    props: ['department'],
    data() {
        return {
            breadcrumb: [
                { label: "財務部", url: null },
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
    },
    created() {},
    mounted() {},
    methods: {},
}
</script>

<style scoped>
/* Smooth hover transitions and prose styling */
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
    @apply text-indigo-600 hover:text-indigo-800 transition-colors;
}

/* Custom animation for pulse */
@keyframes pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.4;
    }
}
.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>