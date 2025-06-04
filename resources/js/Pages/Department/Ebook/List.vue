<template>
    <WebLayout title="工作項目" >
        <div class="mx-auto pt-5">
            <div class="flex-auto pb-3 text-right">
                <a-button @click="createRecord" class="ant-btn ant-btn-primary">{{ $t('create') }}</a-button>
            </div>
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table :dataSource="ebooks" :columns="columns" :expand-column-width="200">
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="editRecord(record)">{{ $t('edit') }}</a-button>
                        </template>
                        <template v-else-if="column.dataIndex=='date'">
                            {{ record['date_start'] }} : {{ record['date_end'] }}
                        </template>
                        <template v-else-if="column.dataIndex=='uid'">
                            <a :href="'/flipbooks/'+record['uid']" target="_blank">Link</a>
                        </template>
                        <template v-else-if="column.dataIndex=='qrcode'">
                            <img :src="'/flipbooks/'+record['uid']+'/qrcode.png'" 
                                alt="MPU eBook, QR Code" width="50px"
                                @click="showLargeImage(record)"
                            />
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
      <img :src="'/flipbooks/'+imageSrc+'/qrcode.png'" alt="Enlarged Image" class="max-w-full max-h-full" />
    </div>

            <!-- Modal Start-->
    <a-modal v-model:open="modal.isOpen" :title="modal.title" width="60%">
      <a-form
        :model="modal.data"
        ref="modalRef"
        name="default"
        layout="horizontal"
        :rules="rules"
        :validate-messages="validateMessages"
        :label-col="{ style:{width:'120px'}  }" :wrapper-col="{ span: 20 }"
      >
          <a-form-item label="Title" name="title" >
            <a-input type="inpuut" v-model:value="modal.data.title" />
          </a-form-item>
          <a-form-item label="Description" name="description" >
            <a-textarea v-model:value="modal.data.description" />
          </a-form-item>
          <a-form-item label="Date Start" name="date_start" >
            <a-date-picker v-model:value="modal.data.date_start" :format="dateFormat" :valueFormat="dateFormat"/>
          </a-form-item>
          <a-form-item label="Date End" name="date_end" >
            <a-date-picker v-model:value="modal.data.date_end" :format="dateFormat" :valueFormat="dateFormat"/>
          </a-form-item>
          <a-form-item label="PDF file" name="pdf_file">
            <a-upload
              :file-list="modal.data.file"
              accept=".pdf"
              @change="handleFileChange"
              :before-upload="beforeUpload"
              :show-upload-list="false"
              :auto-upload="false"  
            >
              <a-button>
                <UploadOutlined /> Click to Upload
              </a-button>
            </a-upload>
            <a-spin v-if="loading" />
           </a-form-item>
           <a-form-item label="Date End" name="date_end" >
            {{ modal.data.published }}
            <a-switch v-model:checked="modal.data.published"/>
           </a-form-item>

      </a-form>
      <template #footer>
        <a-button key="back" @click="modal.isOpen = false">{{ $t('close')}}</a-button>
        <a-button v-if="modal.mode=='EDIT'" key="submit" type="primary" @click="updateRecord">{{  $t('update') }}</a-button>
        <a-button v-if="modal.mode=='CREATE'" key="submit" type="primary" @click="storeRecord">{{  $t('save') }}</a-button>
      </template>
    </a-modal>
    <!-- Modal End-->

    </WebLayout>
</template>

<script>
import WebLayout from "@/Layouts/WebLayout.vue";
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
        WebLayout,
        UploadOutlined, LoadingOutlined, PlusOutlined, DeleteOutlined,
        RestFilled,
        quillEditor,
        message,
        dayjs
    },
    props: ["ebooks"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"招聘流程" ,url:route('personnel.recruitment.workflows.index')},
                {label:"工作項目", url: null },
            ],
            isLargeImageOpen:false,
            imageSrc:null,
            loading:false,
            dateFormat: "YYYY-MM-DD",
            pdfFile:{
                title: '', // Title input
                description: '', // Description input
                file: null,
            },
            modal: {
                isOpen: false,
                data: {},
                title: "Modal",
                mode: ""
            },
            // pagination: {
            //     total: this.workflows.total,
            //     current: this.workflows.current_page,
            //     pageSize: this.workflows.per_page,
            //     defaultPageSize:40,
            //     showSizeChanger:true,
            //     pageSizeOptions:['10','20','30','40','50']
            // },
            columns: [
                {
                    title: "Title",
                    dataIndex: "title",
                }, {
                    title: "Date Start",
                    dataIndex: "date_start",
                }, {
                    title: "Date End",
                    dataIndex: "date_end",
                }, {
                    title: "Link",
                    dataIndex: "uid",
                }, {
                    title: "QRcode",
                    dataIndex: "qrcode",
                }, {
                    title: "操作",
                    dataIndex: "operation",
                    key: "operation",
                    width: 240,
                },
            ],
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
    }, 
    methods: {
        showLargeImage(record){
            this.isLargeImageOpen=true
            this.imageSrc=record.uid
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
        handleFileChange(info) {
            if (info.file.status === 'done') {
            this.successMessage = 'File uploaded successfully.';
            this.errorMessage = '';
            } else if (info.file.status === 'error') {
            this.errorMessage = 'Upload failed.';
            this.successMessage = '';
            }
            this.file = info.file.originFileObj;
        },
        beforeUpload(file) {
            const isPdf = file.type === 'application/pdf';
            const isUnder2MB = file.size / 1024 / 1024 < 20; // Check if file size is less than 20MB
    
            if (!isPdf) {
            message.error('You can only upload PDF files!');
            return false; // Prevent upload
            }
    
            if (!isUnder2MB) {
            message.error('File size must be smaller than 5MB!');
            return false; // Prevent upload
            }
            this.modal.data.file = [...(this.modal.data.file || []), file];
            return false; // Allow upload
        },
        submit() {
            this.loading=true
            console.log(this.pdfFile);
            return true;
            if (!this.pdfFile.file) {
                message.error('Please select a file to upload.');
            return;
            }
            // Use Inertia for the API call
            this.$inertia.post(this.route('manage.ebooks.store'), this.pdfFile, {
                onSuccess: () => {
                    this.loading=false
                    this.successMessage = 'File uploaded successfully.';
                    this.errorMessage = '';
                },
                onError: (errors) => {
                    this.errorMessage = 'Upload failed: ' + errors;
                    this.successMessage = '';
                },
            });
        },


        storeRecord() {
            this.$refs.modalRef.validateFields().then(() => {
                this.loading=true
                this.$inertia.post(route('manage.ebooks.store'), this.modal.data, {
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
                this.$inertia.post(route('manage.ebooks.update', {
                     ebook: this.modal.data,
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
            this.$inertia.delete(route("manage.ebooks.destroy", {publication:record.id,type:'cover'} ), {
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
            this.$inertia.get(
                route("manage.ebooks.index"),
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
                console.log("image format!");
                message.error("You can only upload JPG/PNG file!");
            }
            const isLt2M = info.file.size / 1024 / 1024 < 0.2;
            if (!isLt2M) {
                console.log("image size");
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

    },
};
</script>
