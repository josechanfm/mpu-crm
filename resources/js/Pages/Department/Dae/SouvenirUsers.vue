<template>
    <DepartmentLayout title="Sourvenir Users" >
        <div class="mx-auto pt-5">
            <div class="flex flex-justify justify-end pb-3 gap-5">
                <a href="/storage/souvenirUsers.xlsx" class="pt-1">DownloadSample file</a>
                <a-upload
                    :before-upload="beforeUpload"
                    :show-upload-list="false"
                    accept=".xlsx, .xls"
                >
                    <a-button danger ghost><UploadOutlined /> Select upload File</a-button>
                </a-upload>
                <a-button :href="route('dae.dashboard')">Back</a-button>
                <a-button type="primary" @click="createRecord">Create</a-button>
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
                    <div class="flex items-center space-x-2">
                        <a-button type="primary" @click="onClickFilter">Filter</a-button>
                        <a-button @click="clearMyFilter">Clear</a-button>
                    </div>
                </div>
                
                <!-- Show selected items -->
                <div v-if="selectedRowKeys.length > 0" class="p-3 bg-blue-50 border-b">
                    <span class="text-blue-600 font-medium">{{ selectedRowKeys.length }} item(s) selected</span>
                    <a-button size="small" @click="clearSelection" class="ml-3">Clear Selection</a-button>
                    <a-button size="small" @click="batchModalVisible=true">Batch action</a-button>
                </div>
                <!-- Table with correct row-selection -->
                <a-table 
                    :dataSource="users.data" 
                    :columns="columns" 
                    :pagination="pagination" 
                    @change="onPaginationChange" 
                    :row-selection="rowSelectionConfig"
                    :row-key="record => record.id"
                    v-model:selectedRowKeys="selectedRowKeys"
                >
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="editRecord(record)">Edit</a-button>
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

        <!-- Batch Actions Modal (Optional) -->
        <a-modal 
            v-model:open="batchModalVisible" 
            title="Batch Actions" 
            @ok="handleBatchAction"
            @cancel="batchModalVisible = false"
        >
            <p>You have selected {{ selectedRowKeys.length }} items</p>
            <p>What would you like to do with them?</p>
            <a-select v-model:value="batchAction" class="w-full mt-4">
                <a-select-option value="delete">Delete Selected</a-select-option>
                <a-select-option value="export">Export Selected</a-select-option>
                <a-select-option value="update">Update Status</a-select-option>
            </a-select>
            <a-form-item label="Set Selected items:" v-if="batchAction=='update'">
                <a-switch v-model:checked="batchCanBuys"  checked-children="Can Buy" un-checked-children="Can NOT buy" />
            </a-form-item>
        </a-modal>

        <!-- Modal Start-->
        <a-modal v-model:open="modal.isOpen" :title="modal.title" width="60%">
            <a-form
                :model="modal.data"
                ref="modalRef"
                name="default"
                layout="horizontal"
                :rules="rules" 
                :label-col="{ style:{width:'120px'}  }" :wrapper-col="{ span: 20 }"
            >
                <a-form-item label="Student No." name="netid" >
                    <a-input type="input" v-model:value="modal.data.netid" />
                </a-form-item>
                <a-form-item label="Name (Chinese)" name="name_zh" >
                    <a-input type="input" v-model:value="modal.data.name_zh" />
                </a-form-item>
                <a-form-item label="Name (English)." name="name_en" >
                    <a-input type="input" v-model:value="modal.data.name_en" />
                </a-form-item>
                <a-form-item label="Email" name="email" >
                    <a-input type="input" v-model:value="modal.data.email" />
                </a-form-item>
                <a-form-item label="Phone" name="phone" >
                    <a-input type="input" v-model:value="modal.data.phone" />
                </a-form-item>
                <a-form-item label="Faculty" name="faculty_code">
                    <a-select v-model:value="modal.data.faculty_code" placeholder="Select your faculty / 選擇您的系所">
                        <template v-for="faculty in faculties" :key="faculty.value">
                            <a-select-option :value="faculty.value">{{ faculty.label }}</a-select-option>
                        </template>
                        <!-- <a-select-option value="science">Science / 科學</a-select-option>
                        <a-select-option value="arts">Arts / 人文</a-select-option>
                        <a-select-option value="engineering">Engineering / 工程</a-select-option> -->
                    </a-select>
                </a-form-item>
                <a-form-item label="Degree" name="degree_code">
                    <a-select v-model:value="modal.data.degree_code" placeholder="Select your degree / 選擇您的學位">
                        <a-select-option value="BACHALOR">Bachelor / 學士</a-select-option>
                        <a-select-option value="MASTER">Master / 碩士</a-select-option>
                        <a-select-option value="PHD">PhD / 博士</a-select-option>
                    </a-select>
                </a-form-item>

                <a-form-item label="Can Buy" name="can_buy" >
                    <a-switch v-model:checked="modal.data.can_buy" />
                </a-form-item>
            </a-form>
            <template #footer>
                <a-button key="back" @click="handleModalClose">Close</a-button>
                <a-button v-if="modal.mode=='EDIT'" key="submit" type="primary" @click="updateRecord()" :loading="loading">Update</a-button>
                <a-button v-if="modal.mode=='CREATE'" key="submit" type="primary" @click="storeRecord()" :loading="loading">Create</a-button>
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
import { message } from "ant-design-vue";
import dayjs from 'dayjs';
import axios from "axios";
import { ref, computed, reactive } from 'vue';

