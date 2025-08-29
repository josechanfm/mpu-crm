<template>
    <div class="p-5 bg-gray-200">
        <a href="/">
            <img src="/storage/images/mpu_banner.png" width="300" />
        </a>
    </div>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="mt-8 p-4 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg ">
            <a-typography-title :level="4">
                <p>我還有後續問題。</p>
                <p>I still have follow-up questions.</p>
            </a-typography-title>

            <a-form 
                ref="refTicket" 
                name="ticket" 
                :model="ticket" 
                layout="vertical" 
                @finish="onFinish"
            >
                <a-form-item name="content" label="我的問題是 My question is..." :rules="{required:true,message:'請輸入您的後續問題 Please write your follow-up question'}">
                    <a-textarea v-model:value="ticket.content" :rows="10"></a-textarea>
                </a-form-item>
                <a-form-item name="fileList">
                    <a-upload v-model:file-list="ticket.fileList" list-type="picture" >
                        <a-button>
                            <upload-outlined></upload-outlined>
                            Upload
                        </a-button>
                    </a-upload>
                </a-form-item>
                <a-form-item>
                    <a-button type="primary" html-type="submit">提交 Submit</a-button>
                </a-form-item>

            </a-form>
        </div>
    </div>
</template>

<script>
import { UploadOutlined } from '@ant-design/icons-vue';
import { message, Upload } from 'ant-design-vue';

export default {
    components: {
        UploadOutlined,
        message, 
    },
    props: ['response','ticket'],
    data() {
        return {
            uploadValidator:{
                fileSize:1, //Magabyte
                validFormat:['image/jpeg','image/png','application/pdf','application/zip'],
            },
        }
    },
    created() {
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
        onFinish(){
            console.log(this.ticket);
            this.$inertia.post(route('enquiry.ticket.store'),this.ticket,{
                onSuccess:(page)=>{
                    console.log(page);
                },
                onError:(err)=>{
                    console.log(err);
                }
            });
        }

    },
}
</script>

<style>
.ant-tooltip{
    display: none;
}
</style>