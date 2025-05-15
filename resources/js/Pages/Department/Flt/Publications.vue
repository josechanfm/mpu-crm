<template>
    <DepartmentLayout title="工作項目" :breadcrumb="breadcrumb">
        <div class="mx-auto pt-5">
            <div class="flex-auto pb-3 text-right">
                <a-button @click="createRecord" class="ant-btn ant-btn-primary">{{ $t('create') }}</a-button>
            </div>
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table :dataSource="publications" :columns="columns" :expand-column-width="200">
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="editRecord(record)">{{ $t('edit') }}</a-button>
                        </template>
                        <template v-else-if="column.dataIndex=='plan_date'">
                            {{ record['date_start'] }} : {{ record['date_end'] }}
                        </template>
                        <template v-else-if="column.dataIndex=='days_count'">
                            <div v-html="dayCount(record)"></div>
                        </template>
                        <template v-else-if="column.dataIndex=='active'">
                            {{ record[column.dataIndex]?'Yes':'No' }}
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
        :rules="rules"
        :validate-messages="validateMessages"
        :label-col="{ style:{width:'120px'}  }" :wrapper-col="{ span: 20 }"
      >
          <a-form-item label="title_zh名稱" name="category" >
            <a-select v-model:value="modal.data.category" :options="categories" />
          </a-form-item>
          <a-form-item label="title_zh名稱" name="title_zh" >
            <a-input type="inpuut" v-model:value="modal.data.title_zh" />
          </a-form-item>
          <a-form-item label="title_zh名稱" name="description_zh" >
            <a-textarea v-model:value="modal.data.description_zh" />
          </a-form-item>
          <a-form-item label="title_zh名稱" name="author" >
            <a-input type="inpuut" v-model:value="modal.data.author" />
          </a-form-item>
          <a-form-item label="title_zh名稱" name="print" >
            <a-textarea v-model:value="modal.data.print" />
          </a-form-item>
          <a-form-item label="Banner Image" name="banner_image">
                <div v-if="modal.data.cover" class="flex items-end">
                    <img :src="'/media/'+modal.data.cover" width="100" />
                    <button  @click="deleteConfirmed(modal.data)" class="text-red-500 px-2">
                            <DeleteOutlined />
                    </button>
                </div>
                <div v-else>
                    <div v-if="modal.data.preview" class="flex items-end">
                        <img :src="modal.data.preview" alt="banner" width="120"/>
                        <button class="text-red-500 px-2"  @click="removeSelectedUpload">
                            <DeleteOutlined />..
                        </button>
                    </div>
                    <div v-else>
                        <a-upload
                            v-model:file-list="modal.data.upload"
                            :multiple="false"
                            :max-count="1"
                            :beforeUpload="
                                () => {
                                return false;
                                }
                            "
                            @change="uploadChange"
                        >
                            <a-button>
                                <upload-outlined></upload-outlined>
                                Select File
                            </a-button>
                        </a-upload>
                    </div>
                </div>
            </a-form-item>

      </a-form>
      <template #footer>
        <a-button key="back" @click="modal.isOpen = false">{{ $t('close')}}</a-button>
        <a-button v-if="modal.mode=='EDIT'" key="submit" type="primary" @click="updateRecord">{{  $t('update') }}</a-button>
        <a-button v-if="modal.mode=='CREATE'" key="submit" type="primary" @click="storeRecord">{{  $t('save') }}</a-button>
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
    props: ["categories","publications"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"招聘流程" ,url:route('personnel.recruitment.workflows.index')},
                {label:"工作項目", url: null },
            ],
            loading: false,
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
                    title: "Title zh",
                    dataIndex: "title_zh",
                    minWidth:200,
                }, {
                    title: "工作項目名稱",
                    dataIndex: "name",
                    minWidth:200,
                }, {
                    title: "計劃時期",
                    dataIndex: "plan_date",
                    width: 200,
                }, {
                    title: "計劃日數",
                    dataIndex: "days",
                    width: 100,
                }, {
                    title: "實制開始日",
                    dataIndex: "target_start",
                    width: 120,
                }, {
                    title: "實制結束日",
                    dataIndex: "target_end",
                    width: 120,
                }, {
                    title: "質制日數",
                    dataIndex: "days_count",
                    width: 100,
                }, {
                    title: "狀能",
                    dataIndex: "active",
                    width: 100,
                }, {
                    title: "操作",
                    dataIndex: "operation",
                    key: "operation",
                    width: 240,
                },
            ],
            rules:{
                date_start: { required: true },
                date_end: { required: true },
                email: { type:'email' },
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
        createRecord() {
            this.modal.data = {};
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
        storeRecord() {
            this.$refs.modalRef.validateFields().then(() => {
                this.$inertia.post(route('flt.publications.store'), this.modal.data, {
                    onSuccess: (page) => {
                        this.modal.data = {};
                        this.modal.isOpen = false;
                    },
                    onError: (err) => {
                        console.log(err);
                    }
                });
            }).catch(err => {
                console.log(err);
            });
        },
        updateRecord() {
            this.$refs.modalRef.validateFields().then(() => {
                this.modal.data._method = "PATCH";
                this.$inertia.post(route('flt.publications.update', {
                     publication: this.modal.data,
                }), this.modal.data, {
                    onSuccess: (page) => {
                        this.modal.data = {};
                        this.modal.isOpen = false;
                        console.log(page);
                    },
                    onError: (error) => {
                        console.log(error);
                    }
                });
            }).catch(err => {
                console.log("error", err);
            });
        },

        deleteConfirmed(record) {
            this.$inertia.delete(route("flt.publications.destroy", {publication:record.id,type:'cover'} ), {
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
                route("flt.publications.index"),
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
