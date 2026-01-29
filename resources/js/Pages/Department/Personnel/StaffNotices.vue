<template>
    <DepartmentLayout title="財產申報提示" :breadcrumb="breadcrumb">
        <div class="mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">

<div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
  <h3 class="text-lg font-semibold text-gray-800">Staff Management</h3>
  
  <div class="flex items-center gap-2">
    <span>Filter:</span>
    <a-select v-model:value="filterSelection.status" :options="filterOptions.status" style="width: 200px;" @change="onFilterChange"/>
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
      @press-enter="searchNotices()"
    />
    
    <a-button 
      type="primary" 
      @click="searchNotices()"
      size="middle"
      class="ml-2"
    >
      Search
    </a-button>
    
    <a-button 
      type="default" 
      @click="searchNoticeClear()"
      size="middle"
    >
      Reset
    </a-button>
  </div>
</div>                

                <div>
                    <a-select v-model:value="filterSelection.status" :options="filterOptions.status" style="width: 200px;" @change="onFilterChange"/>
                </div>
                <a-table :dataSource="notices.data" :columns="columns" :pagination="pagination" @change="onPaginationChange" :expand-column-width="200">
                    <template #headerCell="{ column }">
                        {{ column.title }}
                    </template>
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="recordView(record)">修改</a-button>
                        </template>
                        <template v-else-if="column.dataIndex=='staff'">
                            {{ record.staff.name_zh }}
                            {{ record.staff.name_pt }}
                        </template>
                        <template v-else-if="column.dataIndex=='relative'">
                            {{ record.relative.name_zh }}
                            {{ record.relative.name_pt }}
                        </template>
                        <template v-else-if="column.dataIndex=='email'">
                            {{ record.email }}
                        </template>
                        <template v-else-if="column.dataIndex=='status'">
                            <span v-if="record.status=='N'" class="text-red-500">未發出</span>
                            <span v-else-if="record.status=='S'">發發岀</span>
                        </template>
                        <template v-else>
                            {{ record[column.dataIndex] }}
                        </template>
                    </template>
                </a-table>
            </div>
            <p>*該頁面按產生申報義務日降序排列</p>
        </div>

        <!-- Modal Start-->
        <a-modal v-model:open="modal.isOpen" :title="modal.title" width="60%">
            <a-form ref="modalRef" :model="modal.data" name="formField" :label-col="{ style:{width:'150px'}  }" :wrapper-col="{ span: 20 }"
                autocomplete="off" :rules="rules" :validate-messages="validateMessages">
                <a-form-item label="電郵" name="email">
                    {{ modal.data.email }}
                </a-form-item>
                <a-form-item label="於年齡" name="age">
                    {{ modal.data.age }}
                </a-form-item>
                <a-form-item label="預定日期" name="date">
                    {{ modal.data.date }}
                </a-form-item>
                <a-form-item label="發電郵日期" name="sent_at">
                    {{ modal.data.sent_at }}
                </a-form-item>
                <a-form-item label="標題" name="title">
                    {{ modal.data.title }}
                </a-form-item>
                <a-form-item label="內容" name="body">
                    <div v-html="modal.data.body"/>
                </a-form-item>
            </a-form>
            <template #footer>
                <a-button @click="modal.isOpen=fase">Close</a-button>
            </template>
        </a-modal>
        <!-- Modal End-->




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
    props: ["notices"],
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
            filterOptions:{
                status:[
                    {value:'ALL', label:'全部'},
                    {value:'N', label:'未發出'},
                    {value:'S', label:'已發出'},
                ],
            },
            filterSelection:{
                status:'N'
            },
            searchOptions:[
                {value: null , label:'None'},
                {value:'email', label:'電郵'},
                {value:'age', label:'年齡限'},
                {value:'date',label:'預設發出日'},
            ],
            search:{
                field:null,
                value:null
            },

            modal: {
                isOpen: false,
                data: {},
                title: "電郵詳細",
                mode: "",
            },
            pagination: {
                total: this.notices.total,
                current: this.notices.current_page,
                filter: this.filterOptions,
                pageSize: this.notices.per_page,
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
                    title: "員工",
                    i18n: "staff",
                    dataIndex: "staff",
                }, {
                    title: "親人",
                    i18n: "relative",
                    dataIndex: "relative",
                }, {
                    title: "年齡",
                    i18n: "age",
                    dataIndex: "age",
                }, {
                    title: "電郵地址",
                    i18n: "email",
                    dataIndex: "email",
                }, {
                    title: "預定日期",
                    i18n: "date",
                    dataIndex: "date",
                }, {
                    title: "發出日期",
                    i18n: "sent_at",
                    dataIndex: "sent_at",
                }, {
                    title: "狀態",
                    i18n: "status",
                    dataIndex: "status",
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
        if(urlParams.has('filter')){
            this.filterSelection.status=urlParams.get('filter')
        }
    }, 
    methods: {
        dateStartChange(){
            this.modal.data.date_remind=dayjs(this.modal.data.date_start).add(60,'days').format(this.dateFormat)
            this.modal.data.date_due=dayjs(this.modal.data.date_start).add(90,'days').format(this.dateFormat)
            console.log(dayjs(this.modal.data.date_start).add(30,'days'))
            console.log(this.modal.data.date_start)
        }, 
        recordView(record){
            this.modal.data=record
            this.modal.isOpen=true
        },

        searchNotices(){
            const page={
                current:1,
                per_page:this.notices.per_page,
            }
            this.onPaginationChange(page)
        },
        searchNoticeClear(){
            this.search.field=null
            this.search.value=null
            const page={
                current:1,
                per_page:this.notices.per_page,
            }
            this.onPaginationChange(page)
        },

        onFilterChange(target){
            this.$inertia.get(
                route("personnel.staff.notices.index"),
                {
                    page: 1,
                    per_page: this.pagination.pageSize,
                    filter:this.filterSelection.status
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
        onPaginationChange(page, filters, sorter) {
            this.$inertia.get(
                route("personnel.staff.notices.index"),
                {
                    page: page.current,
                    per_page: page.pageSize,
                    filter:this.filterSelection.status,
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
