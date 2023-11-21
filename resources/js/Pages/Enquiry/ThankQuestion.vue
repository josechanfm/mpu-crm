<template>
    <div class="p-5 bg-gray-200">
        <a href="/">
            <img src="/images/mpu_banner.png" width="300" />
        </a>
    </div>

    <div class="py-12">
        <div v-if="enquiryQuestion" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <p>感謝您與澳理大聯繫，您的查詢已收到，我們將儘快回覆。如您希望於收到回覆前再次查詢，請附上查詢編號#{{enquiryQuestion.enquiry_id}}-{{enquiryQuestion.id}}，以便跟進。</p>
                <p>Thank you for contacting MPU, your message has been received, if you need to contact us prior to receiving a response, please reference case #{{enquiryQuestion.enquiry_id}}-{{enquiryQuestion.id}}. </p>
                <a href="https://www.mpu.edu.mo/admission">https://www.mpu.edu.mo/admission</a>
            </div>
        </div>
        <div v-else class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <p>感謝您的查詢 Thank you for your enquiry.</p>
                <a href="https://www.mpu.edu.mo/admission">https://www.mpu.edu.mo/admission</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components: {
    },
    props: ['enquiryQuestion'],
    data() {
        return {
        }
    },
    methods: {
    },
}
</script>