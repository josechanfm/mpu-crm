    <template>
    <DepartmentLayout title="招聘流程" :breadcrumb="breadcrumb">
        <div class="mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto p-5">
                <a-form ref="formRef" :model="workflow" name="formWorkflow" :label-col="{ style: { width: '150px' } }"
                    :wrapper-col="{ span: 20 }" autocomplete="off" :rules="rules" :validate-messages="validateMessages"
                    @finish="onFormSubmit">
                    <a-form-item label="部門/單位" name="department_id">
                        <a-select v-model:value="workflow.department_id"
                            :options="departments.map(d => ({ value: d.id, label: d.abbr + '-' + d.name_zh }))" />
                    </a-form-item>
                    <a-form-item label="招聘分類" name="vacancy_type">
                        <a-select v-model:value="workflow.vacancy_type" :options="vacancyTypes"/>
                    </a-form-item>
                    <a-form-item label="職位分類" name="category_code">
                        <a-select v-model:value="workflow.category_code" :options="workflowCategories"/>
                    </a-form-item>
                    <a-form-item label="招聘編號" name="vacancy_code">
                        <a-input type="inpuut" v-model:value="workflow.vacancy_code" />
                    </a-form-item>
                    <a-form-item label="職位名稱(中文)" name="title_zh">
                        <a-input type="inpuut" v-model:value="workflow.title_zh" />
                    </a-form-item>
                    <a-form-item label="職位名稱(英文)" name="title_en">
                        <a-input type="inpuut" v-model:value="workflow.title_en" />
                    </a-form-item>
                    <a-form-item label="職位名稱(葡文)" name="title_pt">
                        <a-input type="inpuut" v-model:value="workflow.title_pt" />
                    </a-form-item>
                    <a-form-item label="簡介" name="description">
                        <a-textarea v-model:value="workflow.description" />
                    </a-form-item>
                    <a-form-item label="建議書編號" name="proposal_num">
                        <a-input type="inpuut" v-model:value="workflow.proposal_num" />
                    </a-form-item>
                    <a-form-item label="典試委員會主席" name="chairman">
                        <a-input type="inpuut" v-model:value="workflow.chairman" />
                    </a-form-item>
                    <a-form-item label="開始日期" name="date_start">
                        <a-date-picker v-model:value="workflow.date_start" :valueFormat="dateFormat" :format="dateFormat"/>
                    </a-form-item>
                    <a-form-item label="結束日期" name="date_end">
                        <a-date-picker v-model:value="workflow.date_end" :valueFormat="dateFormat" :format="dateFormat"/>
                    </a-form-item>
                    <a-form-item label="電郵" name="email_notice">
                        <a-input type="inpuut" v-model:value="workflow.email_notice" />
                    </a-form-item>
                    <a-form-item label="負責人" name="handler">
                        <a-input type="inpuut" v-model:value="workflow.handler" />
                    </a-form-item>
                    <a-form-item label="負責人電郵" name="handler_email">
                        <a-input type="inpuut" v-model:value="workflow.handler_email" />
                    </a-form-item>
                    <a-form-item label="狀態" name="status">
                        <a-radio-group v-model:value="workflow.status" size="large" button-style="solid">
                            <a-radio-button value="ACTIVE">Active</a-radio-button>
                            <a-radio-button value="ARCHIVE">Archive</a-radio-button>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item :wrapper-col="{ span: 14, offset: 10 }">
                        <inertia-link :href="route('personnel.recruitment.workflows.index')" class="ant-btn mr-5">退出並返回</inertia-link>
                        <a-button type="primary" html-type="submit">保存</a-button>
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
    props: ["departments", "workflowCategories","vacancyTypes","workflow"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"招聘流程" ,url:route('personnel.recruitment.workflows.index')},
                {label:"新增/修改", url: null },
            ],
            dateFormat: "YYYY-MM-DD",
            rules:{
                department_id: { required: true },
                vacancy_type: { required: true },
                category_code: { required: true },
                vacancy_code: { required: true },
                title_zh: { required: true },
                title_en: { required: true },
                title_pt: { required: true },
                status: { required: true },
                email_notice: {type:'email'},
                handler_email: { type: 'email' },
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
            if(this.workflow.id){
                this.workflow._method = "PATCH";
                this.$inertia.post(route('personnel.recruitment.workflows.update',this.workflow.id), this.workflow, {
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
                console.log(this.workflow);
                this.$inertia.post(route("personnel.recruitment.workflows.store"), this.workflow, {
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
