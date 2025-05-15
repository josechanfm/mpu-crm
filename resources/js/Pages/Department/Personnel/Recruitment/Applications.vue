<template>
    <DepartmentLayout title="職位招聘" :breadcrumb="breadcrumb">
        <div class="mx-auto pt-5">
            <div class="flex-auto pb-3 text-right">
                <a-button @click="createRecord" type="primary">{{ $t('rec.apply') }}</a-button>
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
    <a-modal v-model:open="modal.isOpen" :title="$t('rec.apply')" width="60%">
        <a-typography-title :level="5" class="text-center pb-5">{{vacancy.code}} {{vacancy.title_zh}}</a-typography-title>
      <a-form
        :model="modal.data"
        ref="modalRef"
        name="default"
        layout="horizontal"
        :rules="rules"
        :validate-messages="validateMessages"
        :label-col="{ style:{width:'180px'}  }" :wrapper-col="{ span: 20 }"
      >
          <a-form-item label="證件類別" name="id_type" >
            <a-select v-model:value="modal.data.id_type">
                <template v-for="(item, key) in lang.id_type_options">
                    <a-select-option :value="key">{{ item }}</a-select-option>
                </template>
            </a-select>
          </a-form-item>
          <a-form-item label="證件編號" name="id_num" >
            <a-input type="inpuut" v-model:value="modal.data.id_num" @blur="onBlurIdNum"/>
          </a-form-item>
          <a-form-item label="電郵" name="email" >
            <a-input type="inpuut" v-model:value="modal.data.email" @blur="onBlurEmail"/>
          </a-form-item>
          <a-form-item label="中文全名" name="name_full_zh" >
            <a-input type="inpuut" v-model:value="modal.data.name_full_zh" />
          </a-form-item>
          <a-form-item label="外文姓" name="name_family_fn" >
            <a-input type="inpuut" v-model:value="modal.data.name_family_fn" />
          </a-form-item>
          <a-form-item label="外文名" name="name_given_fn" >
            <a-input type="inpuut" v-model:value="modal.data.name_given_fn" />
          </a-form-item>
          <div v-if="errorMessages">
            <div v-html="errorMessages"/>
          </div>
          
      </a-form>
      <template #footer>
        <a-button key="back" @click="modal.isOpen = false">{{ $t('close') }}</a-button>
        <a-button key="submit" type="primary" @click="storeRecord" :disabled="errorMessages">{{ $t('rec.apply') }}</a-button>
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
                {label:"職位招聘" ,url:route('personnel.recruitment.vacancies.index')},
                {label:"求職人士" ,url:null},
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
                    title: "性別",
                    dataIndex: "gender",
                    width: 60,
                }, {
                    title: "電話",
                    dataIndex: "phone",
                    width: 80,
                }, {
                    title: "日期",
                    dataIndex: "created_at",
                    width: 120,
                }, {
                    title: "遞交",
                    dataIndex: "submitted",
                    width: 60,
                }, {
                    title: "支付",
                    dataIndex: "paid",
                    width: 180,
                }, {
                    title: "操作",
                    dataIndex: "operation",
                    key: "operation",
                },
            ],
            rules:{
                id_type: { required: true },
                id_num: { required: true },
                email: { required: true, type:'email' },
                name_family_fn: { required: true },
                name_given_fn: { required: true },
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
            this.modal.data = {}
            this.modal.mode = "CREATE"
            this.modal.title = "新增"
            this.modal.isOpen = true
            this.errorMessages=null
        },
        storeRecord(){
            if(this.errorMessages!=null){
                console.log('still have error');
                return false;
            }
            this.$refs.modalRef.validateFields().then(() => {
                this.$inertia.post(route("personnel.recruitment.applications.store", this.vacancy), this.modal.data, {
                    onSuccess: (page) => {
                        console.log(page);
                    },
                    onError: (error) => {
                        alert(error.message);
                    },
                });
            }).catch(err => {
                console.log(err);
            })

            
        },
        deleteConfirmed(record) {
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
                this.errorMessages=null
                if(resp.data.length>0){
                    this.errorMessages="此證件編號已報考該職位："+this.modal.data.id_num
                    this.modal.data.id_num=null
                }
                
            })
        },
        onBlurEmail(event){
            if(event.target.value.length==0) return false;
            axios.get(route('personnel.recruitment.application.checkEmail',{
                vacancy_code:this.vacancy.code,
                email:event.target.value
            })).then(resp => {
                //this.errorMessages='此電郵已使用於：<br>'
                this.errorMessages=null
                if(resp.data.application!=null){
                    this.errorMessages+='報名表編號：'+resp.data.application.id +'/' +resp.data.application.name_full_zh+"</br>"
                }
                if(resp.data.member!=null){
                    this.errorMessages+='用戶帳號：'+resp.data.member.first_name + " "+resp.data.member.last_name+"</br>"
                }
                if(resp.data.user!=null){
                    this.errorMessages+='登入帳號：'+resp.data.user.first_name + " "+resp.data.user.last_name+"</br>"
                }
            })
        }
    },
};
</script>
