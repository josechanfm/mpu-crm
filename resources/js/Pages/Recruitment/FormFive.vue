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
        {{ application.uploads }}
        <CardBox :title="$t('rec.position_info')">
            <template #content>
                <a-form :model="application" layout="vertical" :rules="rules" @finish="onFinish">
                    <table width="100%">
                        <tr>
                            <th width="150px">{{ $t('rec.doc_id') }}</th>
                            <td width="50%"></td>
                            <td><button class="ant-btn">upload</button></td>
                        </tr>
                        <tr>
                            <th>{{ $t('rec.doc_educations') }}</th>
                            <td></td>
                            <td><button class="ant-btn">upload</button></td>
                        </tr>
                        <tr>
                            <th>{{ $t('rec.doc_resume') }}</th>
                            <td></td>
                            <td><button class="ant-btn">upload</button></td>
                        </tr>
                        <tr>
                            <th>{{ $t('rec.doc_employment') }}</th>
                            <td></td>
                            <td><button class="ant-btn">upload</button></td>
                        </tr>
                        <tr>
                            <th>{{ $t('rec.doc_training') }}</th>
                            <td></td>
                            <td><button class="ant-btn">upload</button></td>
                        </tr>
                        <tr>
                            <th>{{ $t('rec.doc_academic') }}</th>
                            <td></td>
                            <td><button class="ant-btn">upload</button></td>
                        </tr>
                        <tr>
                            <th>{{ $t('rec.doc_others') }}</th>
                            <td></td>
                            <td>
                                <a-upload
                                    v-model:file-list="docOthers"
                                    name="file"
                                    :multiple="true"
                                    :customRequest="{uploadFiles}"
                                    @change="onChangeUpload"
                                >
                                    <a-button>
                                    <upload-outlined></upload-outlined>
                                    {{ $t('file_upload')}}
                                    </a-button>
                                </a-upload>
                            </td>
                        </tr>
                    </table>
                </a-form>

                {{ $t('rec.doc_type_notes') }}
            </template>
        </CardBox>

        <!-- <div class="border border-sky-500 rounded-lg mt-5">
                    <h2 class="bg-sky-500 text-white p-4 rounded-t-lg">{{ $t('rec.personal_info') }}</h2>
                    <div class="p-4">
                        <p>Card content</p>
                    <p>Card content</p>
                    <p>Card content</p>
                    </div>
                </div> -->
        <a-divider />
        <div class="text-center pt-5">
            <a-button :href="route('application.apply', { code: vacancy.code, page: this.page.previours })"
                class="bg-amber-500 text-white p-3 rounded-lg m-5">{{ $t('rec.back_no_save') }}</a-button>
            <a-button type="primary" @click="saveToNext">{{ $t('rec.save_next') }}</a-button>
        </div>
    </RecruitmentLayout>
</template>

<script>
import RecruitmentLayout from '@/Layouts/RecruitmentLayout.vue';
import CardBox from '@/Components/CardBox.vue';
import { CaretRightOutlined } from '@ant-design/icons-vue';
import { UploadOutlined } from '@ant-design/icons-vue';
import { message } from 'ant-design-vue';

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
            this.$inertia.post(route('application.save'), { to_page: 6, application: this.application }, {
                onSuccess: (page) => {
                    console.log(page.data)
                },
                onError: (err) => {
                    console.log(err)
                }
            });
        },
        uploadfiles(options){
            console.log(options)
        },
        onChangeUpload(info){
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
            console.log(info);
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
