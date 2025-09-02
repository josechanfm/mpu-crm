<template>
    <DepartmentLayout title="Souvenirs" >
        <div class="mx-auto pt-5">
            <div class="flex flex-justify justify-end pb-3 gap-5">
                <a-button :href="route('dae.dashboard')">Back</a-button>
                <a-button type="primary" @click="createRecord">Create</a-button>
            </div>
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <div class="flex flex-wrap p-5 gap-4">
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
                    <div class="flex items-center">
                        <a-switch 
                            v-model:checked="myFilter.show_all" 
                            checkedChildren="Only Available" 
                            unCheckedChildren="Show All" 
                        />
                    </div>
                    <div class="flex items-center space-x-2">
                        <a-button type="primary" @click="onClickFilter">Filter</a-button>
                        <a-button @click="clearMyFilter">Clear</a-button>
                    </div>
                </div>
                <a-table :dataSource="souvenirs.data" :columns="columns" :pagination="pagination" @change="onPaginationChange">
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="editRecord(record)">Edit</a-button>
                        </template>
                        <template v-else-if="column.dataIndex=='images'">
                            <div class="flex flex-wrap">
                                <span v-for="(image, idx) in record.images" :key="idx" class="m-1 w-1/3">
                                    <img :src="image" alt="MPU Souvenir" class="w-full h-auto" @click="showLargeImage(image)"/>
                                </span>
                            </div>
                        </template>
                        <template v-else>
                            {{ record[column.dataIndex] }}
                        </template>
                    </template>
                </a-table>
            </div>
        </div>

    <!-- Overlay for enlarged image -->
    <div
      v-if="isLargeImageOpen"
      class="fixed inset-0 bg-black bg-opacity-75 flex justify-center items-center z-50"
      @click="isLargeImageOpen=false"
    >
      <img :src="imageSrc" alt="Enlarged Image" class="max-w-full max-h-full" />
    </div>

    <!-- Modal Start-->
    <a-modal v-model:open="modal.isOpen" :title="modal.title" width="60%" @cancel="handleModalClose"
