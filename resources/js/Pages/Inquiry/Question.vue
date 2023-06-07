<template>
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
                    <p>是否尚有查詢? Still have Questions? </p>
                    <a-form-item>
                        <a-radio-group v-model:value="followup.has_question">
                            <a-radio :value='true'>是 Yes</a-radio>
                            <a-radio :value='false'>否 No</a-radio>
                        </a-radio-group>
                    </a-form-item>
                    <template v-if="followup.has_question">
                        <label>請寫下你的問題 Please write your question.</label>
                        <a-form-item>
                            <a-textarea v-model:value="followup.content" :rows="10"></a-textarea>
                        </a-form-item>
                        <div class="flow-root">
                            <a-form-item name="fileList">
                                <a-upload 
                                    v-model:file-list="followup.fileList"
                                    :beforeUpload="()=>false"
                                    :max-count="10"
                                    list-type="picture"
                                >
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

export default {
    components: {
        UploadOutlined
    },
    props: ['inquiry', 'faqs'],
    data() {
        return {
            followup: {
                has_question: false,
                inquiry_id: this.inquiry.id,
                content: '',
                token:this.inquiry.token
            }
        }
    },
    methods: {
        onSubmits() {
            if (this.followup.has_question) {
                if (this.followup.content.length == '') {
                    alert("請寫下你的問題.\nPlease write your question.");
                    return false;
                }
                this.$inertia.post(route('inquiry.submitQuestion', { inquiry: this.inquiry }), this.followup, {
                    onSuccess: (page) => {
                        this.followup=null;
                        console.log(page);
                    },
                    onError: (err) => {
                        console.log(err);
                    }
                });
            } else {
                this.$inertia.patch(route('inquiry.noQuestion', { inquiry: this.inquiry }), {
                    onSuccess: (page) => {
                        this.followup=null;
                        console.log(page);
                    },
                    onError: (err) => {
                        console.log(err);
                    }
                });
            }
        }
    },
}
</script>