export default {
    components: {
        DepartmentLayout,
        UploadOutlined, LoadingOutlined, PlusOutlined, DeleteOutlined,
        RestFilled,
        message,
        dayjs
    },
    props: ["users"],
    setup() {
        // Use reactive refs for Ant Design Vue 4
        const selectedRowKeys = ref([]);
        const selectedRows = ref([]);
        const batchModalVisible = ref(false);
        const batchCanBuys=ref(false);
        const batchAction = ref('');
        
        return {
            selectedRowKeys,
            selectedRows,
            batchModalVisible,
            batchCanBuys,
            batchAction
        };
    },
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
                title: "Modal....",
                mode: ""
            },
            faculties:[
                { label: 'FAC / 應用科學學院', value: 'FCA' },
                { label: 'FHSS / 健康科學及體育學院', value: 'FCSD' },
                { label: 'FLT / 語言及翻譯學院', value: 'FLT' },
                { label: 'FAD / 藝術及設計學院', value: 'FAD' },
                { label: 'FHSS / 人文及社會科學學院', value: 'FCHS' },
                { label: 'FB / 管理科學學院', value: 'FCG' },
                { label: 'AE / 北京大學醫學部——澳門理工大學護理書院', value: 'AE' },
            ],
            rules: {
                netid: [{ required: true, message: 'Please enter your NetID' }],
                password: [{ required: true, message: 'Please enter your password' }],
                faculty_code: [{ required: true, message: 'Please select your faculty' }],
                degree_code: [{ required: true, message: 'Please select your degree' }],
                phone: [
                    { required: true, message: 'Please enter your phone number' },
                    { pattern: /^[0-9]*$/, message: 'Phone number must contain only numbers' },
                ],
                email: [
                    { required: true, type: 'email', message: 'Please enter a valid email address' },
                ], 
                
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
            file: null,
            error: '',
            successMessage: '',
            loading: false
        };
    },
    created() {

    },
    mounted() {
    }, 
    computed: {
        // Correct rowSelection configuration for Ant Design Vue 4
        rowSelectionConfig() {
            return {
                selectedRowKeys: this.selectedRowKeys,
                onChange: (selectedRowKeys, selectedRows) => {
                    this.selectedRowKeys = selectedRowKeys;
                    this.selectedRows = selectedRows;
                    // You can call your custom handlers here
                    this.onSelectionChange(selectedRowKeys, selectedRows);
                },
                onSelect: (record, selected, selectedRows, nativeEvent) => {
                    console.log('onSelect - single record:', record);
                    console.log('onSelect - selected:', selected);
                    console.log('onSelect - all selected rows:', selectedRows);
                    
                    // Custom logic for single selection
                    if (selected) {
                        console.log(`Selected record with ID: ${record.id}`);
                    } else {
                        console.log(`Deselected record with ID: ${record.id}`);
                    }
                },
                onSelectAll: (selected, selectedRows, changeRows) => {
                    console.log('onSelectAll - selected:', selected);
                    console.log('onSelectAll - selectedRows:', selectedRows);
                    console.log('onSelectAll - changeRows:', changeRows);
                    
                    if (selected) {
                        console.log('All rows on current page selected');
                    } else {
                        console.log('All rows on current page deselected');
                    }
                },
                // Additional configuration options
                checkStrictly: false,
                type: 'checkbox', // 'checkbox' or 'radio'
                // Column width for selection column
                columnWidth: 60,
                // Fixed column position
                fixed: false,
                // Hide selection column when false
                hideDefaultSelections: false,
                // Custom selections (for dropdown)
                selections: [
                    {
                        key: 'all-current',
                        text: 'Select All Current',
                        onSelect: () => {
                            const currentPageIds = this.users.data.map(item => item.id);
                            this.selectedRowKeys = [...new Set([...this.selectedRowKeys, ...currentPageIds])];
                        }
                    },
                    {
                        key: 'invert-current',
                        text: 'Invert Current',
                        onSelect: () => {
                            const currentPageIds = this.users.data.map(item => item.id);
                            const newSelected = this.selectedRowKeys.filter(id => !currentPageIds.includes(id));
                            const inverted = currentPageIds.filter(id => !this.selectedRowKeys.includes(id));
                            this.selectedRowKeys = [...newSelected, ...inverted];
                        }
                    },
                    {
                        key: 'clear',
                        text: 'Clear Selection',
                        onSelect: () => {
                            this.selectedRowKeys = [];
                            this.selectedRows = [];
                        }
                    }
                ]
            };
        },
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
                    sorter: true,
                }, {
                    title: "Email",
                    dataIndex: "email",
                    sorter: true,
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
                    filters: [
                        { text: '可購買', value: 1 },
                        { text: '不可購買', value: 0 },
                    ],
                    onFilter: (value, record) => record.can_buy == value,
                }, {
                    title: "Updated At",
                    dataIndex: "updated_at",
                    sorter: (a, b) => {
                        return new Date(a.updated_at) - new Date(b.updated_at);
                    },
                }, {
                    title: "Operation",
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

        // Selection-related methods
        onSelectionChange(selectedRowKeys, selectedRows) {
            console.log(`Selected ${selectedRowKeys.length} items`);
            
            // You can trigger batch actions modal when selection changes
            if (selectedRowKeys.length > 0) {
                // Optional: Show batch action buttons or modal
                // this.showBatchActions = true;
            }
        },
        
        clearSelection() {
            this.selectedRowKeys = [];
            this.selectedRows = [];
            message.success('Selection cleared');
        },
        
        handleBatchAction() {
            if (!this.batchAction) {
                message.warning('Please select an action');
                return;
            }
            
            switch (this.batchAction) {
                case 'delete':
                    this.deleteSelectedItems();
                    break;
                case 'export':
                    this.exportSelectedItems();
                    break;
                case 'update':
                    this.updateSelectedItems();
                    break;
            }
            
            this.batchModalVisible = false;
            this.batchAction = '';
        },
        
        deleteSelectedItems() {
            if (this.selectedRowKeys.length === 0) {
                message.warning('No items selected');
                return;
            }
            
            this.$confirm({
                title: 'Confirm Deletion',
                content: `Are you sure you want to delete ${this.selectedRowKeys.length} selected item(s)?`,
                onOk: async () => {
                    try {
                        // Call your API to delete selected items
                        this.$inertia.post(route('dae.souvenir.users.batchDelete'), {ids: this.selectedRowKeys})
                        // const response = await axios.post(route('dae.souvenir.users.batchDelete'), {
                        //     ids: this.selectedRowKeys
                        // });
                        
                        message.success(response.data.message || 'Items deleted successfully');
                        this.selectedRowKeys = [];
                        this.selectedRows = [];
                        
                        // Refresh the table data
                        this.$inertia.reload();
                    } catch (error) {
                        message.error('Failed to delete items');
                    }
                },
                onCancel() {
                    console.log('Cancelled');
                },
            });
        },
        
        exportSelectedItems() {
            console.log('exportSelectedItems')
            if (this.selectedRowKeys.length === 0) {
                message.warning('No items selected');
                return;
            }
            
            //this.$inertia.get(route('dae.souvenir.users.batchExport'), {ids: this.selectedRowKeys})
            // Create export URL with selected IDs
            const params = new URLSearchParams({
                ids: this.selectedRowKeys.join(',')
            });
            console.log(params)
            window.open(`${route('dae.souvenir.users.batchExport')}?${params.toString()}`, '_blank');
        },
        
        updateSelectedItems() {
            // Implement your update logic here
            this.$inertia.post(route('dae.souvenir.users.batchUpdate'), {ids: this.selectedRowKeys, can_buy:this.batchCanBuys})
            console.log('Update selected items:', this.selectedRowKeys);
            // You might want to open a modal for bulk update
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
                    filter_column:this.myFilter.filter.column,
                    filter_value:this.myFilter.filter.value,
                    search_column:this.myFilter.search.column,
                    search_text:this.myFilter.search.text,
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
                can_buy:true
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
        updateRecord(){
            console.log('update record', this.modal.data)
            this.$inertia.put(route('dae.souvenir.users.update', this.modal.data.id), this.modal.data)
            this.modal.isOpen=false
            //this.modal.data=null
        },
        storeRecord(){
            this.$inertia.post(route('dae.souvenir.users.store'), this.modal.data)
            this.modal.data=null
            this.modal.isOpen=false
        },
        handleModalClose(){
            this.modal.isOpen=false
            location.reload();
        },

        // method for excel file import
        beforeUpload(file) {
            console.log('Selected file:', file);
            const isExcel = file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || 
                            file.type === 'application/vnd.ms-excel';
            const isLt2M = file.size / 1024 / 1024 < 2; // Limit to 2MB

            if (!isExcel) {
                this.error = 'You can only upload Excel files!';
                return false; // Prevent upload
            } else if (!isLt2M) {
                this.error = 'File must be smaller than 2MB!';
                return false; // Prevent upload
            }

            this.error = ''; // Clear any previous errors
            this.file = file; // Store the file for upload
            this.uploadFile(file); // Immediately upload the file
            return false; // Prevent automatic upload
        },
        
        async uploadFile(file) {
            console.log('Uploading file:', file);
            const formData = new FormData();
            formData.append('file', file);
            this.$inertia.post(route('dae.souvenir.user.import'), formData, {
                onSuccess: (page) => {
                    console.log('File imported successfully:', page);
                    this.loading=false
                },
                onError: (err) => {
                    console.log(err);
                    this.loading=false
                }
            });
        },
    }
};
</script>

<style scoped>
/* Optional custom styles for selection */
:deep(.ant-table-selection-column) {
    min-width: 60px;
}

:deep(.ant-table-row-selected) {
    background-color: #e6f7ff !important;
}

:deep(.ant-table-row-selected:hover) {
    background-color: #d4edff !important;
}
</style>