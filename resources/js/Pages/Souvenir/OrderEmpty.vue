<template>
    <BlankLayout title="Souvenir Order">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    “澳理大小熊”購買專頁<br>
                    MPU Bear Order Form
                </h2>
            </div>
        </template>

        <!-- 主要內容：停止販售訊息 -->
        <div class="flex justify-center items-center min-h-[60vh] py-12">
            <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 text-center border border-gray-200">
                <div class="text-5xl mb-4">🐻</div>
                <div class="text-3xl font-bold text-red-600 mb-2">⚠️ Purchase Stopped</div>
                <p class="text-gray-700 text-lg mb-4">
                    Online purchase is now stopped.<br>
                    Please wait for the next sale period.
                </p>
                <div class="border-t border-gray-200 my-4"></div>
                <p class="text-gray-700 text-lg font-medium">
                    網上購買現已停止<br>
                    請等待下一次銷售期。
                </p>
                <div class="mt-6 text-sm text-gray-500">
                    感謝您的支持與理解<br>Thank you for your support and understanding.
                </div>
            </div>
        </div>
    </BlankLayout>
</template>

<script>
import BlankLayout from '@/Layouts/BlankLayout.vue';

export default {
    components: {
        BlankLayout,
    },
};
</script>

<style scoped>
/* 保留原有樣式，無需額外修改 */
</style>