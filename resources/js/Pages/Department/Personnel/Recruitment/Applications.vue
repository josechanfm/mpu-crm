<template>
    <DepartmentLayout title="職位招聘" :breadcrumb="breadcrumb">
        <div class="mx-auto pt-5">
            <div class="flex-auto pb-3 text-right">
                <a-button @click="createRecord">Apply</a-button>
                <inertia-link :href="route('personnel.recruitment.applications.create',{vacancy:vacancy})" class="ant-btn ant-btn-primary">add applicaiton</inertia-link>
            </div>
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table :dataSource="applications.data" :columns="columns" :pagination="pagination" @change="onPaginationChange" :expand-column-width="200">
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a :href="route('personnel.recruitment.applications.show',{vacancy:vacancy,application:record.id})" class="ant-btn" target="_blank">報名表</a>
                            <a :href="route('personnel.recruitment.applications.edit',{vacancy:vacancy,application:record.id})" class="ant-btn" target="_blank">模擬申請人</a>
                            <a-popconfirm title="是否確定刪除?" ok-text="Yes" cancel-text="No"
                                @confirm="deleteConfirmed(record)" :disabled="record.entries_count > 0">
                                <a-button :disabled="record.entries_count > 0">刪除</a-button>
                            </a-popconfirm>
                        </template>
                        <template v-else-if="column.dataIndex=='vacancy_type'">
                            {{ vacancy.type }}
                        </template>
                        <template v-else-if="column.dataIndex=='vacancy_code'">
                            {{ vacancy.code }}
                        </template>
                        <template v-else-if="column.dataIndex=='name_full'">
                            {{ record.name_full_zh }}
                            {{ record.name_given_fn }}
                            {{ record.name_family_fn }}
                        </template>

                        <template v-else-if="column.dataIndex=='paid' && record.paid">
                            {{ record.paid.merc_order_no }}<br>
                            {{ record.paid.payment_date }}
                            {{ record.paid.payment_time }}
                        </template>
                        <template v-else-if="column.dataIndex=='active'">
                            <span :class="text?'':'text-orange-600'">{{ text?'有效':'無效' }}</span>
                        </template>
                        <template v-else>
                            {{ record[column.dataIndex] }}
                        </template>
                    </template>
                </a-table>
            </div>
        </div>

    <!-- Modal Start-->
    <a-modal v-model:visible="modal.isOpen" title="View Only" width="60%">
      <a-form
        :model="modal.data"
        ref="formRef"
        name="default"
        layout="horizontal"
        :validate-messages="validateMessages"
        :label-col="{ style:{width:'180px'}  }" :wrapper-col="{ span: 20 }"
      >
          <a-form-item label="Id Type" name="id_type" >
            <a-select v-model:value="modal.data.id_type">
                <template v-for="(item, key) in lang.id_type_options">
                    <a-select-option :value="key">{{ item }}</a-select-option>
                </template>
            </a-select>
          </a-form-item>
          <a-form-item label="Id Num" name="id_num" >
            <a-input v-model:value="modal.data.id_num" @blur="onBlurIdNum"/>
          </a-form-item>
          <a-form-item label="Email" name="email" >
            <a-input v-model:value="modal.data.email" @blur="onBlurEmail"/>
          </a-form-item>
          <a-form-item label="Full Name (Chinese)" name="name_full_zh" >
            <a-input v-model:value="modal.data.name_full_zh" />
          </a-form-item>
          <a-form-item label="Family Name (Foreign)" name="name_family_fn" >
            <a-input v-model:value="modal.data.name_family_fn" />
          </a-form-item>
          <a-form-item label="Given Name (Foreign)" name="name_given_fn" >
            <a-input v-model:value="modal.data.name_given_fn" />
          </a-form-item>
          <div v-if="errorMessages">
            <div v-html="errorMessages"/>
          </div>
          
      </a-form>
      <template #footer>
        <a-button key="back" @click="modal.isOpen = false">cancel</a-button>
        <a-button key="submit" type="primary" @click="updateRecord">
          update</a-button>
      </template>
    </a-modal>
    <!-- Modal End-->

    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from "@/Layouts/DepartmentLayout.vue";
