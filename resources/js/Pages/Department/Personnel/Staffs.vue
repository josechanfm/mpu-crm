<template>
    <DepartmentLayout title="財產申報提示" :breadcrumb="breadcrumb">
        <div class="mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
<div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
  <h3 class="text-lg font-semibold text-gray-800">Staff Management</h3>
  
  <div class="flex items-center gap-2">
    <span>Search:</span>
    <a-select
      v-model:value="search.field"
      :options="searchOptions"
      style="width: 150px"
      size="middle"
      placeholder="Field"
    />
    
    <a-input
      v-model:value="search.value"
      placeholder="Search..."
      style="width: 200px"
      size="middle"
      allow-clear
      @press-enter="searchStaff()"
    />
    
    <a-button 
      type="primary" 
      @click="searchStaff()"
      size="middle"
      class="ml-2"
    >
      Search
    </a-button>
    
    <a-button 
      type="default" 
      @click="searchStaffClear()"
      size="middle"
    >
      Reset
    </a-button>
  </div>
</div>
                <a-table :dataSource="staffs.data" :columns="columns" :pagination="pagination" @change="onPaginationChange" :expand-column-width="200">
                    <template #headerCell="{ column }">
                        {{ column.title }}
                    </template>
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <inertia-link :href="route('personnel.staffs.show',record.id)">
                                <a-button>印卡</a-button>
                            </inertia-link> 
                        </template>
                        <template v-else-if="column.dataIndex=='sent_email_count'">
                            <span v-if="record.sent_email_count">
                                <inertia-link :href="route('personnel.staffs.emails',{gpdp_id:record.id})">{{ record.sent_email_count }}</inertia-link>
                            </span>
                            <span v-else>
                                --
                            </span>
                            
                        </template>
                        <template v-else-if="column.dataIndex=='is_valid'">
                            <span :class="text?'':'text-orange-600'">{{ text==1?'有效':'無效' }}</span>
                        </template>
                        <template v-else>
                            {{ record[column.dataIndex] }}
                        </template>
                    </template>
                </a-table>
            </div>
            <p>*該頁面按產生申報義務日降序排列</p>
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
import { message } from "ant-design-vue";
import dayjs from 'dayjs';
import axios from "axios";
import { Inertia } from "@inertiajs/inertia";

export default {
    components: {
        DepartmentLayout,
        UploadOutlined,
        LoadingOutlined,
        PlusOutlined,
        RestFilled,
        message,
        dayjs
    },
    props: ["staffs"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"財產申報" ,url:null},
            ],
            loading: false,
            imageUrl: null,
            importFile:null,
            dateFormat: "YYYY-MM-DD",
            exportCriteria:{
                period:[dayjs().subtract(1,'month').format(this.dateFormat),dayjs().format(this.dateFormat)],
                is_valid:true
            },
            searchOptions:[
                {value: null , label:'None'},
                {value:'name_zh', label:'中文姓名'},
                {value:'name_pt',label:'外文姓名'},
                {value:'username',label:'NetId'},
            ],
            search:{
                field:null,
                value:null
            },
            modal: {
                isOpen: false,
                data: {},
                title: "Modal",
                mode: "",
            },
            pagination: {
                total: this.staffs.total,
                current: this.staffs.current_page,
                pageSize: this.staffs.per_page,
                defaultPageSize:40,
                showSizeChanger:true,
                pageSizeOptions:['10','20','30','40','50']
            },
            employmentOptions:[
                {value:'CONTRACT',label:'工作合同'},
                {value:'REQUISITION',label:'徵用'},
                {value:'APPOINT',label:'定期委任'}
            ],
            columns: [
                {
                    title: "員工編號",
                    i18n: "staff_num",
                    dataIndex: "staff_num",
                    width: 90,
                }, {
                    title: "姓名(中文)",
                    i18n: "name_zh",
                    dataIndex: "name_zh",
                    width: 100,
                }, {
                    title: "姓名(外文)",
                    i18n: "name_pt",
                    dataIndex: "name_pt",
                }, {
                    title: "電郵地址",
                    i18n: "email",
                    dataIndex: "email",
                }, {
                    title: "電郵地址",
                    i18n: "email",
                    dataIndex: "medical_type",
                }, {
                    title: "電郵地址",
                    i18n: "email",
                    dataIndex: "medical_num",
                }, {
                    title: "操作",
                    i18n: "operation",
                    dataIndex: "operation",
                    key: "operation",
                    width: 240,
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

    },
    mounted() {
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.has('search_field')){
            this.search.field=urlParams.get('search_field')
        }
        if(urlParams.has('search_value')){
            this.search.value=urlParams.get('search_value')
        }

    }, 
    methods: {
        dateStartChange(){
            this.modal.data.date_remind=dayjs(this.modal.data.date_start).add(60,'days').format(this.dateFormat)
            this.modal.data.date_due=dayjs(this.modal.data.date_start).add(90,'days').format(this.dateFormat)
            console.log(dayjs(this.modal.data.date_start).add(30,'days'))
            console.log(this.modal.data.date_start)
        }, 
        searchStaff(){
            const page={
                current:1,
                per_page:this.staffs.per_page,
            }
            this.onPaginationChange(page)
        },
        searchStaffClear(){
            this.search.field=null
            this.search.value=null
            const page={
                current:1,
                per_page:this.staffs.per_page,
            }
            this.onPaginationChange(page)
        },
        onPaginationChange(page, filters, sorter) {
            this.$inertia.get(
                route("personnel.staffs.index"),
                {
                    page: page.current,
                    per_page: page.pageSize,
                    search_field: this.search.field,
                    search_value: this.search.value,
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
    },
};
</script>
