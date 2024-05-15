<template>
    <RecruitmentLayout title="Vacancies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ vacancy.code }}
                {{ vacancy['title_' + $page.props.lang] }}
            </h2>
        </template>
        <template v-if="$page.props.env == 'local'">
            <a-button @click="sampleData">Sample Data</a-button>
        </template>
        <div class="pb-5">
            <a-steps  progress-dot :current="4">
                <a-step v-for="item in lang.steps" :description="item.title"/>
            </a-steps>
        </div>
        <CardBox :title="lang.file_uploaded">
            <template #content>
                
                    <table width="100%">
                        <tr>
                            <th width="150px">{{ lang.doc_id }}</th>
                            <td width="50%">
                                <ol>
                                    <li v-for="file in getFileList('doc_id')">
                                        <a :href="file.full_path" target="_blank">{{file.original_name}}</a>
                                        <a class="pl-5" @click="deleteFile(file)"><delete-outlined /></a>
                                    </li>
                                </ol>
                            </td>
                            <td>
                                <a-upload 
                                    :showUploadList="false"
                                    name="doc_id"
                                    :multiple="true"
                                    :beforeUpload="handleBeforeUpload"
                                    :customRequest="customeFileUpload('doc_id')"
                                >
                                    <a-button>
                                    <upload-outlined></upload-outlined>
                                    {{ lang.upload }}
                                    </a-button>
                                </a-upload>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ lang.doc_education }}</th>
                            <td>
                                <ol>
                                    <li v-for="file in getFileList('doc_education')">
                                        <a :href="file.full_path" target="_blank">{{file.original_name}}</a>
                                    </li>
                                </ol>
                            </td>
                            <td> <button @click="()=>{console.log(this.$refs.asd)}">qwes</button>
                                <a-upload ref="asd"
                                    :showUploadList="false"
                                    name="doc_education"
                                    :multiple="true"
                                    :beforeUpload="handleBeforeUpload"
                                    :customRequest="customeFileUpload('doc_education')"
                                >
                                    <a-button>
                                    <upload-outlined></upload-outlined>
                                    {{ lang.upload }}
                                    </a-button>
                                </a-upload>
                                
                            </td>
                        </tr>
                        <tr>
                            <th>{{ lang.doc_resume }}</th>
                            <td>
                                <ol>
                                    <li v-for="file in getFileList('doc_resume')">
                                        <a :href="file.full_path" target="_blank">{{file.original_name}}</a>
                                    </li>
                                </ol>
                            </td>
                            <td>
                                <a-upload
                                    :showUploadList="false"
                                    name="doc_resume"
                                    :multiple="true"
                                    :beforeUpload="handleBeforeUpload"
                                    :customRequest="customeFileUpload('doc_resume')"
                                >
                                    <a-button>
                                    <upload-outlined></upload-outlined>
                                    {{ lang.upload }}
                                    </a-button>
                                </a-upload>
                               
                            </td>
                        </tr>
                        <tr>
                            <th>{{ lang.doc_employment }}</th>
                            <td>
                                <ol>
                                    <li v-for="file in getFileList('doc_employment')">
                                        <a :href="file.full_path" target="_blank">{{file.original_name}}</a>
                                    </li>
                                </ol>
                            </td>
                            <td>
                                <a-upload
                                    :showUploadList="false"
                                    name="doc_employment"
                                    :multiple="true"
                                    :beforeUpload="handleBeforeUpload"
                                    :customRequest="customeFileUpload('doc_employment')"
                                >
                                    <a-button>
                                    <upload-outlined></upload-outlined>
                                    {{ lang.upload }}
                                    </a-button>
                                </a-upload>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ lang.doc_training }}</th>
                            <td>
                                <ol>
                                    <li v-for="file in getFileList('doc_training')">
                                        <a :href="file.full_path" target="_blank">{{file.original_name}}</a>
                                    </li>
                                </ol>
                            </td>
                            <td>
                                <a-upload
                                    :showUploadList="false"
                                    name="doc_training"
                                    :multiple="true"
                                    :beforeUpload="handleBeforeUpload"
                                    :customRequest="customeFileUpload('doc_training')"
                                >
                                    <a-button>
                                    <upload-outlined></upload-outlined>
                                    {{ lang.upload }}
                                    </a-button>
                                </a-upload>

                            </td>
                        </tr>
                        <tr>
                            <th>{{ lang.doc_academic }}</th>
                            <td>
                                <ol>
                                    <li v-for="file in getFileList('doc_academic')">
                                        <a :href="file.full_path" target="_blank">{{file.original_name}}</a>
                                    </li>
                                </ol>
                            </td>
                            <td>
                                <a-upload
                                    :showUploadList="false"
                                    name="doc_academic"
                                    :multiple="true"
                                    :beforeUpload="handleBeforeUpload"
                                    :customRequest="customeFileUpload('doc_academic')"
                                >
                                    <a-button>
                                    <upload-outlined></upload-outlined>
                                    {{ lang.upload }}
                                    </a-button>
                                </a-upload>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ lang.doc_other }}</th>
                            <td>
                                <ol>
                                    <li v-for="file in getFileList('doc_other')">
                                        <a :href="file.full_path" target="_blank">{{file.original_name}}</a>
                                    </li>
                                </ol>
                            </td>
                            <td>
                                <a-upload
                                    :showUploadList="false"
                                    name="doc_other"
                                    :multiple="true"
                                    :beforeUpload="handleBeforeUpload"
                                    :customRequest="customeFileUpload('doc_other')"
                                >
                                    <a-button>
                                    <upload-outlined></upload-outlined>
                                    {{ lang.upload }}
                                    </a-button>
                                </a-upload>
                            </td>
                        </tr>
                    </table>
                {{ lang.doc_type_notes }}
            </template>
        </CardBox>
        <a-divider />
        <div class="text-center pt-5">
            <a-button :href="route('recruitment.application.form', { code: vacancy.code, page: this.page.previours })"
                class="bg-amber-500 text-white p-3 rounded-lg m-5">{{ lang.back_no_save }}</a-button>
            <a-button type="primary" @click="saveToNext">{{ lang.save_next }}</a-button>
        </div>

        <a-modal v-model:visible="confirmFileDelete" title="Basic Modal" @ok="deleteFileConfirmed">
            <p>Some contents...</p>
            <p>Some contents...</p>
            <p>Some contents...</p>
        </a-modal>
    </RecruitmentLayout>
