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


    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div>Receipt Number # / 收據編號：{{ order.receipt_no }}</div>
                <div>Date / 日期：{{ formatDate(order.created_at).format('YYYY-MM-DD HH:mm:ss') }}</div>
                <li v-for="item in order.items" :key="item.id" class="flex items-center justify-between py-4">
                    <span class="w-1/2 text-gray-700 font-medium truncate">{{ item.name }}</span>
                    <span class="w-1/4 text-gray-600 truncate">Qty: {{ item.qty }} / 數量: {{ item.qty }}</span>
                    <span class="w-1/8 text-gray-800 font-semibold">${{ item.amount.toFixed(2) }}</span>
                </li>
                <div class="font-bold float-right">Total / 總計: ${{ order.amount.toFixed(2) }}</div>
            </div>
            <div class="text-center p-5">
                <a-button type="primary" :href="route('souvenir.order.receipt',{id:order.id, uuid:order.uuid})" target="_blank">
                    Receipt & QR code / 單據及領取二維碼
                </a-button>
            </div>
            <div class="text-center p-5"><a-button :href="route('souvenir')">Back to Main Page / 返回主頁</a-button></div>
        </div>
    </div>

    </BlankLayout>
</template>

<script>
import BlankLayout from '@/Layouts/BlankLayout.vue';
import { ShoppingCartOutlined } from '@ant-design/icons-vue';
import { baseSelectPropsWithoutPrivate } from 'ant-design-vue/es/vc-select/BaseSelect';
import dayjs from 'dayjs';

export default {
    components: {
        BlankLayout,
        ShoppingCartOutlined,
        dayjs,
    },
    props: {
        order:{
            type: Object,
            required: false,
        },
    },

    data() {
        return {
        };
    },
    methods: {
        formatDate(date) {
            return dayjs(date);
        }
    },
};
</script>

<style scoped>
/* Additional styling */
</style>