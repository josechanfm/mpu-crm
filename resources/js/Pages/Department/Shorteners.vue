<template>
    <DepartmentLayout title="Souvenir links" >
        <div class="mx-auto pt-5">
            <div class="flex justify-end pb-3 gap-5 w-full">
                <div class="flex-grow">
                <a-input
                    type="input"
                    v-model:value="adhoc_url"
                    placeholder="Adhoc URL"
                    required
                />
                </div>
                <a-button type="primary" danger @click="generateQrCodeOnly">
                    Generate QR Code
                </a-button>

                <a-button type="primary" @click="createRecord">Create</a-button>
                <a-button :href="route('dae.dashboard')">Back</a-button>
            </div>
                
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">

                <div class="flex flex-wrap p-5 gap-4">
                    <!-- <div class="flex items-center space-x-2">
                        <label>Filter:</label>
                        <a-select 
                            type="input" 
                            v-model:value="myFilter.filter.value" 
                            :options="myFilter.filter.options" 
                            class="w-32" 
                            placeholder="Select sort column"
                        />
                    </div> -->
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
                
                <a-table :dataSource="links.data" :columns="columns" :pagination="pagination" @change="onPaginationChange">
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="openQrCodeModal(record)">Show QR Code</a-button>
                            <a-button @click="editRecord(record)">Edit</a-button>
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
                            <span v-else-if="record.status==2" class="text-blue-300">已領取</span>
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
          <a-form-item label="Token" v-if="modal.mode=='EDIT'">
            {{ modal.data.token }}
          </a-form-item>
          <a-form-item label="Title" name="title">
            <a-input type="inpuut" v-model:value="modal.data.title" />
          </a-form-item>
          <a-form-item label="Url" name="url">
            <a-input type="inpuut" v-model:value="modal.data.url" />
          </a-form-item>
          <a-form-item label="Valid at" name="valid_at">
            <a-date-picker type="inpuut" v-model:value="modal.data.valid_at" :format="dateFormat" :valueFormat="dateFormat"/>
            <span v-if="validAtError" style="color: red;">{{ validAtError }}</span>
          </a-form-item>
          <a-form-item label="Expire at" name="expire_at">
            <a-date-picker type="inpuut" v-model:value="modal.data.expire_at"  :format="dateFormat" :valueFormat="dateFormat" @change="validateValidAt"/>
          </a-form-item>
          <a-form-item label="Remark" >
            <a-textarea type="inpuut" v-model:value="modal.data.remark" />
          </a-form-item>

      </a-form>
      <template #footer>
        <a-button key="back" @click="modal.isOpen=false">{{ $t('close')}}</a-button>
        <a-button v-if="modal.mode=='EDIT'" key="submit" type="primary" @click="submitRecord" :loading="loading">{{  $t('update') }}</a-button>
        <a-button v-if="modal.mode=='CREATE'" key="submit" type="primary" @click="submitRecord" :loading="loading">{{  $t('save') }}</a-button>

      </template>
    </a-modal>
    <!-- Modal End-->

    <!-- QR Code Modal -->
    <qr-code-modal 
      :fullUrl="qrCodeModal.url" 
      :title="qrCodeModal.title" 
      :isModalOpen="qrCodeModal.isOpen" 
      @update:isModalOpen="updateQrCodeModalOpen" 
    />
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
    ConsoleSqlOutlined,
} from "@ant-design/icons-vue";
import Icon, { RestFilled } from "@ant-design/icons-vue";
import { message } from "ant-design-vue";
import dayjs from 'dayjs';
import QrCodeModal from '@/Components/QrCodeModal.vue';

