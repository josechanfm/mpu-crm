<template>
    <DepartmentLayout title="Souvenir orders" >
        <div class="mx-auto pt-5">
            <div class="flex flex-justify justify-end pb-3 gap-5">
                <a-button :href="route('dae.dashboard')">Back</a-button>
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
                        <a-button type="primary" @click="onClickFilter">Filter</a-button>
                        <a-button @click="clearMyFilter">Clear</a-button>
                    </div>
                </div>
                
                <a-table :dataSource="orders.data" :columns="columns" :pagination="pagination" @change="onPaginationChange">
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="viewRecord(record)">Edit</a-button>
                        </template>
                        <template v-else-if="column.dataIndex=='buyer' && record.user">
                            <span>{{ record.user.netid }}</span>
                        </template>
                        <template v-else-if="column.dataIndex=='items'">
                            <div v-for="item in record.items">
                                {{ item.name }} ({{ item.qty }})
                            </div>
                        </template>
                        <template v-else-if="column.dataIndex=='created_at'">
                            {{  formatDate(record.created_at) }}
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
          <a-form-item label="User" >
            {{ modal.data.user?.netid }}
          </a-form-item>
          <a-form-item label="Form" >
            <div modal.data.form_meta>
                <div>Faculty: {{ modal.data.form_meta?.faculty }}</div>
                <div>Degree: {{ modal.data.form_meta?.degree }}</div>
                <div>Phone: {{ modal.data.form_meta?.phone }}</div>
                <div>Email: {{ modal.data.form_meta?.email }}</div>
            </div>
          </a-form-item>
          <a-form-item label="Method" >
            {{ modal.data.payment_method }}
          </a-form-item>
          <a-form-item label="Amount" >
            {{ modal.data.amount }}
          </a-form-item>
          <a-form-item label="Items" >
            <div v-for="item in modal.data.items">
                {{ item.name }} ({{ item.qty }})
            </div>
          </a-form-item>
          <a-form-item label="Date" >
            {{  formatDate(modal.data.created_at) }}
          </a-form-item>
          <a-form-item label="Status" >
            <span v-if="modal.data.status==null">未支付</span>
            <span v-else-if="modal.data.status==0">支付失敗</span>
            <span v-else-if="modal.data.status==1">已支付</span>
            <span v-else-if="modal.data.status==2">確認支付</span>
          </a-form-item>

      </a-form>
      <template #footer>
        <a-button key="back" @click="modal.isOpen=false">{{ $t('close')}}</a-button>
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
    props: ["orders"],
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
                        { value: 0, label: "未支付" },
                        { value: 1, label: "支付失敗" },
                        { value: 2, label: "己支付" },
                        { value: 3, label: "己領取" },
                    ],
                    column:'status',
                    value: 2
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
            columns: [
                {
                    title: "Buyer",
                    dataIndex: "buyer",
                }, {
                    title: "Amount",
                    dataIndex: "amount",
                }, {
                    title: "Items",
                    dataIndex: "items",
                }, {
                    title: "Date",
                    dataIndex: "created_at",
                }, {
                    title: "Status",
                    dataIndex: "status",
                }, {
                    title: "操作",
                    dataIndex: "operation",
                    key: "operation",
                    width: 240,
                },
            ],
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
            this.myFilter.filter.value = this.urlParams['filter_value']??2;
            this.myFilter.search.column = this.urlParams['search_column'];
            this.myFilter.search.text = this.urlParams['search_text'];
            return {
                current: this.orders.current_page,
                pageSize: this.orders.per_page,
                total: this.orders.total,
                showSizeChanger: true,
                
                showTotal: (total, range) => `Showing ${range[0]}-${range[1]} of ${total} items`,
            };
        },
    },
    methods: {
        formatDate(dateString) {
            return dayjs(dateString).format('YYYY-MM-DD H:s');
        },

        viewRecord(record) {
            this.modal.data = { ...record };
            this.modal.mode = "VIEW";
            this.modal.title = "View Record";
            this.modal.isOpen = true;
        },
        clearMyFilter(){
            this.myFilter.search.column = null;
            this.myFilter.search.text = '';
            this.myFilter.filter.column= 'status';
            this.myFilter.filter.value= 2;
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
                route("dae.souvenir.orders.index"),
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

    },
};
</script>
