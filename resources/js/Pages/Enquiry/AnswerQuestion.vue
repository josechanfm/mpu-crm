<template>
        <div class="p-5 bg-gray-200">
        <a href="/">
            <img src="/images/mpu_banner.png" width="300" />
        </a>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-10" v-for="faq in faqs">
                    <h3 class="flex items-center mb-4 text-lg font-medium text-gray-900 dark:text-white">
                        <svg class="flex-shrink-0 mr-2 w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                clip-rule="evenodd"></path>
                        </svg>
                        {{ faq.question_zh }}
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400" v-html="faq.answer_zh"></p>
                </div>
                <a-form
                    ref="refQuestion"
                    :model="followup"
                    name="Question"
                    autocomplete="off"
                    enctype="multipart/form-data"
                >
                    <!-- Check if subject only select other, jump to question box directly. -->
                    <template v-if="this.enquiry.subjects.includes('OTH') && this.enquiry.subjects.length==1">
                        <!-- dummy row, show the question box directly -->
                    </template>
                    <template v-else>
                        <p>是否尚有查詢? Still have Questions? </p>
                        <a-form-item>
                            <a-radio-group v-model:value="followup.has_question">
                                <a-radio :value='true'>是 Yes</a-radio>
                                <a-radio :value='false'>否 No</a-radio>
                            </a-radio-group>
                        </a-form-item>
                    </template>

                    <template v-if="followup.has_question">
                        <label>請寫下你的問題 Please write your question.</label>
                        <a-form-item>
                            <a-textarea v-model:value="followup.content" :rows="10"></a-textarea>
                        </a-form-item>
                        <div class="flow-root">
                            <a-form-item name="fileList">
                                <a-upload v-model:file-list="followup.fileList" list-type="picture" :beforeUpload="beforeUpload" :max-count="5">
                                    <a-button>
                                        <upload-outlined></upload-outlined>
                                        Upload
                                    </a-button>
                                </a-upload>
                            </a-form-item>
                        </div>
                    </template>
                    <a-form-item class="text-center">
                        <a-button type="primary" class="self-end" @click="onSubmits">提交 Submit</a-button>
                    </a-form-item>
                </a-form>
                
            </div>
        </div>
    </div>
</template>

<script>
import { UploadOutlined } from '@ant-design/icons-vue';
import { message, Upload } from 'ant-design-vue';
export default {
    components: {
        UploadOutlined,
        message
    },
    props: ['enquiry', 'faqs'],
    data() {
        return {
            uploadValidator:{
                fileSize:5, //Magabyte
                validFormat:['image/jpeg','image/png','application/pdf','application/zip'],
            },
            
            followup: {
                has_question: false,
                enquiry_id: this.enquiry.id,
                content: '',
                token:this.enquiry.token
            }
        }
    },
    mounted(){
        this.followup.has_question=this.enquiry.subjects.includes('OTH')
    },
    methods: {
        beforeUpload(file){
            var isOverSize=file.size/1024/1024 > this.uploadValidator.fileSize;
            var isFormatInvalid=!this.uploadValidator.validFormat.includes(file.type);
            if(isOverSize || isFormatInvalid){
                message.error({
                    content:()=>'檔案格式不符或大小超過限制. The file format does not match or the size exceeds the limit.',
                    class:'custom-class',
                    style:{
                        marginTop:'50vh'
                    }
                });
                return false || Upload.LIST_IGNORE;
            }
        },
        onSubmits() {
            if (this.followup.has_question) {
                if (this.followup.content.length == '') {
                    alert("請寫下你的問題.\nPlease write your question.");
                    return false;
                }
                this.$inertia.post(route('enquiry.submitQuestion', { enquiry: this.enquiry }), this.followup, {
                    onSuccess: (page) => {
                        this.followup=null;
                        console.log(page);
                    },
                    onError: (err) => {
                        console.log(err);
                    }
                });
            } else {
                this.$inertia.patch(route('enquiry.noQuestion', { enquiry: this.enquiry }), {
                    onSuccess: (page) => {
                        this.followup=null;
                        console.log(page);
                    },
                    onError: (err) => {
                        console.log(err);
                    }
                });
            }
        },
        formatEnquiryNumber(num){
            num='00000'+num
            num=num.substring(num.length-4)
            return '#'+num
        }
    },
}
</script>
<style>
.ant-tooltip-placement-top{
    visibility: hidden;
}
</style>