<template>
    <DepartmentLayout title="招聘通告" :breadcrumb="breadcrumb">
        <div class="mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto p-5">
                <a-form ref="formRef" :model="notification" name="formVacancy" :label-col="{ style:{width:'150px'}  }" :wrapper-col="{ span: 20 }"
                autocomplete="off" :rules="rules" :validate-messages="validateMessages" @finish="onFormSubmit">
                    <a-form-item label="標題(中文)" name="title_zh">
                        <a-input v-model:value="notification.title_zh"/>
                    </a-form-item>
                    <a-form-item label="標題(英文)" name="title_en">
                        <a-input v-model:value="notification.title_en"/>
                    </a-form-item>
                    <a-form-item label="標題(葡文)" name="title_pt">
                        <a-input v-model:value="notification.title_pt"/>
                    </a-form-item>
                    <a-form-item label="開始日" name="date_start">
                        <a-date-picker v-model:value="notification.date_start" :format="dateFormat" :valueFormat="dateFormat" />
                        (yyyy-mm-dd)
                    </a-form-item>
                    <a-form-item label="截止日" name="date_end">
                        <a-date-picker v-model:value="notification.date_end" :format="dateFormat" :valueFormat="dateFormat" />
                        (yyyy-mm-dd) hh:mm:ss
                    </a-form-item>
                    <a-form-item label="附件文檔(中文)" name="file_zh" extra="Document in Chinese, PDF file only">
                        <a-upload
                            v-model:fileList="notification.file_zh"
                            name="file_zh"
                        >
                            <a-button>
                            <template #icon><UploadOutlined /></template>
                            上載文檔
                            </a-button>
                        </a-upload>
                    </a-form-item>                    
                    <a-form-item label="附件文檔(英文)" name="file_en" extra="Document in English, PDF file only">
                        <a-upload
                            v-model:fileList="notification.file_en"
                            name="file_en"
                        >
                            <a-button>
                            <template #icon><UploadOutlined /></template>
                            上載文檔
                            </a-button>
                        </a-upload>
                    </a-form-item>                    
                    <a-form-item label="附件文檔(葡文)" name="file_pt" extra="Document in Portuguese, PDF file only">
                        <a-upload
                            v-model:fileList="notification.file_pt"
                            name="file_pt"
                        >
                            <a-button>
                            <template #icon><UploadOutlined /></template>
                            上載文檔
                            </a-button>
                        </a-upload>
                    </a-form-item>                    
                   <a-form-item label="下載" name="can_download">
                        <a-radio-group v-model:value="notification.can_download" button-style="solid">
                            <a-radio-button :value="true">可下載</a-radio-button>
                            <a-radio-button :value="false">有條件下載</a-radio-button>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item label="報名" name="can_apply">
                        <a-radio-group v-model:value="notification.can_apply" button-style="solid">
                            <a-radio-button :value="true">可報名</a-radio-button>
                            <a-radio-button :value="false">不可報名</a-radio-button>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item label="發報" name="published">
                        <a-radio-group v-model:value="notification.published" button-style="solid">
                            <a-radio-button :value="true">已發報</a-radio-button>
                            <a-radio-button :value="false">未發報</a-radio-button>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item :wrapper-col="{ span: 14, offset: 10 }">
                        <a-button type="primary" html-type="submit" class="mr-5">保存</a-button>
                        <inertia-link :href="route('personnel.recruitment.vacancies.index')" class="ant-btn">退出並返回</inertia-link>
                    </a-form-item>
                </a-form>
            </div>
        </div>
    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from "@/Layouts/DepartmentLayout.vue";
import {
    UploadOutlined,
    LoadingOutlined,
    PlusOutlined,
    InfoCircleFilled,
} from "@ant-design/icons-vue";
import Icon, { RestFilled } from "@ant-design/icons-vue";
import { quillEditor, Quill } from "vue3-quill";
import { message, notification } from "ant-design-vue";
import dayjs from 'dayjs';
import axios from "axios";

export default {
    components: {
        DepartmentLayout,
        UploadOutlined,
        LoadingOutlined,
        PlusOutlined,
        RestFilled,
        quillEditor,
        message,
        dayjs
    },
    props: ["vacancy","notification"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"職位招聘" ,url:route('personnel.recruitment.vacancies.index')},
                {label:"招聘通告" ,url:route('personnel.recruitment.vacancy.notifications.index',this.vacancy.id)},
                {label:this.notification.id?"修改":"新增" ,url:null},
            ],
            dateFormat: "YYYY-MM-DD",
            rules:{
                title_zh: { required: true },
                date_start: { required: true },
                date_end: { required: true },
                can_download: { required: true },
                can_apply: { required: true },
                published: { required: true },
            },
            validateMessages: {
                required: '${label} is required!',
                types: {
                    email: '${label} is not a valid email!',
                    number: '${label} is not a valid number!',
                },
                number: {
                    range: '${label} must be between ${min} and ${max}',
                },
            },

        };
    },
    created() {

    },
    mounted() {
    }, 
    methods: {
        onFormSubmit(){
            if(this.notification.id){
                console.log('update');
                console.log(this.notification);
                console.log({vacancy:this.notification.rec_vacancy_id,notification:this.notification.id});
                this.$inertia.patch(route('personnel.recruitment.vacancy.notifications.update',{
                    vacancy:this.notification.rec_vacancy_id,
                    notification:this.notification.id
                }), this.notification, {
                        onSuccess: (page) => {
                            console.log(page.data);
                        },
                        onError: (err) => {
                            console.log(err);
                        }
                    }
                );
            }else{
                console.log('create');
                this.$inertia.post(route("personnel.recruitment.vacancy.notifications.store",{
                    vacancy:this.notification.rec_vacancy_id,
                }), Object.assign({},this.notification), {
                        onSuccess: (page) => {
                            console.log(page);
                        },
                        onError: (err) => {
                            console.log(err);
                        },
                    }
                );
            }
        }
    },
};
</script>
