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
            <div class="max-w-3xl mx-auto sm:px-6 p-4">
                <div class="bg-white shadow-md rounded-lg p-4">
                    <div v-if="`order.items`.length === 0" class="text-center text-red-600">
                        <p class="text-lg">You don't have any items selected. / 你沒有選擇任何項目。</p>
                    </div>
                    <ul v-else class="divide-gray-200">
                        <li v-for="item in order.items" :key="item.id" class="flex items-center justify-between py-4">
                            <span class="w-3/4 text-gray-700 font-medium truncate">{{ item.name }} ( x {{ item.qty }} )</span>
                            <span class="w-1/4 text-gray-800 font-semibold">MOP${{ (item.amount).toFixed(2) }} </span>
                        </li>
                    </ul>
                    <div class="font-bold float-right">Total / 總計: MOP${{ order.amount.toFixed(2) }}</div>
                    <div class="mt-10">
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
                            <a :href="route('souvenir')" class="rounded-md bg-gray-300 text-gray-800 hover:bg-gray-400 w-full mr-2 text-center">
                                Back to Main Page <br> 返回主頁
                            </a>
                            <button type="submit" class="rounded-md bg-blue-600 text-white hover:bg-blue-700 w-full">
                                Confirm to Pay <br> 確認付款
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