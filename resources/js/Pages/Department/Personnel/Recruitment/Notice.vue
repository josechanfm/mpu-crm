<template>
    <DepartmentLayout title="招聘通告" :breadcrumb="breadcrumb">
        
        <div class="mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto p-5">
                <a-form ref="formRef" :model="notice" name="formVacancy" :label-col="{ style:{width:'150px'}  }" :wrapper-col="{ span: 20 }"
                autocomplete="off" :rules="rules" :validate-messages="validateMessages" @finish="onFormSubmit">
                    <a-form-item label="標題(中文)" name="title_zh">
                        <a-input v-model:value="notice.title_zh"/>
                    </a-form-item>
                    <a-form-item label="標題(英文)" name="title_en">
                        <a-input v-model:value="notice.title_en"/>
                    </a-form-item>
                    <a-form-item label="標題(葡文)" name="title_pt">
                        <a-input v-model:value="notice.title_pt"/>
                    </a-form-item>
                    <a-form-item label="開始日" name="date_start">
                        <a-date-picker v-model:value="notice.date_start" :format="dateFormat" :valueFormat="dateFormat" />
                        (yyyy-mm-dd)
                    </a-form-item>
                    <a-form-item label="截止日" name="date_end">
                        <a-date-picker v-model:value="notice.date_end" :show-time="{ format: 'HH:mm' }" :format="dateTimeFormat" :valueFormat="dateTimeFormat" />
                        (yyyy-mm-dd) hh:mm
                    </a-form-item>
                    
                    <a-form-item label="附件文檔(中文)" name="upload_file_zh" extra="Document in Chinese, PDF file only">
                        <template v-if="notice.file_zh">
                            <inertia-link :href="route('personnel.recruitment.notice.deleteMedia',notice.file_zh)"
                                class="float-right text-red-500">
                                <svg focusable="false" class="" data-icon="delete" width="1em" height="1em" fill="currentColor"
                                    aria-hidden="true" viewBox="64 64 896 896">
                                    <path
                                        d="M360 184h-8c4.4 0 8-3.6 8-8v8h304v-8c0 4.4 3.6 8 8 8h-8v72h72v-80c0-35.3-28.7-64-64-64H352c-35.3 0-64 28.7-64 64v80h72v-72zm504 72H160c-17.7 0-32 14.3-32 32v32c0 4.4 3.6 8 8 8h60.4l24.7 523c1.6 34.1 29.8 61 63.9 61h454c34.2 0 62.3-26.8 63.9-61l24.7-523H888c4.4 0 8-3.6 8-8v-32c0-17.7-14.3-32-32-32zM731.3 840H292.7l-24.2-512h487l-24.2 512z">
                                    </path>
                                </svg>
                            </inertia-link>
                            <img :src="notice.file_zh.preview_url" width="100"/>
                        </template>
                        <template v-else>
                            <a-upload
                                v-model:fileList="notice.upload_file_zh"
                                name="file_zh"
                                accept="image/png, image/jpeg"
                                :max-count="1"
                            >
                                <a-button>
                                <template #icon><UploadOutlined /></template>
                                上載文檔
                                </a-button>
                            </a-upload>
                        </template>
                    </a-form-item>

                    <a-form-item label="附件文檔(英文)" name="upload_file_en" extra="Document in Chinese, PDF file only">
                        <template v-if="notice.file_en">
                            <inertia-link :href="route('personnel.recruitment.notice.deleteMedia',notice.file_en)"
                                class="float-right text-red-500">
                                <svg focusable="false" class="" data-icon="delete" width="1em" height="1em" fill="currentColor"
                                    aria-hidden="true" viewBox="64 64 896 896">
                                    <path
                                        d="M360 184h-8c4.4 0 8-3.6 8-8v8h304v-8c0 4.4 3.6 8 8 8h-8v72h72v-80c0-35.3-28.7-64-64-64H352c-35.3 0-64 28.7-64 64v80h72v-72zm504 72H160c-17.7 0-32 14.3-32 32v32c0 4.4 3.6 8 8 8h60.4l24.7 523c1.6 34.1 29.8 61 63.9 61h454c34.2 0 62.3-26.8 63.9-61l24.7-523H888c4.4 0 8-3.6 8-8v-32c0-17.7-14.3-32-32-32zM731.3 840H292.7l-24.2-512h487l-24.2 512z">
                                    </path>
                                </svg>
                            </inertia-link>
                            <img :src="notice.file_en.preview_url" width="100"/>
                        </template>
                        <template v-else>
                            <a-upload
                                v-model:fileList="notice.upload_file_en"
                                name="file_en"
                                accept="image/png, image/jpeg"
                                :max-count="1"
                            >
                                <a-button>
                                <template #icon><UploadOutlined /></template>
                                上載文檔
                                </a-button>
                            </a-upload>
                        </template>
                    </a-form-item>
                    
                    <a-form-item label="附件文檔(葡文)" name="upload_file_pt" extra="Document in Chinese, PDF file only">
                        <template v-if="notice.file_pt">
                            <inertia-link :href="route('personnel.recruitment.notice.deleteMedia',notice.file_pt)"
                                class="float-right text-red-500">
                                <svg focusable="false" class="" data-icon="delete" width="1em" height="1em" fill="currentColor"
                                    aria-hidden="true" viewBox="64 64 896 896">
                                    <path
                                        d="M360 184h-8c4.4 0 8-3.6 8-8v8h304v-8c0 4.4 3.6 8 8 8h-8v72h72v-80c0-35.3-28.7-64-64-64H352c-35.3 0-64 28.7-64 64v80h72v-72zm504 72H160c-17.7 0-32 14.3-32 32v32c0 4.4 3.6 8 8 8h60.4l24.7 523c1.6 34.1 29.8 61 63.9 61h454c34.2 0 62.3-26.8 63.9-61l24.7-523H888c4.4 0 8-3.6 8-8v-32c0-17.7-14.3-32-32-32zM731.3 840H292.7l-24.2-512h487l-24.2 512z">
                                    </path>
                                </svg>
                            </inertia-link>
                            <img :src="notice.file_pt.preview_url" width="100"/>
                        </template>
                        <template v-else>
                            <a-upload
                                v-model:fileList="notice.upload_file_pt"
                                name="file_pt"
                                accept="image/png, image/jpeg"
                                :max-count="1"
                            >
                                <a-button>
                                <template #icon><UploadOutlined /></template>
                                上載文檔
                                </a-button>
                            </a-upload>
                        </template>
                    </a-form-item>

                   <a-form-item label="下載" name="can_download">
                        <a-radio-group v-model:value="notice.can_download" button-style="solid">
                            <a-radio-button :value="true">可下載</a-radio-button>
                            <a-radio-button :value="false">有條件下載</a-radio-button>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item label="報名" name="can_apply">
                        <a-radio-group v-model:value="notice.can_apply" button-style="solid">
                            <a-radio-button :value="true">可報名</a-radio-button>
                            <a-radio-button :value="false">不可報名</a-radio-button>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item label="發報" name="published">
                        <a-radio-group v-model:value="notice.published" button-style="solid">
                            <a-radio-button :value="true">已發報</a-radio-button>
                            <a-radio-button :value="false">未發報</a-radio-button>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item :wrapper-col="{ span: 14, offset: 10 }">
                        <inertia-link :href="route('personnel.recruitment.vacancy.notices.index',vacancy.id)" class="ant-btn">退出並返回</inertia-link>
                        <a-button type="primary" html-type="submit" class="mr-5">保存</a-button>
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
import { onBeforeUpdate } from "vue";

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
    props: ["vacancy","notice"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"職位招聘" ,url:route('personnel.recruitment.vacancies.index')},
                {label:"招聘通告" ,url:route('personnel.recruitment.vacancy.notices.index',this.vacancy.id)},
                {label:this.notice.id?"修改":"新增" ,url:null},
            ],
            dateFormat: "YYYY-MM-DD",
            dateTimeFormat: "YYYY-MM-DD HH:mm",
            rules:{
                title_zh: { required: true },
                title_en: { required: true },
                title_pt: { required: true },
                upload_file_zh: { required: true },
                upload_file_en: { required: true },
                upload_file_pt: { required: true },
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
        this.refresh();
    },
    mounted() {
    }, 
    methods: {
        refresh(){
            this.notice.file_zh=this.notice.media.find(m=>m.collection_name=='noticeFileZh');
            this.notice.file_en=this.notice.media.find(m=>m.collection_name=='noticeFileEn');
            this.notice.file_pt=this.notice.media.find(m=>m.collection_name=='noticeFilePt');
        },
        onBeforeUpload(file){
            console.log(file)
        },
        onFormSubmit(){
            if(this.notice.id){
                console.log('update');
                console.log(this.notice);
                console.log({vacancy:this.notice.rec_vacancy_id,notice:this.notice.id});
                this.notice._method = "PATCH";
                this.$inertia.post(route('personnel.recruitment.vacancy.notices.update',{
                    vacancy:this.notice.rec_vacancy_id,
                    notice:this.notice.id
                }), this.notice, {
                        onSuccess: (page) => {
                            console.log(page.data);
                            this.refresh();
                        },
                        onError: (err) => {
                            console.log(err);
                        }
                    }
                );
            }else{
                console.log('create');
                this.$inertia.post(route("personnel.recruitment.vacancy.notices.store",{
                    vacancy:this.notice.rec_vacancy_id,
                }), Object.assign({},this.notice), {
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
