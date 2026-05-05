<template>
    <DepartmentLayout :title="department.name_zh" :department="department" :breadcrumb="breadcrumb">
        
        <div class="pt-5">
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                <h4 class="font-semibold text-xl mb-4">Personnel Department Dashboard</h4>
                <p class="text-gray-600">Welcome to the DAE department! Here you can manage your souvenirs, orders, and pickups.</p>
            </div>

            <div v-html="department.landing_page" class="mb-6"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <inertia-link :href="route('dae.souvenir.users.index')">
                    <a-button class="w-full hover:bg-gray-200 transition duration-300 text-xl h-16">Users</a-button>
                </inertia-link>
                <inertia-link :href="route('dae.souvenirs.index')" as="button">
                    <a-button class="w-full hover:bg-gray-200 transition duration-300 text-xl h-16">Souvenirs</a-button>
                </inertia-link>
                <inertia-link :href="route('dae.souvenir.orders.index')">
                    <a-button class="w-full hover:bg-gray-200 transition duration-300 text-xl h-16">Orders</a-button>
                </inertia-link>
                <inertia-link :href="route('dae.souvenir.pickup')">
                    <a-button class="w-full hover:bg-gray-200 transition duration-300 text-xl h-16">Pickup</a-button>
                </inertia-link>
            </div>
        </div>

        <div class="pt-10">
            <SouvenirManual />
        </div>
        <!--
        <div>
            <div>
                <div class="font-bold">User</div>
                <div>用戶指所有NetId用戶, 包括學校、教師及職員, 可以滙入方式, 新增加修改用戶用資料。為確保歷史記錄的一致性, 所有用戶資料會被保留, 而可以設為不可購買狀態。而用戶記錄的適別欄位為NetId。</div>
                <div>滙入方式除了可以新增用戶於， 還可以用作修改用戶的購買限制，如某年滙入當年可購買的用戶名單，其限後，只需在同一名單中最後一個欄位"can_buy"設為0，再重新滙入, 系統會以第一欄netid記錄, 更新題否可以購買的狀態。</div>
            </div>
            <div>
                <div class="font-bold">Souvenirs</div>
                <div>Stock: 指紀念品的總倉存量。</div>
                <div>Quota: 指每一名用戶可購買該紀念品的上量。</div>
                <div>Available: 指該紀念品現有可供購買的存量。</div>
                <div>Available To: 指該紀念品可供購買的期限。</div>
                <div>Is Available: 指該紀念品是否開放供用戶購買。</div>
            </div>
            <div>
                <div class="font-bold">Orders</div>
                <div>指購買訂單的編表, 包括成功及未成功支付的記錄。也可滙出購訂訂單的記錄。</div>
            </div>
            <div>
                <div class="font-bold">Pickup</div>
                <div>用管理員使用, 管理員登入此項功能後, 可通過掃描購買收據上的二維碼方式, 確認用戶(購買者)購買記錄, 並可確為已認提取。注提取只可以同時一次性提取該購買單據上的所有貨品, 不提無部分提取。 </div>
            </div>
            <div>
                <div class="font-bold">用戶購買操作流程</div>
                <div>紀念品公開頁面 https://venus.mpu.edu.mo/souvenirs 顯示所有可供購買的物品, 在未登入前選購按鈕不可操作。而登入後選購按鈕上會顯示該用戶可購買的數量限制及已選購的數量, 若用戶對該項紀念品的已購買數量已超, 同樣會顯示為不可操作。</div>
            </div>
            
        </div>
        -->
    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';
import SouvenirManual from './SouvenirManual.vue'; // 請根據實際路徑調整

export default {
    components: {
        DepartmentLayout,
        SouvenirManual
    },
    props: ['department'],
    data() {
        return {
            breadcrumb: [
                { label: "學生事務處", url: null },
            ],
        };
    },
    created() {
    },
    mounted() {
    },
    methods: {
    },
}
</script>