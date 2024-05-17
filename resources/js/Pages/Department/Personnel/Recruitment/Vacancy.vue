<template>
    <DepartmentLayout title="職位招聘" :breadcrumb="breadcrumb">
        <div class="mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto p-5">
                <a-form ref="formRef" :model="vacancy" name="formVacancy" :label-col="{ style:{width:'150px'}  }" :wrapper-col="{ span: 20 }"
                autocomplete="off" :rules="rules" :validate-messages="validateMessages" @finish="onFormSubmit">
                    <a-form-item label="招聘類別" name="type">
                        <a-select v-model:value="vacancy.type" :options="recruitmentTypes"/>
                    </a-form-item>
                    <a-form-item label="招聘職位" name="code">
                        <a-select v-model:value="vacancy.code" show-search :options="workflows.map(w=>({value:w.code,label:w.code+' : '+w.title_c}))" @change="onVacancyCodeChange"/>
                    </a-form-item>
                    <a-form-item label="職位名稱(中文)" name="title_zh">
                        <a-input v-model:value="vacancy.title_zh"/>
                    </a-form-item>
                    <a-form-item label="職位名稱(英文)" name="title_en">
                        <a-input v-model:value="vacancy.title_en"/>
                    </a-form-item>
                    <a-form-item label="職位名稱(葡文)" name="title_pt">
                        <a-input v-model:value="vacancy.title_pt"/>
                    </a-form-item>
                    <a-form-item label="教育程度" name="education">
                        <a-select v-model:value="vacancy.education" :options="educations.value" :fieldNames="{value:'value',label:'label_zh'}"/>
                    </a-form-item>
                    <a-form-item label="駕駛執照" name="vehicle">
                        <a-select v-model:value="vacancy.vehicle" :options="vehicles.value" :fieldNames="{value:'value',label:'label_zh'}"/>
                    </a-form-item>
                    <a-form-item label="簡介" name="description">
                        <a-textarea v-model:value="vacancy.description" />
                    </a-form-item>
                    <a-form-item label="開始日" name="date_start">
                        <a-date-picker v-model:value="vacancy.date_start" :format="dateFormat" :valueFormat="dateFormat" />
                        (yyyy-mm-dd)
                    </a-form-item>
                    <a-form-item label="截止日" name="date_end">
                        <a-date-picker v-model:value="vacancy.date_end" :show-time="{ format: 'HH:mm' }" :format="dateTimeFormat" :valueFormat="dateTimeFormat" />
                        (yyyy-mm-dd) hh:mm:ss
                    </a-form-item>
                    <a-form-item label="補充文件開始日" name="supplement_start">
                        <a-date-picker v-model:value="vacancy.supplement_start" :format="dateFormat" :valueFormat="dateFormat" />
                        (yyyy-mm-dd)
                    </a-form-item>
                    <a-form-item label="補充文件截止日" name="supplement_end">
                        <a-date-picker v-model:value="vacancy.supplement_end" :show-time="{ format: 'HH:mm' }" :format="dateTimeFormat" :valueFormat="dateTimeFormat" />
                        (yyyy-mm-dd) hh:mm:ss
                    </a-form-item>
                    <a-form-item label="發報日期" name="date_publish">
                        <a-date-picker v-model:value="vacancy.date_publish" :format="dateFormat" :valueFormat="dateFormat" />
                        (yyyy-mm-dd)
                    </a-form-item>
                   <a-form-item label="程序狀態" name="progress">
                        <a-radio-group v-model:value="vacancy.progress" button-style="solid">
                            <a-radio-button :value="true">程序進行中</a-radio-button>
                            <a-radio-button :value="false">程序完成</a-radio-button>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item label="綜合狀態" name="active">
                        <a-radio-group v-model:value="vacancy.active" button-style="solid">
                            <a-radio-button :value="true">有效</a-radio-button>
                            <a-radio-button :value="false">無效</a-radio-button>
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
import { message } from "ant-design-vue";
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
    props: ['workflows','vacancy','educations','vehicles'],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"職位招聘" ,url:null},
            ],
            dateFormat: "YYYY-MM-DD",
            dateTimeFormat: "YYYY-MM-DD HH:mm",
            recruitmentTypes:[
                {value:"ACA",label:"教職人員"},
                {value:"ADM",label:"非教職人員"}
            ],
            columns: [
                {
                    title: "員工編號",
                    dataIndex: "type",
                    width: 90,
                }, {
                    title: "姓名(中文)",
                    dataIndex: "code",
                    width: 100,
                }, {
                    title: "姓名(外文)",
                    dataIndex: "name_fr",
                }, {
                    title: "電郵地址",
                    dataIndex: "email",
                }, {
                    title: "產生申報義務日期",
                    dataIndex: "date_start",
                    width: 150,
                }, {
                    title: "期提醒日期(60日)",
                    dataIndex: "date_remind",
                    width: 150,
                }, {
                    title: "到期日期(90日)",
                    dataIndex: "date_due",
                    width: 140,
                }, {
                    title: "已發送電郵",
                    dataIndex: "sent_email_count",
                }, {
                    title: "有效",
                    dataIndex: "is_valid",
                    width:60,
                }, {
                    title: "操作",
                    dataIndex: "operation",
                    key: "operation",
                    width: 240,
                },
            ],
            rules:{
                type: { required: true },
                code: { required: true },
                date_start: { required: true },
                date_end: { required: true },
                progress: { required: true },
                active: { required: true },
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
            if(this.vacancy.id){
                console.log('update');
                this.$inertia.patch(route('personnel.recruitment.vacancies.update',this.vacancy.id), this.vacancy, {
                    onSuccess: (page) => {
                        console.log(page.data);
                    },
                    onError: (err) => {
                        console.log(err);
                    }
                });
            }else{
                console.log('create');
                this.$inertia.post(route("personnel.recruitment.vacancies.store"), Object.assign({},this.vacancy), {
                    onSuccess: (page) => {
                        console.log(page);
                    },
                    onError: (err) => {
                        console.log(err);
                    },
                });
            }
        },
        onVacancyCodeChange(value){
            const workflow=this.workflows.find(w=>w.code==value)
            if(!this.vacancy.title_zh){
                this.vacancy.title_zh=workflow.title_c
            }
            if(!this.vacancy.title_en){
                this.vacancy.title_en=workflow.title_e
            }
            if(!this.vacancy.title_pt){
                this.vacancy.title_pt=workflow.title_p
            }
            console.log(workflow);
        }
    },
};
</script>
