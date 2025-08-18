<template>
    <WebLayout title="學生事務處">
        <a-typography-title :level="3">{{ department?.name_zh }}</a-typography-title>
        <div class="container mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                Pickup orders
            </div>
            {{ department }}
              <div>
                <qrcode-stream></qrcode-stream>
                <qrcode-drop-zone></qrcode-drop-zone>
                <qrcode-capture></qrcode-capture>
            </div>
        </div>
    </WebLayout>
</template>

<script>
import WebLayout from '@/Layouts/WebLayout.vue';
import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'


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
            breadcrumb:[
                {label:"學生事務處" ,url:null},
            ],
            qrCode: null,

        }
    },
    created(){

    },
    mounted(){
    },
    methods: {
        onDecode(decodedString) {
        this.qrCode = decodedString;
        },
    },
}
</script>