</template>

<script>
import RecruitmentLayout from '@/Layouts/RecruitmentLayout.vue';
import CardBox from '@/Components/CardBox.vue';
import { CaretRightOutlined,UploadOutlined, DeleteOutlined, ConsoleSqlOutlined } from '@ant-design/icons-vue';
import recLang  from '/lang/recruitment.json';
import { message } from 'ant-design-vue';
import { Modal } from 'ant-design-vue';
import { createVNode, defineComponent } from 'vue';
import { ExclamationCircleOutlined } from '@ant-design/icons-vue';

import axios from 'axios';

export default {
    components: {
        RecruitmentLayout,
        CaretRightOutlined,
        CardBox,
        UploadOutlined,
        DeleteOutlined,
        Modal
    },
    props: ['vacancy', 'application'],
    data() {
        return {
            page: {},
            headers:{authorization:'header text'},
            confirmFileDelete:false,
            rules: {
                name_zh: { required: true },
                first_name_fn: { required: true },
                last_name_fn: { required: true },
                gender: { required: true },
                pob: { required: true },
                pob_oth: { required: true },
                dob: { required: true },
                id_type: { required: true },
                id_type_name: { required: true },
                id_num: { required: true },
                nationality: { required: true },
                phone: { required: true },
                email: { required: true },
            },
            activeTag: '1',
            activeCollapse: null,
            customStyle: 'background: #509f7f;border-radius: 4px;margin-bottom: 24px;border: 1;overflow: hidden',
        }
    },
    created() {
        this.lang = recLang[this.$page.props.lang]
    },
    mounted() {
        let urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('page')) {
            this.page.current = parseInt(urlParams.get('page'))
            this.page.previours = this.page.current - 1
            this.page.next = this.page.current + 1
        }
    },
    methods: {
        sampleData() {
            this.application.obtain_info = ['WEB', 'NEW'],
                this.application.obtain_info_new = 'Macao Daily',
                this.application.obtain_info_oth = 'Inernet',
                this.application.name_zh = '陳大文',
                this.application.first_name_fn = 'Tai Man',
                this.application.last_name_fn = 'Chan',
                this.application.gender = 'M',
                this.application.pob = 'OTH',
                this.application.pob_oth = 'Germany',
                this.application.dob = '1970-07-18',
                this.application.id_type = 'OTH',
                this.application.id_type_name = 'Germany',
                this.application.id_num = '123456789',
                this.application.nationality = 'German',
                this.application.phone = '66778899',
                this.application.email = 'chantaiman@example.com',
                this.application.address = 'Somewhere near by..'
        },
        saveToNext() {
            this.onFinish();
        },
        handleBeforeUpload(file){
            console.log(file)
            return true
        },
        customeFileUpload(fileType){
            return ({ onSuccess, onError, file })=> {
                let formData=new FormData();
                formData.append('rec_application_id',this.application.id)
                formData.append('document_type',fileType)
                formData.append('file',file)
                
                this.$inertia.post(route('recruitment.application.fileUpload'),formData, {
                    onSuccess: (page) => {
                        onSuccess(formData)
                    },
                    onError: (err) => {
                        onError(err)
                    }
                });
                return {status:'success'};
            }
        },
        varifyImage(info){
           
            console.log('varifyimage');
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
                //this.getBase64(info.file.originFileObj, (base64Url) => {
                //this.imageUrl = base64Url;
                //this.loading = true;
                //});
                console.log('image type and size are corrent!');
                return true
            } else {
                console.log('nonono image is not correct');
                return false;
                //this.form.image = [];
            }
        },
        getBase64(img, callback) {
            const reader = new FileReader();
            reader.addEventListener("load", () => callback(reader.result));
            reader.readAsDataURL(img);
        },
        getFileList(documentType){
            let files=this.application.uploads.filter(f=>f.document_type==documentType)
            return files;
        },
        deleteFile(file){
            Modal.confirm({
                title: 'Confirm',
                icon: createVNode(ExclamationCircleOutlined),
                content: 'Bla bla ...',
                okText: '确认',
                cancelText: '取消',
                onOk: ()=>{
                    this.$inertia.delete(route('recruitment.application.fileDelete',{rec_upload:file.id}), {
                        onSuccess: (page) => {
                            console.log(page.data)
                        },
                        onError: (err) => {
                            onError(err)
                        }
                    });
                },
                onCancel: ()=>{
                    console.log(' cancel')
                }
            });
           
            // this.$inertia.delete(route('recruitment.application.fileDelete',{rec_upload:file.id}), {
            //     onSuccess: (page) => {
            //         console.log(page.data)
            //     },
            //     onError: (err) => {
            //         onError(err)
            //     }
            // });
        },
        deleteFileConfirmed(){
            console.log('detel file cnfirmed')
        }
    },
};

</script>
<style>
label.ant-checkbox-wrapper {
    margin-left: 8px;
}

table,
table tr th,
table tr td {
    border: 1px solid gray;
    padding: 5px
}
</style>
