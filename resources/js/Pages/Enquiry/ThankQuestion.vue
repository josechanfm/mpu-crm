<template>
    <div class="p-5 bg-gray-200">
        <a href="/">
            <img src="/images/mpu_banner.png" width="300" />
        </a>
    </div>

    <div class="py-12">
        <div v-if="enquiryQuestion" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <p>{{ lang.enquiry_thank_question }}</p>
                <p>{{ lang.enquiry_number }}#{{enquiryQuestion.enquiry_id}}-{{enquiryQuestion.id}}</p>
                <a href="https://www.mpu.edu.mo/admission">https://www.mpu.edu.mo/admission</a>
            </div>
        </div>
        <div v-else class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <p>{{ lang.enquiry_thanks }}</p>
                <a href="https://www.mpu.edu.mo/admission">https://www.mpu.edu.mo/admission</a>
            </div>
        </div>
    </div>
</template>

<script>
import enquiryLang  from '/lang/enquiry.json';

export default {
    components: {
        
    },
    props: ['enquiryQuestion'],
    data() {
        return {
            lang:{}
        }
    },
    created(){
        this.lang=enquiryLang[document.documentElement.lang];
    },
    mounted(){
        
    },
    methods: {
        

    },
}
</script>