<template>
    <WebLayout title="電子書">
        <div class="mx-auto max-w-6xl px-4 py-6">
            <div class="mb-5 flex items-end justify-between gap-4">
                <div>
                    <h1 class="text-xl font-semibold text-gray-800">電子書</h1>
                    <p class="mt-1 text-sm text-gray-500">上傳 PDF，產生翻頁書與二維碼</p>
                </div>
                <a-button type="primary" @click="createRecord">新增電子書</a-button>
            </div>
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <a-table
                    :dataSource="ebooks"
                    :columns="columns"
                    row-key="id"
                    :pagination="false"
                >
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button size="small" @click="editRecord(record)">編輯</a-button>
                        </template>
                        <template v-else-if="column.dataIndex=='published'">
                            <a-tag :color="record.published ? 'green' : 'default'">
                                {{ record.published ? '已發佈' : '未發佈' }}
                            </a-tag>
                        </template>
                        <template v-else-if="column.dataIndex=='uid'">
                            <a :href="'/flipbooks/'+record.uid" target="_blank">開啟</a>
                        </template>
                        <template v-else-if="column.dataIndex=='qrcode'">
                            <button type="button" class="block" @click="showLargeImage(record)">
                                <img
                                    :src="'/flipbooks/'+record.uid+'/qrcode.png'"
                                    alt="QR code"
                                    class="h-12 w-12 rounded border border-gray-200 object-contain"
                                />
                            </button>
                        </template>
                        <template v-else>
                            {{ record[column.dataIndex] || '—' }}
                        </template>
                    </template>
                </a-table>
            </div>
        </div>

        <div
            v-if="isLargeImageOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/70"
            @click="isLargeImageOpen=false"
        >
            <div class="rounded-lg bg-white p-6 text-center" @click.stop>
                <img :src="'/flipbooks/'+imageSrc+'/qrcode.png'" alt="QR code" class="mx-auto max-h-[70vh]" />
                <div class="mt-3 text-sm text-gray-600">{{ imageTitle }}</div>
            </div>
        </div>

        <a-modal v-model:open="modal.isOpen" :title="modal.title" :width="640" :mask-closable="!loading">
            <a-form
                :model="modal.data"
                ref="modalRef"
                layout="vertical"
                :rules="rules"
                :validate-messages="validateMessages"
            >
                <a-form-item label="書籍名稱" name="title">
                    <a-input v-model:value="modal.data.title" placeholder="電子書標題" />
                </a-form-item>
                <a-form-item label="二維碼標題" name="qrcode_title">
                    <a-input v-model:value="modal.data.qrcode_title" placeholder="印在二維碼下方的文字" />
                    <div class="mt-1 text-xs text-gray-400">留空時使用書籍名稱</div>
                </a-form-item>
                <a-form-item label="說明" name="description">
                    <a-textarea v-model:value="modal.data.description" :rows="3" placeholder="簡短說明" />
                </a-form-item>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <a-form-item label="開始日期" name="date_start">
                        <a-date-picker v-model:value="modal.data.date_start" :format="dateFormat" :valueFormat="dateFormat" class="w-full" />
                    </a-form-item>
                    <a-form-item label="結束日期" name="date_end">
                        <a-date-picker v-model:value="modal.data.date_end" :format="dateFormat" :valueFormat="dateFormat" class="w-full" />
                    </a-form-item>
                </div>
                <a-form-item label="PDF 檔案">
                    <a-upload
                        accept=".pdf"
                        :before-upload="beforeUpload"
                        :show-upload-list="false"
                    >
                        <a-button>
                            <UploadOutlined /> {{ pdfName ? '更換 PDF' : '選擇 PDF' }}
                        </a-button>
                    </a-upload>
                    <div v-if="pdfName" class="mt-2 text-sm text-gray-600">{{ pdfName }}</div>
                    <div class="mt-1 text-xs text-gray-400">PDF，最大 20MB。編輯時不選檔案則保留現有翻頁書。</div>
                </a-form-item>
                <a-form-item label="發佈">
                    <a-switch v-model:checked="modal.data.published" />
                </a-form-item>
            </a-form>
            <template #footer>
                <a-button :disabled="loading" @click="modal.isOpen = false">取消</a-button>
                <a-button v-if="modal.mode=='EDIT'" type="primary" :loading="loading" @click="updateRecord">儲存</a-button>
                <a-button v-if="modal.mode=='CREATE'" type="primary" :loading="loading" @click="storeRecord">建立</a-button>
            </template>
        </a-modal>
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
import { message } from "ant-design-vue";
import dayjs from 'dayjs';
import axios from "axios";

export default {
    components: {
        WebLayout,
        UploadOutlined, LoadingOutlined, PlusOutlined, DeleteOutlined,
        RestFilled,
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
            imageTitle:'',
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
                { title: "書籍名稱", dataIndex: "title" },
                { title: "二維碼標題", dataIndex: "qrcode_title" },
                { title: "開始", dataIndex: "date_start", width: 120 },
                { title: "結束", dataIndex: "date_end", width: 120 },
                { title: "狀態", dataIndex: "published", width: 100 },
                { title: "連結", dataIndex: "uid", width: 80 },
                { title: "二維碼", dataIndex: "qrcode", width: 90 },
                { title: "操作", dataIndex: "operation", width: 90 },
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
    computed: {
        pdfName() {
            const files = this.modal.data.file;
            if (!files || !files.length) return '';
            return files[files.length - 1].name || '';
        },
    },
    methods: {
        showLargeImage(record){
            this.isLargeImageOpen=true
            this.imageSrc=record.uid
            this.imageTitle = '按右鍵複製'
        },
        createRecord() {
            this.modal.data = {
                published:false,
                qrcode_title:'',
                title:'',
                description:'',
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
            message.error('PDF 需小於 20MB');
            return false;
            }
            this.modal.data.file = [file];
            return false;
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
                    forceFormData: true,
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
                    forceFormData: true,
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
