<template>
    <BlankLayout title="Souvenir Order">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    “澳理大小熊”購買專頁<br>
                    MPU Bear Order Form
                </h2>
                <div class="flex justify-end gap-2">
                    <button v-if="user" @click="logout"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200 font-medium text-sm shadow-sm hover:shadow-md flex items-center justify-center min-w-[100px]">
                        <span>Logout</span>
                        <span class="ml-1 text-red-100">/ 登出</span>
                    </button>
                    <button v-else @click="login"
                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200 font-medium text-sm shadow-sm hover:shadow-md flex items-center justify-center min-w-[100px]">
                        <span>Login</span>
                        <span class="ml-1 text-blue-100">/ 登入</span>
                    </button>
                </div>
            </div>
        </template>

    <div>
        <div class="max-w-3xl mx-auto sm:px-6 p-4 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <!-- Error header with red accent -->
                <div class="bg-red-50 px-6 py-4 border-b border-red-100">
                    <div class="flex items-center gap-3">
                        <!-- Exclamation circle icon (SVG) -->
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <h2 class="text-xl font-bold text-red-800">Order Failed</h2>
                            <p class="text-red-600 text-sm">Stock availability issue</p>
                        </div>
                    </div>
                </div>

                <!-- User greeting -->
                <div class="px-6 pt-5 pb-2 bg-white">
                    <p class="text-gray-700">
                        Dear <span class="font-semibold text-gray-900">{{ order.form_meta.netid }}</span>,
                    </p>
                    <p class="text-red-600 font-medium mt-1 flex items-center gap-2">
                        <span class="inline-block w-2 h-2 bg-red-500 rounded-full"></span>
                        We're sorry, but your order cannot be processed because one or more items are out of stock.
                    </p>
                </div>

                <!-- Order summary (failed items highlighted) -->
                <div class="px-6 py-2">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">
                            Your cart items
                        </h3>
                        <div class="space-y-3">
                            <div v-for="item in order.items" :key="item.id" 
                                class="flex justify-between items-center border-b border-gray-200 pb-2 last:border-0">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">{{ item.name }}</p>
                                    <div class="flex gap-4 text-sm text-gray-500 mt-0.5">
                                        <span>Price: MOP {{ formatPrice(item.price) }}</span>
                                        <span>Quantity: <span class="font-mono">{{ item.qty }}</span></span>
                                    </div>
                                </div>
                                <!-- Out of stock badge (optional indicator) -->
                                <div class="text-red-500 text-xs bg-red-50 px-2 py-1 rounded-full">
                                    <span>{{ failedTag(failedItems.find(i=>i.souvenir_id==item.souvenir_id)) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action buttons -->
                <div class="px-6 pb-6 pt-2 bg-white">
                    <div class="flex flex-col justify-center sm:flex-row gap-3">
                        <a :href="route('souvenir')"
                        class="inline-flex justify-center items-center gap-2 px-5 py-2.5 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-xl transition duration-200 shadow-sm hover:shadow">
                            <!-- Arrow left icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Main Page
                            <span class="text-xs text-gray-300 ml-1">返回主頁</span>
                        </a>

                        <button @click="clearCartAndRetry"
                                class="inline-flex justify-center items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl transition duration-200">
                            <!-- Refresh icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Clear Cart & Retry
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-4 text-center">
                        Please adjust quantities and try again.
                    </p>
                </div>
            </div>
        </div>
    </div>

    </BlankLayout>
</template>

<script>
import BlankLayout from '@/Layouts/BlankLayout.vue';
import { ShoppingCartOutlined } from '@ant-design/icons-vue';
import dayjs from 'dayjs';

export default {
    components: {
        BlankLayout,
        ShoppingCartOutlined,
        dayjs,
    },
    props: {
        user:{
            type: Object,
            required: false,
        },
        order: {
            type: Object,
            required: true,
        },
        failedItems:{
            type: Object,
            required: true,
        }
    },

    data() {
        return {
        };
    },
    methods: {
        login(){
            this.$inertia.get(route('souvenir.login'))
        },
        logout(){
            this.$inertia.post(route('souvenir.logout'), {}, {
                onSuccess: () => {
                    console.log('Logout successful');
                    this.isLoggedIn = false; // Update login state
                    this.user = {}; // Clear user data
                },
                onError: (error) => {
                    console.error('Logout error:', error);
                },
            });
        },
        formatPrice(price) {
            return Number(price).toFixed(2);
        },
        clearCartAndRetry() {
            // Implement cart clearing logic (e.g., emit event or call store mutation)
            this.$emit('clear-cart');
            // Then redirect to souvenir page
            window.location.href = this.route('souvenir');
        },
        failedTag(failedItem){
            console.log(failedItem)
            if(failedItem['error_code']=='10'){
                return 'User Quota Exceeded '
            }else if(failedItem['error_code']=='20'){
                return 'Out of Stock'
            }else if(failedItem['error_code']=='30'){
                return 'Not available for order'
            }else{
                return 'error';
            }
            return failedItem['error_code']
        }

    },
};
</script>

<style scoped>
/* Additional styling */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}
</style>