>
      <a-form
        :model="modal.data"
        ref="modalRef"
        name="default"
        layout="horizontal"
        :rules="rules"
        :validate-messages="validateMessages"
        :label-col="{ style:{width:'120px'}  }" :wrapper-col="{ span: 20 }"
      >
          <a-form-item label="Name" name="name" >
            <a-input type="inpuut" v-model:value="modal.data.name" />
          </a-form-item>
          <a-form-item label="Description" name="description" >
            <quill-editor
                v-model:value="modal.data.description"
                style="min-height: 200px"
            />
            <a-textarea v-model:value="modal.data.description" />
          </a-form-item>
          <a-form-item label="Stock" name="stock" >
            <a-input-number v-model:value="modal.data.stock" />
          </a-form-item>
          <a-form-item label="price" name="price" >
            <a-input-number v-model:value="modal.data.price" />
          </a-form-item>
          <a-form-item label="Quota" name="quota" >
            <a-input-number v-model:value="modal.data.quota" />
          </a-form-item>
           <a-form-item label="Available" name="available" >
            <a-switch v-model:checked="modal.data.is_available"/>
           </a-form-item>
           <a-form-item label="Uploaded Images">
                <div class="flex flex-wrap">
                    <span v-for="(image, idx) in modal.data.images" :key="idx" class="m-1">
                        <img 
                            :src="image" 
                            alt="MPU Souvenir"
                            width="100px"
                        />
                        <span @click="deleteImage(modal.data, idx)">Delete</span>
                    </span>
                </div>

           </a-form-item>
            <a-form-item label="Images">
            <a-upload
                v-model:file-list="modal.data.files"
                list-type="picture-card"
                accept=".jpg,.jpeg,.png"
                :multiple="true"
                :auto-upload="false"
                :customRequest="null"
                :before-upload="beforeUpload"
                @change="handleFileChange"
            >
                <UploadOutlined /> Upload
            </a-upload>
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
    ConsoleSqlOutlined,
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
    props: ["souvenirs"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"招聘流程" ,url:route('personnel.recruitment.workflows.index')},
                {label:"工作項目", url: null },
            ],
            filesProcessed: 0,
            isLargeImageOpen:false,
            imageSrc:null,
            loading:false,
            dateFormat: "YYYY-MM-DD",
            myFilter :{
                show_all:false, 
                search: {  
                    options: [
                        { label: "Name", value: "name" },
                        { label: "Stock", value: "stock" },
                        { label: "Price", value: "price" },
                        { label: "Quota", value: "quota" },
                    ],
                    column: null, // Selected search column
                    text: null, // search text
                },
                sort:{
                    options: [
                        { label: "Name", value: "name" },
                        { label: "Stock", value: "stock" },
                        { label: "Price", value: "price" },
                        { label: "Quota", value: "quota" },
                    ],
                    column: null, // Default to 'name' if not specified
                    order: 'asc' // 'asc' or 'desc'
                }
            },
            modal: {
                isOpen: false,
                data: {},
                title: "Modal",
                mode: ""
            },
            rules:{
                title: { required: true },
                date_start: { required: true },
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
        this.refreshMyFilter();
    }, 
    computed:{
        urlParams(){
            return new URLSearchParams(window.location.search);
        },
        pagination() {
            this.myFilter.sort.column = this.urlParams.get('sort_column');
            this.myFilter.sort.order = this.urlParams.get('sort_order');
            this.myFilter.search.column = this.urlParams.get('search_column');
            this.myFilter.search.text = this.urlParams.get('search_text');
            return {
                current: this.souvenirs.current_page,
                pageSize: this.souvenirs.per_page,
                total: this.souvenirs.total,
                showSizeChanger: true,
                showTotal: (total, range) => `Showing ${range[0]}-${range[1]} of ${total} items`,
            };
        },
        isProcessingFiles() {
            console.log('fileProcessed',this.modal.data.files);
            return this.filesProcessed < this.modal.data.files?.length;
        },
        columns(){
            return [
                {
                    title: "Name",
                    dataIndex: "name",
                    sorter: (a, b) => this.safeStringCompare(a.name, b.name),
                    sortOrder: this.myFilter.sort.column=='name'?this.myFilter.sort.order:null,
                }, {
                    title: "Quantity",
                    dataIndex: "qty",
                }, {
                    title: "Stock",
                    dataIndex: "stock",
                }, {
                    title: "price",
                    dataIndex: "price",
                }, {
                    title: "Quota",
                    dataIndex: "quota",
                }, {
                    title: "Images",
                    dataIndex: "images",
                }, {
                    title: "Available",
                    dataIndex: "is_available",
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
        clearMyFilter(){
            this.myFilter.search.column = null;
            this.myFilter.search.text = '';
            this.myFilter.sort.column = null;
            this.myFilter.sort.order = null;
            this.pagination.current = 1; // Reset to first page
            this.onPaginationChange(this.pagination);

            // this.$inertia.get(route("dae.souvenirs.index"), {}, {
            //     preserveState: true,
            //     preserveScroll: true,
            // });
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
        safeStringCompare(a, b, nullsFirst = false) {
            if (a == null && b == null) return 0;
            if (a == null) return nullsFirst ? -1 : 1;
            if (b == null) return nullsFirst ? 1 : -1;
            return String(a).localeCompare(String(b));
        },
        beforeUpload(file) {
            const isImage = file.type === 'image/jpeg' || file.type === 'image/png';
            if (!isImage) {
            message.error('You can only upload JPG/PNG files!');
            this.filesProcessed++;
            this.checkProcessingComplete();
            return false;
            }
            
            const isLt5MB = file.size / 1024 / 1024 < 5;
            if (!isLt5MB) {
            message.error('Image must be smaller than 5MB!');
            this.filesProcessed++;
            this.checkProcessingComplete();
            return false;
            }
            
            // Generate preview thumbnail
            this.generateThumbnail(file).then(() => {
            this.filesProcessed++;
            this.checkProcessingComplete();
            });
            
            return false; // Prevent automatic upload
        },
        
        handleFileChange({ file, fileList }) {
            this.modal.data.files = fileList.filter(f =>  f.status === 'done' || f.originFileObj);
        },
        
        async handlePreview(file) {
            if (!file.url && !file.preview) {
            file.preview = await this.getBase64(file.originFileObj);
            }
            this.previewImage = file.url || file.preview;
            this.previewVisible = true;
        },
        showLargeImage(image){
            this.isLargeImageOpen=true
            this.imageSrc=image || null;
        },
        createRecord() {
            this.modal.data = {
                published:false  
            };
            this.modal.mode = "CREATE";
            this.modal.title = "Create new item";
            this.modal.isOpen = true;
        },
        editRecord(record) {
            this.modal.data = { ...record };
            this.modal.mode = "EDIT";
            this.modal.title = "Edit item";
            this.modal.isOpen = true;
        },
        handleModalClose(){
            console.log('modal is going to close')
            this.modal.isOpen=false
            location.reload();
        },
        storeRecord() {
            this.$refs.modalRef.validateFields().then(() => {
                this.loading=true
                this.$inertia.post(route('dae.souvenirs.store'), this.modal.data, {
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
            }).catch(err => {
                console.log(err);
            });
        },
        updateRecord() {
            this.$refs.modalRef.validateFields().then(() => {
                this.loading=true
                this.modal.data._method = "PATCH";
                this.$inertia.post(route('dae.souvenirs.update', {
                     souvenir: this.modal.data,
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
            }).catch(err => {
                console.log("error", err);
            });
        },

        deleteConfirmed(record) {
            this.$inertia.delete(route("dae.souvenirs.destroy", {publication:record.id,type:'cover'} ), {
                onSuccess: (page) => {
                    this.modal.data.cover=null
                },
                onError: (error) => {
                    alert(error.message);
                },
            });
        },
        removeSelectedUpload(){
            this.modal.data.preview=null
            this.modal.data.upload=null
        },
        onPaginationChange(page, filters, sorter) {
            console.log(page, filters, sorter);
            this.$inertia.get(
                route("dae.souvenirs.index"),
                {
                    page: page.current,
                    per_page: page.pageSize,
                    show_all: this.myFilter.show_all,
                    search_column:this.myFilter.search.column,
                    search_text:this.myFilter.search.text,
                    sort_column: this.myFilter.sort.column,
                    sort_order: this.myFilter.sort.order,
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
        dayCount(record){
            const start = dayjs(record.target_start)
            const end = dayjs(record.target_end)
            const days=end.diff(start,'days')
            const diff=record.days-days

            if(diff==0){
                return  "<font color='black'>"+days+"</font>"
            }else if(diff<0){
                return  "<font color='red'>"+days+ "("+(diff)+")</font>"
            }else{
                return  "<font color='green'>"+days+ "("+(diff)+")</font>"
            }
            
        },
        uploadChange(info) {
            console.log('check file before upload');
            console.log(info);
            this.modal.data.preview=URL.createObjectURL(info.file)
            console.log(this.modal.data.preview);
            const isJpgOrPng =
                info.file.type === "image/jpeg" || info.file.type === "image/png";
            if (!isJpgOrPng) {
                message.error("You can only upload JPG/PNG file!");
            }
            const isLt2M = info.file.size / 1024 / 1024 < 0.2;
            if (!isLt2M) {
                message.error("Image must smaller than 2MB!");
            }

            if (isJpgOrPng && isLt2M) {
                //this.modal.data.coverUrl=info.file
                // this.getBase64(info.file, (base64Url) => {
                //     this.modal.data.coverUrl = base64Url;
                //     this.loading = true;
                // });
            } else {
                this.modal.data.cover = [];
            }
        },
        getBase64(img, callback) {
            const reader = new FileReader();
            reader.addEventListener("load", () => callback(reader.result));
            reader.readAsDataURL(img);
        },
        deleteImage(souvenir, idx){
            axios.post(route('dae.souvenir.removeImage', { souvenir: souvenir.id, imageId: idx }))
                .then(response => {
                    console.log(response.data)
                    souvenir.images=response.data.images;
                    this.$message.success('Image removed successfully.');
                })
                .catch(err => {
                    console.log(err);
                    this.$message.error('Error removing image.');
                });
        }

    },
};
</script>
