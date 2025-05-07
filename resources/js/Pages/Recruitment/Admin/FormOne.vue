<template>
    <MemberLayout title="Vacancies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ vacancy.code }}
                {{ vacancy['title_' + $page.props.lang] }}
            </h2>
        </template>
        <div class="p-5">
            <a-steps  progress-dot :current="0">
                <a-step v-for="item in lang.steps" :description="item.title"/>
            </a-steps>
        </div>
        <template v-if="$page.props.env == 'local'">
            <a-button @click="sampleData">Sample Data</a-button>
        </template>
        <div class="container bg-white rounded mx-auto p-5">
            <a-form 
                :model="application" layout="vertical" 
                :rules="rules" 
                :validate-messages="validateMessages"
                @finish="onFinish" 
                @finishFailed="onFinishFailed"
            >
                <CardBox :title="$t('rec.position_info')">
                    <template #content>
                        <a-row>
                            <a-col :span="16">
                                <p><span class="font-black">{{ lang.code }}：</span>{{ vacancy.code }}</p>
                                <p><span class="font-black">{{ lang.title }}：</span>{{ vacancy['title_' + $page.props.lang] }}</p>
                                <p><span class="font-black">{{ lang.exam_lang }}：</span>
                                       <a-radio-group v-model:value="application.exam_lang" button-style="solid">
                                        <a-radio-button value="zh">{{ lang.lang_options['zh'] }}</a-radio-button>
                                        <a-radio-button value="pt">{{ lang.lang_options['pt'] }}</a-radio-button>
                                    </a-radio-group>

                                </p>
                            </a-col>
                            <a-col>
                                <p><span class="font-black">{{ lang.required_edu }}：</span>{{ getConfigLabel(educations,vacancy.education) }}</p>
                                <p><span class="font-black">{{ lang.required_doc }}：</span>{{ getConfigLabel(vehicles,vacancy.vehicle) }}</p>
                            </a-col>
                        </a-row>
                    </template>
                </CardBox>
                <CardBox :title="lang.notice_title" themeColor="amber-500">
                    <template #content>
                        <div class="pl-5 pr-3">
                            <div v-html="lang.notice_content" />
                        </div>
                    </template>
                </CardBox>
                <CardBox :title="lang.declaration_title" themeColor="amber-500">
                    <template #content>
                        <div class="pl-5 pr-3">
                            <div v-html="lang.declaration_content" />
                        </div>
                    </template>
                </CardBox>
                <CardBox :title="lang.personal_info">
                    <template #content>
                        <a-row :gutter="12">
                            <a-col :span="16">
                                <a-form-item :label="lang.name_full_zh" name="name_full_zh">
                                    <a-input v-model:value="application.name_full_zh" />
                                </a-form-item>
                            </a-col>
                            <a-col :span="8">
                                <a-form-item :label="lang.gender" name="gender">
                                    <a-radio-group v-model:value="application.gender">
                                        <a-radio value="M">{{ lang.male }}</a-radio>
                                        <a-radio value="F">{{ lang.female }}</a-radio>
                                    </a-radio-group>
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-form-item :label="lang.name_full_fn" name="name_full_fn">
                            <a-input v-model:value="application.name_full_fn" />
                        </a-form-item>
                        <a-row :gutter="12">
                            <a-col :span="12">
                                <a-form-item :label="lang.id_num" name="id_num">
                                    <a-input v-model:value="application.id_num" />
                                    <div class="custom-label float-right">
                                        {{ lang.id_required_copy }}
                                    </div>

                                </a-form-item>
                            </a-col>
                            <a-col :span="12">
                                <a-form-item :label="lang.dob" name="dob">
                                    <a-input v-model:value="application.dob" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="12">
                            <a-col :span="12">
                                <a-form-item :label="lang.email" name="email">
                                    <a-input v-model:value="application.email" />
                                </a-form-item>
                            </a-col>
                            <a-col :span="12">
                                <a-form-item :label="lang.phone" name="phone">
                                    <a-input v-model:value="application.phone" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-form-item :label="lang.address" name="address">
                            <a-input v-model:value="application.address" />
                        </a-form-item>

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
                <a-form-item :wrapper-col="{ span: 24, offset: 11, }">
                    <a-button type="primary" html-type="submit">{{ lang.save_next }}</a-button>
                </a-form-item>
            </a-form>
        </div>
    </MemberLayout>
</template>

<script>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import CardBox from '@/Components/CardBox.vue';
import { CaretRightOutlined, TranslationOutlined } from '@ant-design/icons-vue';
import recLang  from '/lang/recruitment_admin.json';
import { message,Modal} from 'ant-design-vue';
import { computed } from 'vue';

export default {
    components: {
        MemberLayout,
        CaretRightOutlined,
        CardBox,
    },
    props: ['educations','vehicles','vacancy', 'application'],
    data() {
        return {
            page: {},
            rules: {
                name_full_zh: { 
                    required: true,
                    validator: ((_rule, value)=>{
                        return new Promise((resolve,reject)=>{
                            const chineseRegex = /^[\u4e00-\u9fa5]+$/;
                            if (value!=null && value!='' && !chineseRegex.test(value)) {
                                reject(this.lang.is_required);
                            } else {
                                resolve();
                            }
                        })
                    })
                },
                name_family_fn: { required: true },
                name_given_fn: { required: true },
                gender: { required: true },
                pob: { 
                    required: true, 
                    validator: ((_rule, value) => {
                        return new Promise((resolve, reject)=>{
                            if (value==='OT' && (this.application.pob_oth===null || this.application.pob_oth==='')) {
                                reject(this.lang.pob+this.$t('is_required'));
                            }else{
                                resolve();
                            } 
                        })
                    }),
                },
                pob_oth: { required: true },
                dob: { required: true },
                id_type: { required: true },
                id_type_name: { required: true },
                id_num: { required: true },
                nationality: { 
                    required: true,
                    validator: ((_rule, value) => {
                        return new Promise((resolve, reject)=>{
                            if (value==='OT' && (this.application.nationality_oth===null || this.application.nationality_oth==='')) {
                                reject(this.lang.nationality+this.$t('is_required'));
                            }else{
                                resolve();
                            } 
                        })
                    }),
                },
                phone: { required: true },
                email: { required: true, type:'email' },
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
                this.application.name_full_zh = '陳大文',
                this.application.name_given_fn = 'Tai Man',
                this.application.name_family_fn = 'Chan',
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
        onFinish() {
            this.$inertia.post(route('recruitment.admin.save'), { to_page: 2, application: this.application }, {
                onSuccess: (page) => {
                    console.log('save & update success')
                },
                onError: (err) => {
                    console.log(err)
                }
            });
        },
        onFinishFailed(){
            message.error(this.lang.error_required_fields);
        },
        getConfigLabel(config, value){
            let item=config.find(i=>i.value==value)
            return item?item['label_'+this.$page.props.lang]:null;
        }
    },
    computed:{
        validateMessages() {
            return {
                required: '${label}' + this.$t('is_required'),
                types: {
                    email: this.$t('is_not_email'),
                    number: '${label} ' + this.$t('is_no_number'),
                },
                number: {
                    //range: '${label} must be between ${min} and ${max}',
                    range: '${label} ' + this.$t('must_between') + ' ${min} - ${max}',
                },
            }
        },
    }
};

</script>
<style scoped>
label.ant-checkbox-wrapper {
    margin-left: 8px;
}
</style>
