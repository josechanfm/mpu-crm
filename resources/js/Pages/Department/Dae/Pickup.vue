<template>
    <WebLayout title="學生事務處">
        <a-typography-title :level="3">{{ department?.name_zh }}</a-typography-title>
        <div class="container mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto p-5">
                <a-button type="primary" ghost @click="toggleSanner">{{ scannerActive ? 'Turn Off Scanner' : 'Turn On Scanner' }}</a-button>
                <hr>
                <h2 class="text-lg font-semibold">Pickup Orders</h2>
                <div class="mt-5" v-if="scannerActive">
                    <QrcodeStream @decode="onDecode" @error="onError" />
                    <QrcodeDropZone @decode="onDecode" @error="onError" />
                    <QrcodeCapture @decode="onDecode" @error="onError" />
                </div>
                <div v-if="responseData">
                    Pickup items:
                    <div>{{ responseData.user.netid }}</div>
                    <div>{{ responseData.user.phone }}</div>
                    <hr>
                    <div v-for="order in responseData.user.orders">
                        <div>{{ order.created_at }}</div>
                        <div>
                            <span v-if="order.status==null" class="text-yellow-500">未支付</span>
                            <span v-else-if="order.status==0" class="text-red-500">支付失敗</span>
                            <span v-else-if="order.status==1" class="text-red-800">已支付</span>
                            <span v-else-if="order.status==2" class="text-blue-500">確認支付</span>
                            <span v-else-if="order.status==3" class="text-green-500">已領取</span>
                        </div>
                        <div v-for="item in order.items">
                            {{ item.qty }} : {{  item.name }} 
                        </div>
                        <hr>
                    </div>
                    <div class="flex justify-between items-center pt-4">
                        <a-button type="primary" @click="pickupConfirm" :disabled="responseData.user.orders.find(p=>p.status==2)==null">Confirm</a-button>
                        <a-button @click="clearData">Clear</a-button>
                    </div>
                    
                </div>
            </div>
                

        </div>
    </WebLayout>
</template>

<script>
import WebLayout from '@/Layouts/WebLayout.vue';
import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'qrcode-reader-vue3';
import axios from 'axios';

export default {
    components: {
        WebLayout,
        QrcodeStream,
        QrcodeDropZone,
        QrcodeCapture
    },
    props: ['department'],
    data() {
        return {
            breadcrumb: [
                { label: "學生事務處", url: null },
            ],
            decodedData: null,
            scannerActive: false,
            responseData:null,
        };
    },
    methods: {
        clearData(){
            this.decodedData=null
            this.scannerActive=false
            this.responseData=null
        },
        toggleSanner() {
            this.scannerActive = !this.scannerActive; // Toggle camera state
            this.responseData=null;
        },
        async onDecode(data) {
            this.decodedData = data; // Store decoded data
            this.errorMessage = ''; // Clear any previous error

            // Turn off the camera after successful read
            this.scannerActive = false;

            // Make the Axios request
            try {
                const response = await axios.get(route('dae.souvenir.pickupCode'), {
                    params: { code: data } // Send the decoded data as a query parameter
                });
                // Handle the response data
                this.responseData=response.data;
                this.scannerActive = false;
                // You can update the UI with the response data if needed
            } catch (error) {
                this.errorMessage = 'Failed to fetch data: ' + error.message;
            }
        },
        onError(error) {
            this.errorMessage = error.message; // Display error message
        },
        async pickupConfirm() {
            try {
                const response = await axios.post(route('dae.souvenir.pickupConfirm'), {
                    code: this.decodedData // Send the decoded data
                });
                // Handle the successful response
                this.$notification.success({
                    message: 'Pickup confirmed: ',
                    description: JSON.stringify(response.data)
                });
                console.log(response.data)
            } catch (error) {
                this.$notification.warning({
                    message: 'Failed to confirm pickup',
                    description: JSON.stringify(error)
                    //description:'Something when wrong, please try again or content system admin.',
                });    
                console.log(error)
            }
        }
    },
}
</script>

<style scoped>
.decode-result {
    margin-top: 10px;
}
</style>