export default {
    components: {
        DepartmentLayout,
        UploadOutlined, LoadingOutlined, PlusOutlined, DeleteOutlined,
        RestFilled,
        message,
        dayjs,
        QrCodeModal
    },
    props: ["links"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"招聘流程" ,url:route('personnel.recruitment.workflows.index')},
                {label:"工作項目", url: null },
            ],
            dateFormat: "YYYY-MM-DD",
            loading:false,
            modal: {
                isOpen: false,
                data: {},
                title: "Modal",
                mode: ""
            },
            adhoc_url:null,
            qrCodeModal:{
                isOpen: false,
                title:'Qr Code',
                url: 'https://venus.mpu.edu.mo'
            },
            validAtError:'',
            myFilter :{
                // filter:{
                //     options:[
                //         { value: "null", label: "未支付" },
                //         { value: "0", label: "支付失敗" },
                //         { value: "1", label: "己支付" },
                //         { value: "2", label: "己領取" },
                //     ],
                //     column:'status',
                //     value: 2
                // },
                search: {  
                    options: [
                        { value:'title', label: "Title"},
                        { value:'url', label: "URL"},
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
        columns() {
            return [
                {
                    title: "Title",
                    dataIndex: "title",
                }, {
                    title: "Token",
                    dataIndex: "token",
                }, {
                    title: "Valid at",
                    dataIndex: "valid_at",
                }, {
                    title: "Expire at",
                    dataIndex: "expire_at",
                }, {
                    title: "Create at",
                    dataIndex: "create_at",
                }, {
                    title: "Operation",
                    dataIndex: "operation",
                    key: "operation",
                    width: 240,
                },
            ]
        },
        validateValidAt(record) {
            // Check if expire_at is filled and valid_at is not
            if (this.modal.data.expire_at && !this.modal.data.valid_at) {
                this.validAtError = 'Valid at is required when Expire at is filled.';
            }else if(this.modal.data.expire_at < this.modal.data.valid_at){
                this.validAtError = 'Valid at must be before expire.';
            }else{
                this.validAtError = '';
            }
        },
        urlParams(){
            return new URLSearchParams(window.location.search);
        },
        pagination() {
            // this.myFilter.filter.value = this.urlParams.get('filter_value')??2;
            this.myFilter.search.column = this.urlParams.get('search_column');
            this.myFilter.search.text = this.urlParams.get('search_text');
            return {
                current: this.links.current_page,
                pageSize: this.links.per_page,
                total: this.links.total,
                showSizeChanger: true,
                showTotal: (total, range) => `Showing ${range[0]}-${range[1]} of ${total} items`,
            };
        },
    },
    methods: {
        formatDate(dateString) {
            return dayjs(dateString).format('YYYY-MM-DD H:s');
        },
        submitRecord(){
            if(this.modal.data.id){
                this.updateRecord()
            }else{
                this.storeRecord()
            }
        },
        editRecord(record) {
            this.modal.data = { ...record };
            this.modal.mode = "EDIT";
            this.modal.title = "Edit Record";
            this.modal.isOpen = true;
        },
        createRecord() {
            this.modal.data = {};
            this.modal.mode = "CREATE";
            this.modal.title = "Create new URL shortener";
            this.modal.isOpen = true;
        },
        storeRecord() {
            this.loading=true
            this.$inertia.post(route('manage.shorteners.store'), this.modal.data, {
                onSuccess: (page) => {
                    this.modal.data = {};
                    this.modal.isOpen = false;
                    this.loading=false
                },
                onError: (err) => {
                    console.log(err);
                    this.loading=false
                }
            });
        },
        updateRecord() {
            this.loading=true
            this.modal.data._method = "PATCH";
            this.$inertia.post(route('manage.shorteners.update', {
                    shortener: this.modal.data,
            }), this.modal.data, {
                onSuccess: (page) => {
                    this.modal.data = {};
                    this.modal.isOpen = false;
                    this.loading=false
                    console.log(page);
                },
                onError: (error) => {
                    console.log(error);
                    this.loading=false
                }
            });
        },
        clearMyFilter(){
            this.myFilter.search.column = null;
            this.myFilter.search.text = '';
            this.myFilter.filter.column= 'status';
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
                route("manage.shorteners.index"),
                {
                    page: page.current,
                    per_page: page.pageSize,
                    // show_all: this.myFilter.show_all,
                    // filter_column:this.myFilter.filter.column,
                    // filter_value:this.myFilter.filter.value,
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
        generateQrCodeOnly() {
            //this.qrCodeModal.title = record.title;
            this.qrCodeModal.url = this.adhoc_url;
            this.qrCodeModal.isOpen = true; 
        },
        openQrCodeModal(record) {
            this.qrCodeModal.title = record.title;
            //this.qrCodeModal.url = record.url;
            this.qrCodeModal.url = `${window.location.origin}/s/${record.token}`;
            this.qrCodeModal.isOpen = true; 
        },
        updateQrCodeModalOpen(value) {
            this.qrCodeModal.isOpen = value; // Update the modal open state
        },

    },
};
</script>
