<template>
    <BlankLayout title="Souvenir Order">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Souvenir Order Form
                </h2>
            </div>
        </template>


    <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <div v-if="order.items.length === 0" class="text-center text-red-600">
                        <p class="text-lg">You don't have any items selected. / 你沒有選擇任何項目。</p>
                    </div>
                    <ul v-else class="divide-y divide-gray-200">
                        <li v-for="item in order.items" :key="item.id" class="flex items-center justify-between py-4">
                            <span class="w-1/2 text-gray-700 font-medium truncate">{{ item.name }}</span>
                            <span class="w-1/4 text-gray-600 truncate">Qty: {{ item.qty }} / 數量: {{ item.qty }}</span>
                            <span class="w-1/4 text-gray-800 font-semibold">${{ (item.amount).toFixed(2) }} / ${{ (item.amount).toFixed(2) }}</span>
                        </li>
                    </ul>

                    <div class="mt-6">
                        <h3 class="font-semibold text-lg text-gray-800">
                            Contact Info: / 聯絡資訊：
                        </h3>
                            <div class="mt-2">
                                <div><strong>NetId / 學生號碼: </strong>{{ user.netid }}</div>
                                <div><strong>Faculty / 學院: </strong>{{ order.form_meta.faculty }}</div>
                                <div><strong>Degree / 學位: </strong>{{ order.form_meta.degree }}</div>
                                <div><strong>Phone / 電話: </strong>{{ order.form_meta.phone }}</div>
                                <div><strong>Email / 電郵: </strong>{{ order.form_meta.email }}</div>
                            </div>
                    </div>

                    <form method="get" :action="route('souvenir.payment.confirm')" class="mt-6 flex justify-between">
                        <input type="hidden" name="order_uuid" :value="order.uuid" />
                        <div class="flex justify-between w-full">
                            <button :href="route('souvenir')" class="bg-gray-300 text-gray-800 hover:bg-gray-400 w-full mr-2">
                                Back to Main Page / 返回主頁
                            </button>
                            <button type="submit" class="bg-blue-600 text-white hover:bg-blue-700 w-full">
                                Confirm to Pay / 確認付款
                            </button>
                        </div>
                    </form>
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

    },
};
</script>

<style scoped>
/* Additional styling */
</style>