import recLang  from '/lang/recruitment_admin.json';
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
    props: ["vacancy","applications"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"職位招聘" ,url:null},
            ],
            lang: '',
            errorMessages:'',
            imageUrl: null,
            importFile:null,
            dateFormat: "YYYY-MM-DD",
            exportCriteria:{
                period:[dayjs().subtract(1,'month').format(this.dateFormat),dayjs().format(this.dateFormat)],
                is_valid:true
            },
            modal: {
                isOpen: false,
                data: {},
                title: "Modal",
                mode: "",
            },
            pagination: {
                total: this.applications.total,
                current: this.applications.current_page,
                pageSize: this.applications.per_page,
                defaultPageSize:40,
                showSizeChanger:true,
                pageSizeOptions:['10','20','30','40','50']
            },
            columns: [
                {
                    title: "類別",
                    dataIndex: "vacancy_type",
                    width: 90,
                }, {
                    title: "招聘編號",
                    dataIndex: "vacancy_code",
                    width: 140,
                }, {
                    title: "名稱",
                    dataIndex: "name_full",
                }, {
                    title: "Gender",
                    dataIndex: "gender",
                    width: 40,
                }, {
                    title: "Phone",
                    dataIndex: "phone",
                    width: 80,
                }, {
                    title: "日期",
                    dataIndex: "created_at",
                    width: 120,
                }, {
                    title: "Submit",
                    dataIndex: "submitted",
                    width: 40,
                }, {
                    title: "Paid",
                    dataIndex: "paid",
                    width: 180,
                }, {
                    title: "操作",
                    dataIndex: "operation",
                    key: "operation",
                },
            ],
            rules:{
                email: { required: true, type:'email' },
                date_start: { required: true },
                date_remind: { required: true },
                date_due: { required: true },
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
        this.lang = recLang[this.$page.props.lang]
    },
    mounted() {
    }, 
    methods: {
        createRecord(){
            this.modal.data = {};
            this.modal.mode = "CREATE";
            this.modal.title = "新增";
            this.modal.isOpen = true;
        },
        updateRecord(){
            console.log(this.modal.data)
        },
        deleteConfirmed(record) {
            console.log("delete");
            console.log(record);
            this.$inertia.delete(route("personnel.recruitment.applications.destroy", {vacancy:this.vacancy, application:record} ), {
                onSuccess: (page) => {
                    console.log(page);
                },
                onError: (error) => {
                    alert(error.message);
                },
            });
        },
        onPaginationChange(page, filters, sorter) {
            this.$inertia.get(
                route("personnel.recruitment.applications.index"),
                {
                    page: page.current,
                    per_page: page.pageSize,
                },
                {
                onSuccess: (page) => {
                    console.log(page);
                },
                onError: (error) => {
                    console.log(error);
                },
                }
            );
        },
        onBlurIdNum(event){
            if(event.target.value.length==0) return false;
            axios.get(route('personnel.recruitment.application.checkIdNum',{
                vacancy_code:this.vacancy.code,
                id_num:event.target.value
            })).then(resp => {
                this.errorMessages=''
                if(resp.data.length>0){
                    this.errorMessages='id existed'
                }
                
            })
        },
        onBlurEmail(event){
            if(event.target.value.length==0) return false;
            axios.get(route('personnel.recruitment.application.checkEmail',{
                vacancy_code:this.vacancy.code,
                email:event.target.value
            })).then(resp => {
                this.errorMessages=''
                if(resp.data.application!=null){
                    this.errorMessages+='Application Duplicated<br>'
                }
                if(resp.data.member!=null>0){
                    this.errorMessages+='Member Duplicated<br>'
                }
                if(resp.data.user!=null>0){
                    this.errorMessages+='User Duplicated'
                }
            })
        }
    },
};
</script>
