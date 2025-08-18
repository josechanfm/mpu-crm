<template>
    <WebLayout title="工作項目" >
        <div class="mx-auto pt-5">
            <div class="flex-auto pb-3 text-right">
                <a-button @click="createRecord" class="ant-btn ant-btn-primary">{{ $t('create') }}</a-button>
            </div>
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table :dataSource="souvenirs" :columns="columns">
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="editRecord(record)">{{ $t('edit') }}</a-button>
                        </template>
                        <template v-else-if="column.dataIndex=='images'">
                            <div class="flex flex-wrap">
                                <span v-for="(image, idx) in record.images" :key="idx" class="m-1 w-1/3">
                                    <img 
                                        :src="image" 
                                        alt="MPU Souvenir" class="w-full h-auto"
                                        @click="showLargeImage(record)"
                                    />
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
            <a-textarea v-model:value="modal.data.description" />
          </a-form-item>
          <a-form-item label="Qty" name="qty" >
            <a-input-number v-model:value="modal.data.qty" />
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
            <a-switch v-model:checked="modal.data.available"/>
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
        <a-button v-if="modal.mode=='EDIT'" key="submit" type="primary" @click="updateRecord" :disabled="isProcessingFiles || loading" :loading="loading">{{  $t('update') }}</a-button>
        <a-button v-if="modal.mode=='CREATE'" key="submit" type="primary" @click="storeRecord" :disabled="isProcessingFiles || loading" :loading="loading">{{  $t('save') }}</a-button>
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
    props: ["souvenirs"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"招聘流程" ,url:route('personnel.recruitment.workflows.index')},
                {label:"工作項目", url: null },
            ],
            isProcessingFiles:false,
            isLargeImageOpen:false,
            imageSrc:null,
            loading:false,
            dateFormat: "YYYY-MM-DD",
            modal: {
                isOpen: false,
                data: {},
                title: "Modal",
                mode: ""
            },
            columns: [
                {
                    title: "Name",
                    dataIndex: "name",
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

        showLargeImage(record){
            this.isLargeImageOpen=true
            this.imageSrc=record.thumbnail
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
            this.$inertia.get(
                route("manage.souvenirs.index"),
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
        deleteImage(souvenir, idx){
            axios.post(route('dae.souvenir.removeImage', { souvenir: souvenir.id, imageId: idx }))
                .then(response => {
                    // Update the local state to reflect the removed image
                    // this.souvenirs.find(s => s.id === souvenir.id).images.splice(idx, 1);
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
