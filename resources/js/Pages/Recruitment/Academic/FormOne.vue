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
                                <p>{{ lang.unit }}: {{ vacancy.apply_in }}</p>
                                <p>{{ lang.code }}:{{ vacancy.code }}</p>
                                <p>{{ lang.title }}: {{ vacancy['title_' + $page.props.lang] }}</p>
                            </a-col>
                            <a-col>
                                <p>{{ lang.obtain_info }}</p>
                                <a-checkbox-group v-model:value="application.obtain_info"
                                    style="display: flex; flex-direction: column;margin-left:8">
                                    <a-checkbox value="WEB">{{ lang.obtain_info_web }}</a-checkbox>
                                    <a-row>
                                        <a-col>
                                            <a-checkbox value="NEW">{{ lang.obtain_info_new }}</a-checkbox>
                                        </a-col>
                                        <a-col>
                                            <a-input v-model:value="application.obtain_info_new" />
                                        </a-col>
                                    </a-row>
                                    <a-row>
                                        <a-col>
                                            <a-checkbox value="OTH">{{ lang.obtain_info_oth }}</a-checkbox>
                                        </a-col>
                                        <a-col>
                                            <a-input v-model:value="application.obtain_info_oth" />
                                        </a-col>
                                    </a-row>

                                </a-checkbox-group>
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
                        <a-form-item :label="lang.name_given_fn" name="name_given_fn">
                            <a-input v-model:value="application.name_given_fn" />
                        </a-form-item>
                        <a-form-item :label="lang.name_family_fn" name="name_family_fn">
                            <a-input v-model:value="application.name_family_fn" />
                        </a-form-item>
                        <a-row :gutter="12">
                            <a-col :span="16">
                                <a-form-item :label="lang.pob" name="pob">
                                    <a-radio-group v-model:value="application.pob">
                                        <template v-for="(item, key) in lang.pob_options">
                                            <a-radio :value="key">{{ item }}</a-radio>
                                        </template>
                                    </a-radio-group>
                                    <a-input v-if="application.pob=='OT'" v-model:value="application.pob_oth" style="width:200px"/>
                                </a-form-item>
                                
                            </a-col>
                            <a-col :span="8">
                                <a-form-item :label="lang.dob" name="dob">
                                    <a-input v-model:value="application.dob" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="12">
                            <a-col :span="12">
                                <a-form-item :label="lang.id_type" name="id_type">
                                    <a-select v-model:value="application.id_type">
                                        <template v-for="(item, key) in lang.id_type_options">
                                            <a-select-option :value="key">{{ item }}</a-select-option>
                                        </template>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col :span="12" v-if="application.id_type=='OTH'">
                                <a-form-item :label="lang.id_type_name" name="id_type_name">
                                    <a-input v-model:value="application.id_type_name" />
                                </a-form-item>
                            </a-col>
                        </a-row>
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
                                <a-form-item :label="lang.nationality" name="nationality">
                                    <a-radio-group v-model:value="application.nationality">
                                        <template v-for="(item, key) in lang.nationality_options">
                                            <a-radio :value="key">{{ item }}</a-radio>
                                        </template>
                                        <a-input v-if="application.nationality=='OT'" v-model:value="application.nationality_oth" style="width:200px"/>
                                    </a-radio-group>
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-form-item :label="lang.address" name="address">
                            <a-input v-model:value="application.address" />
                        </a-form-item>
                        <a-row :gutter="12">
                            <a-col :span="12">
                                <a-form-item :label="lang.phone" name="phone">
                                    <a-input v-model:value="application.phone" />
                                </a-form-item>
                            </a-col>
                            <a-col :span="12">
                                <a-form-item :label="lang.email" name="email">
                                    <a-input v-model:value="application.email" />
                                </a-form-item>
                            </a-col>
                        </a-row>
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
import recLang  from '/lang/recruitment_academic.json';
import { message,Modal} from 'ant-design-vue';
import { computed } from 'vue';

export default {
    components: {
        MemberLayout,
        CaretRightOutlined,
        CardBox,
    },
    props: ['vacancy', 'application'],
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
        }else{
            this.page.currrent=1
            this.page.previours=1
            this.page.next=2
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
        handlePobValidate(rule, value, callback){
            console.log(rule)
            console.log(value)
        },
        onFinish() {
            console.log(this.page);
            this.$inertia.post(route('recruitment.academic.save'), { to_page: this.page.next, application: this.application }, {
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
