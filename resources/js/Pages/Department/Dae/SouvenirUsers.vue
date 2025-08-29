<template>
    <DepartmentLayout title="工作項目" >
        <div class="mx-auto pt-5">
            <div class="flex-auto pb-3 text-right">
                <a-button @click="createRecord" class="ant-btn ant-btn-primary">{{ $t('create') }}</a-button>
            </div>
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">

                <div class="flex flex-wrap p-5 gap-4">
                    <div class="flex items-center space-x-2">
                        <label>Filter:</label>
                        <a-select 
                            type="input" 
                            v-model:value="myFilter.filter.value" 
                            :options="myFilter.filter.options" 
                            class="w-32" 
                            placeholder="Select sort column"
                        />
                    </div>
<!-- 
                    <div class="flex items-center space-x-2">
                        <label>Sort By:</label>
                        <a-select 
                            type="input" 
                            v-model:value="myFilter.sort.column" 
                            :options="myFilter.sort.options" 
                            class="w-32" 
                            placeholder="Select sort column"
                        />
                        <a-select 
                            v-model:value="myFilter.sort.order" 
                            :options="[{label:'Ascending', value:'asc'}, {label:'Descending', value:'desc'}]" 
                            class="w-32"
                        />
                    </div> -->
                    <div class="flex items-center space-x-2">
                        <label>Search:</label>
                        <a-select 
                            v-model:value="myFilter.search.column" 
                            :options="myFilter.search.options" 
                            placeholder="Select search column"  
                            class="w-32"
                        />
                        <a-input 
                            type="input" 
                            v-model:value="myFilter.search.text"  
                            placeholder="Search content" 
                            class="w-32"
                        />
                    </div>
                    <!-- <div class="flex items-center">
                        <a-switch 
                            v-model:checked="myFilter.show_all" 
                            checkedChildren="Only Available" 
                            unCheckedChildren="Show All" 
                        />
                    </div> -->
                    <div class="flex items-center space-x-2">
                        <a-button type="primary" @click="onClickFilter">{{ $t('filter') }}</a-button>
                        <a-button @click="clearMyFilter">Clear</a-button>
                    </div>
                </div>
                
                <a-table :dataSource="users.data" :columns="columns" :pagination="pagination" @change="onPaginationChange">
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="editRecord(record)">{{ $t('edit') }}</a-button>
                        </template>
                        <template v-else-if="column.dataIndex=='updated_at'">
                            {{  formatDate(record.updated_at) }}
                        </template>
                        <template v-else-if="column.dataIndex=='status'">
                            <span v-if="record.status==null" class="text-yellow-500">未支付</span>
                            <span v-else-if="record.status==0" class="text-red-500">支付失敗</span>
                            <span v-else-if="record.status==1" class="text-red-800">已支付</span>
                            <span v-else-if="record.status==2" class="text-blue-300">確認支付</span>
                            <span v-else-if="record.status==3" class="text-green-300">已領取</span>
                        </template>
                        <template v-else>
                            {{ record[column.dataIndex] }}
                        </template>
                    </template>
                </a-table>
            </div>
        </div>

    <!-- Modal Start-->
    <a-modal v-model:open="modal.isOpen" :title="modal.title" width="60%">
      <a-form
        :model="modal.data"
        ref="modalRef"
        name="default"
        layout="horizontal"
        :label-col="{ style:{width:'120px'}  }" :wrapper-col="{ span: 20 }"
      >
          <a-form-item label="Student No." name="netid" >
            <a-input type="inpuut" v-model:value="modal.data.netid" />
          </a-form-item>

          <a-form-item label="Method" >
            {{ modal.data }}
          </a-form-item>
      </a-form>
      <template #footer>
        <a-button key="back" @click="handleModalClose">{{ $t('close')}}</a-button>
        <a-button v-if="modal.mode=='EDIT'" key="submit" type="primary" @click="updateRecord" :loading="loading">{{  $t('update') }}</a-button>
        <a-button v-if="modal.mode=='CREATE'" key="submit" type="primary" @click="storeRecord" :loading="loading">{{  $t('save') }}</a-button>
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
    DeleteOutlined,
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
        UploadOutlined, LoadingOutlined, PlusOutlined, DeleteOutlined,
        RestFilled,
        quillEditor,
        message,
        dayjs
    },
    props: ["users"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"招聘流程" ,url:route('personnel.recruitment.workflows.index')},
                {label:"工作項目", url: null },
            ],
            dateFormat: "YYYY-MM-DD",
            modal: {
                isOpen: false,
                data: {},
                title: "Modal",
                mode: ""
            },
            myFilter :{
                filter:{
                    options:[
                        { value: 1, label: "可購買" },
                        { value: 0, label: "不可購買" },
                    ],
                    column:'can_buy',
                    value: 1
                },
                search: {  
                    options: [
                        { value:'buyer', label: "Student No."},
                    ],
                    column: null, // Selected search column
                    text: null, // search text
                },
                sort:{
                    options: [
                        { value:'date', label: "Date"},
                    ],
                    column: null, // Default to 'name' if not specified
                    order: 'asc' // 'asc' or 'desc'
                }
            },
        };
    },
    created() {

    },
    mounted() {
    }, 
    computed: {
        urlParams(){
            return new URLSearchParams(window.location.search);
        },
        pagination() {
            this.myFilter.filter.value = this.urlParams['filter_value']??1;
            this.myFilter.search.column = this.urlParams['search_column'];
            this.myFilter.search.text = this.urlParams['search_text'];
            return {
                current: this.users.current_page,
                pageSize: this.users.per_page,
                total: this.users.total,
                showSizeChanger: true,
                
                showTotal: (total, range) => `Showing ${range[0]}-${range[1]} of ${total} items`,
            };
        },
                columns(){
            return [
                {
                    title: "Student No.",
                    dataIndex: "netid",
                }, {
                    title: "Email",
                    dataIndex: "email",
                }, {
                    title: "Phone",
                    dataIndex: "phone",
                }, {
                    title: "Faculty",
                    dataIndex: "faculty_code",
                }, {
                    title: "Degree",
                    dataIndex: "degree_code",
                }, {
                    title: "Can Buy",
                    dataIndex: "can_buy",
                }, {
                    title: "Updated At",
                    dataIndex: "updated_at",
                }, {
                    title: "操作",
                    dataIndex: "operation",
                    key: "operation",
                    width: 240,
                },
            ]
        },

    },
    methods: {
        formatDate(dateString) {
            return dayjs(dateString).format('YYYY-MM-DD H:s');
        },

        viewRecord(record) {
            this.modal.data = { ...record };
            this.modal.mode = "VIEW";
            this.modal.title = "詳細";
            this.modal.isOpen = true;
        },
        clearMyFilter(){
            this.myFilter.search.column = null;
            this.myFilter.search.text = '';
            this.myFilter.filter.column= 'can_buy';
            this.myFilter.filter.value= 1;
            this.pagination.current = 1; // Reset to first page
            this.onPaginationChange(this.pagination);
        },
        onClickFilter(){
            this.pagination.current = 1; // Reset to first page
            this.onPaginationChange(this.pagination);
        },

        refreshMyFilter(){
            if(this.urlParams.get('show_all')){
                console.log('show_all')
                console.log(this.urlParams.get('show_all'))
                this.myFilter.show_all=this.urlParams.get('show_all')==='true'?true:false
            }
            if(this.urlParams.get('search_column')){
                console.log('search_column')
                this.myFilter.search.column=this.urlParams.get('search_column')
            }
            if(this.urlParams.get('search_text')){
                console.log('search_text')
                this.myFilter.search.text=this.urlParams.get('search_text')
            }
            if(this.urlParams.get('sort_column')){
                console.log('sort_column')
                this.myFilter.sort.column=this.urlParams.get('sort_column')
            }
            if(this.urlParams.get('sort_order')){
                console.log('sort_order')
                this.myFilter.sort.order=this.urlParams.get('sort_order')
            }
        },

        onPaginationChange(page, filters, sorter) {
            console.log(page, filters, sorter);
            this.$inertia.get(
                route("dae.souvenir.users.index"),
                {
                    page: page.current,
                    per_page: page.pageSize,
                    // show_all: this.myFilter.show_all,
                    filter_column:this.myFilter.filter.column,
                    filter_value:this.myFilter.filter.value,
                    search_column:this.myFilter.search.column,
                    search_text:this.myFilter.search.text,
                    // sort_column: this.myFilter.sort.column,
                    // sort_order: this.myFilter.sort.order,
                },
                {
                onSuccess: (page) => {
                    this.refreshMyFilter();
                    console.log(page);
                },
                onError: (error) => {
                    console.log(error);
                },
                }
            );
        },

        createRecord() {
            this.modal.data = {
                published:false  
            };
            this.modal.mode = "CREATE";
            this.modal.title = "新增";
            this.modal.isOpen = true;
        },
        editRecord(record) {
            this.modal.data = { ...record };
            this.modal.mode = "EDIT";
            this.modal.title = "修改";
            this.modal.isOpen = true;
        },
        handleModalClose(){
            console.log('modal is going to close')
            this.modal.isOpen=false
            location.reload();
        },


    },
};
</script>
