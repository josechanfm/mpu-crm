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
                <a-form :model="application" layout="vertical" :rules="rules" @finish="onFinish" @finishFailed="onFinishFailed">
                    <table width="100%">
                        <tr>
                            <th width="150px">{{ lang.doc_id }}</th>
                            <td width="50%"></td>
                            <td><button class="ant-btn"><upload-outlined></upload-outlined><span>{{ lang.upload }}</span></button></td>
                        </tr>
                        <tr>
                            <th>{{ lang.doc_education }}</th>
                            <td></td>
                            <td><button class="ant-btn"><upload-outlined></upload-outlined><span>{{ lang.upload }}</span></button></td>
                        </tr>
                        <tr>
                            <th>{{ lang.doc_resume }}</th>
                            <td></td>
                            <td><button class="ant-btn"><upload-outlined></upload-outlined><span>{{ lang.upload }}</span></button></td>
                        </tr>
                        <tr>
                            <th>{{ lang.doc_employment }}</th>
                            <td></td>
                            <td><button class="ant-btn"><upload-outlined></upload-outlined><span>{{ lang.upload }}</span></button></td>
                        </tr>
                        <tr>
                            <th>{{ lang.doc_training }}</th>
                            <td></td>
                            <td><button class="ant-btn"><upload-outlined></upload-outlined><span>{{ lang.upload }}</span></button></td>
                        </tr>
                        <tr>
                            <th>{{ lang.doc_academic }}</th>
                            <td></td>
                            <td>
                                <a-upload
                                    name="doc_academic"
                                    :multiple="true"
                                    :beforeUpload="handleBeforeUpload"
                                    :customRequest="customeFileUpload"
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
                            <td></td>
                            <td>
                                <a-upload
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
                </a-form>

                {{ lang.doc_type_notes }}
            </template>
        </CardBox>

        <!-- <div class="border border-sky-500 rounded-lg mt-5">
                    <h2 class="bg-sky-500 text-white p-4 rounded-t-lg">{{ lang.personal_info }}</h2>
                    <div class="p-4">
                        <p>Card content</p>
                    <p>Card content</p>
                    <p>Card content</p>
                    </div>
                </div> -->
        <a-divider />
        <div class="text-center pt-5">
            <a-button :href="route('recruitment.application.form', { code: vacancy.code, page: this.page.previours })"
                class="bg-amber-500 text-white p-3 rounded-lg m-5">{{ lang.back_no_save }}</a-button>
            <a-button type="primary" @click="saveToNext">{{ lang.save_next }}</a-button>
        </div>
    </RecruitmentLayout>
</template>

<script>
import RecruitmentLayout from '@/Layouts/RecruitmentLayout.vue';
import CardBox from '@/Components/CardBox.vue';
import { CaretRightOutlined, ConsoleSqlOutlined } from '@ant-design/icons-vue';
import { UploadOutlined } from '@ant-design/icons-vue';
import recLang  from '/lang/recruitment.json';
import { message } from 'ant-design-vue';
import axios from 'axios';

export default {
    components: {
        RecruitmentLayout,
        CaretRightOutlined,
        CardBox,
        UploadOutlined
       
    },
    props: ['vacancy', 'application'],
    data() {
        return {
            page: {},
            headers:{authorization:'header text'},
            docOthers:[],
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
        onFinish() {
            console.log('onFinish');
            this.$inertia.post(route('recruitment.application.save'), { to_page: 6, application: this.application }, {
                onSuccess: (page) => {
                    console.log(page.data)
                },
                onError: (err) => {
                    console.log(err)
                }
            });
        },
        onFinishFailed(){
            message.error(this.lang.error_required_fields);
        },
        handleBeforeUpload(file){
            return true
        },
        customeFileUpload(fileType){
            console.log(fileType);
            return ({ onSuccess, onError, file })=> {
                let formData=new FormData();
                formData.append('file_type',fileType)
                formData.append('file',file)
                
                console.log('upload files');
                console.log(formData)
                this.$inertia.post(route('recruitment.application.fileUpload',formData), {
                    onSuccess: (page) => {
                        console.log(page)
                        //this.formData = {};
                    },
                    onError: (err) => {
                        console.log(err);
                    }
                });

            }
        },
        // handleChange(info) {
        //     const status = info.file.status;
        //     if (status !== 'uploading') {
        //     // show update progress console.log(info.file, info.fileList);
        //     }
        //     if (status === 'done') {
        //     // show success message
        //     } else if (status === 'error') {
        //     // show error message
        //     }
        // },
         onChangeUpload(info){
            console.log('onchnageupload')
            console.log(info)
            const status = info.file.status;
            if (status !== 'uploading') {
                // show update progress console.log(info.file, info.fileList);
            }
            if (status === 'done') {
                // show success message
            } else if (status === 'error') {
                // show error message
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
