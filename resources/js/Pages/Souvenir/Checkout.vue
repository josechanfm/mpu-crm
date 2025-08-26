<template>
    <BlankLayout title="Souvenir Order">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    預購畢業生限量專屬紀念品<br>
                    Pre-order of Graduation Gifts
                </h2>
                <div class="flex justify-end gap-2">
                    <div v-if="user" @click="logout">Logout<br>登出</div>
                    <div v-else @click="login">Login<br>登入</div>
                </div>
            </div>
        </template>

    <div>
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-md rounded-lg px-6">
                    <div v-if="order.items.length === 0" class="text-center text-red-600">
                        <p class="text-lg">You don't have any items selected. / 你沒有選擇任何項目。</p>
                    </div>
                    <ul v-else class="divide-y divide-gray-200">
                        <li v-for="item in order.items" :key="item.id" class="flex items-center justify-between py-4">
                            <span class="w-1/2 text-gray-700 font-medium truncate">{{ item.name }}</span>
                            <span class="w-1/4 text-gray-600 truncate">Qty: {{ item.qty }} / 數量: {{ item.qty }}</span>
                            <span class="w-1/8 text-gray-800 font-semibold">MOP${{ (item.amount).toFixed(2) }} </span>
                        </li>
                    </ul>
                    <div class="font-bold float-right">Total / 總計: MOP${{ order.amount.toFixed(2) }}</div>
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
                            <div class="pt-10">
                                <div class="font-bold">預購須知：</div>
                                <div>本人(即購買人)已知悉並接受 (1) 此預購商品及款項一經付款，不能更改或退款；(2) 小心保管付款/領取憑證，於簽領時出示；(3) 由他人代簽領，毋需事先申請，但代領人須出示簽領憑證，因此購買人將自行承擔因轉發憑證所衍生之風險及或有之損失；(4) 按照簽領指引及時間領取預購貨品，不得另約時間領取，逾期未領者，不設補領，不設退款；(5) 簽領紀念品時，當場檢查貨品，若有明顯質量問題，可即場更換，若完成簽領，則不能以任何理由提出更換；(6) 詳情可細閱 "畢業生須知"。</div>
                                <div class="font-bold">Note：</div>
                                <div>I (the buyer) understand and agree to the following (1) Once the payment is made, the order cannot be changed or refunded; (2) I will keep my payment/collection receipt and present it when picking up my item(s); (3) Collection by a third party does not require prior application, but they must present the collection receipt. In this case, the buyer will bear any risks and losses associated with forwarding the receipt or related proof to the third party; (4) Items must be collected according to the provided guidelines and schedule. No alternative collection times will be accommodated, and items not collected by the deadline will not be eligible for re-collection or refunds; (5) Check my items on the spot upon collection. If there is a clear quality issue, I may request an immediate exchange. Otherwise, no exchanges can be made for any reason once the collection is accepted; (6) For more details, I shall refer to the "Graduates' Information Kit".  </div>
                            </div>
                    </div>

                    <form method="get" :action="route('souvenir.payment.confirm')" class="mt-6 flex justify-between">
                        <input type="hidden" name="order_uuid" :value="order.uuid" />
                        <div class="flex justify-between w-full">
                            <button :href="route('souvenir')" class="bg-gray-300 text-gray-800 hover:bg-gray-400 w-full mr-2">
                                Back to Main Page <br> 返回主頁
                            </button>
                            <button type="submit" class="bg-blue-600 text-white hover:bg-blue-700 w-full">
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