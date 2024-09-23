<template>
    <div class="flex justify-between bg-gray-200">
        <div class="p-5">
            <a href="/">
                <img src="/images/mpu_banner.png" width="300" />
            </a>
        </div>
        <div class="hidden lg:block lg:p-10 lg:text-4xl font-medium text-slate-500">
            {{ lang.enquiry_title }}
        </div>
    </div>
    <div class="py-12">
        <div class="lg:hidden text-2xl text-center font-medium text-slate-500">
            {{ lang.enquiry.title }}
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex space-x-4 float-right">
                    <a href="language/zh">{{ lang.chinese }}</a>
                    <a href="language/en">{{ lang.english }}</a>
                    <a href="language/pt">{{ lang.portuguese }}</a>
                </div>
                <a-form ref="refEnquiry" name="enquiry" 
                    :model="enquiry" 
                    layout="vertical"
                    @finish="onFinish">
                    <a-typography-title :level="3" class="pb-0">{{ lang.enquiry_welcome_title }}</a-typography-title>

                    <div v-html="lang.enquiry_welcome_content" />
                    <p class="font-bold underline mt-10">{{ lang.enquiry_disclaimer_title }}</p>
                    <ol class="ml-8">
                        <li class="-indent-5 text-justify" v-for="item in lang.enquiry_disclaimer_list">
                            &#x27A3;<span class="ml-2">{{ item }}</span>
                        </li>
                    </ol>

                    <a-form-item name="privacy">
                        <a-checkbox v-model:checked="enquiry.privacy">
                            <span v-html="fields.privacy['question_' + lang.lang]"></span>
                        </a-checkbox>
                    </a-form-item>
                    <a-form-item name="origin" :label="fields.origin['question_' + lang.lang]" v-if="enquiry.privacy">
                        <a-radio-group v-model:value="enquiry.origin">
                            <a-radio v-for="option in fields.origin.options" :value="option.value"
                                :style="radioStyle">{{option['label_' + lang.lang] }}</a-radio>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item name="degree" :label="fields.degree['question_' + lang.lang]" v-if="enquiry.origin">
                        <a-radio-group v-model:value="enquiry.degree" @change="delete enquiry.admission">
                            <a-radio v-for="option in fields.degree.options" :value="option.value"
                                :style="radioStyle">{{option['label_' + lang.lang] }}</a-radio>
                        </a-radio-group>
                    </a-form-item>

                    <a-form-item name="admission" v-if="enquiry.degree == 'B'">
                        <div class="ant-col ant-form-item-label">

                            <label for="enquiry_admission" class="ant-form-item-required">
                                {{ fields.admission['question_' + lang.lang] }}
                            </label>
                            <a-popover :title="lang.doc_id_type">
                                <template #content>
                                    <ol>
                                        <li v-for="item in fields.origin.options" class="mb-2">
                                            <a :href="item['url_'+lang.lang]" target="_blank">{{ item['label_' + lang.lang] }}</a>
                                        </li>
                                    </ol>
                                </template>
                                <a-button type="link">{{ lang.read_more }}</a-button>
                            </a-popover>
                        </div>
                        <a-radio-group v-model:value="enquiry.admission" class="w-full">
                            <template v-if="enquiry.origin == 'MO' && enquiry.degree == 'B'">
                                <a-radio :value="fields.admission.options[0].value" :style="radioStyle">
                                    {{ fields.admission.options[0]['label_' + lang.lang] }}
                                </a-radio>
                                <a-radio :value="fields.admission.options[1].value" :style="radioStyle">
                                    {{ fields.admission.options[1]['label_' + lang.lang] }}
                                </a-radio>
                                <a-radio :value="fields.admission.options[2].value" :style="radioStyle">
                                    {{ fields.admission.options[2]['label_' + lang.lang] }}
                                </a-radio>
                                <a-radio :value="fields.admission.options[3].value" :style="radioStyle">
                                    {{ fields.admission.options[3]['label_' + lang.lang] }}
                                </a-radio>
                                <a-radio :value="fields.admission.options[4].value">
                                    {{ fields.admission.options[4]['label_' + lang.lang] }}
                                </a-radio>
                            </template>
                            <template v-else-if="enquiry.origin == 'CN' && enquiry.degree == 'B'">
                                <a-radio :value="fields.admission.options[5].value" :style="radioStyle">
                                    {{ fields.admission.options[5]['label_' + lang.lang] }}
                                </a-radio>
                                <a-radio :value="fields.admission.options[6].value">
                                    {{ fields.admission.options[6]['label_' + lang.lang] }}
                                </a-radio>
                            </template>
                            <template v-else>
                                <a-radio :value="fields.admission.options[7].value" :style="radioStyle">
                                    {{ fields.admission.options[7]['label_' + lang.lang] }}
                                </a-radio>
                                <a-radio :value="fields.admission.options[8].value" :style="radioStyle">
                                    {{ fields.admission.options[8]['label_' + lang.lang] }}
                                </a-radio>
                            </template>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item name="profile" :label="fields.profile['question_' + lang.lang]"
                        v-if="enquiry.admission || (enquiry.degree && enquiry.degree != 'B')">
                        <a-radio-group v-model:value="enquiry.profile" @change="delete enquiry.profile_other">
                            <a-radio v-for="option in fields.profile.options" :value="option.value"
                                :style="radioStyle">{{ option['label_' + lang.lang] }}</a-radio>
                            <a-input v-if="enquiry.profile === 'OTH'" style="width: 300px; margin-left: 10px"
                                v-model:value="enquiry.profile_other" />
                        </a-radio-group>
                    </a-form-item>
                    
                    <a-form-item name="apply" :label="fields.apply['question_' + lang.lang]" v-if="enquiry.profile">
                        <a-radio-group v-model:value="enquiry.apply" @change="enquiry.apply_number = null">
                            <a-radio v-for="option in fields.apply.options" :value="option.value" :style="radioStyle">
                                {{ option['label_' + lang.lang] }}
                            </a-radio>
                            <template v-if="enquiry.apply">
                                <a-form-item name="apply_number" :rules="{required:true, message:fields.apply.other['message_' + lang.lang]}">
                                    <a-input style="width: 100px; margin-left: 10px"
                                        v-model:value="enquiry.apply_number"/>
                                    {{ fields.apply.other['label_'+lang.lang] }}
                                </a-form-item>
                            </template>

                        </a-radio-group>
                    </a-form-item>

                    <template v-if="enquiry.apply != null">
                        <a-row :gutter="24">
                            <a-col :span="12">
                                <a-form-item name="surname" 
                                    :label="fields.surname['question_' + lang.lang]" 
                                    :rules="{required:true, message:fields.surname['question_' + lang.lang] + lang.is_required}">
                                    <a-input v-model:value="enquiry.surname" />
                                </a-form-item>
                            </a-col>
                            <a-col :span="12">
                                <a-form-item name="givenname" 
                                    :label="fields.givenname['question_' + lang.lang]"
                                    :rules="{required:true, message:fields.givenname['question_' + lang.lang] + lang.is_required}">
                                    <a-input v-model:value="enquiry.givenname" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-form-item name="email" 
                            :label="fields.email['question_' + lang.lang]"
                            :rules="{required:true, message:fields.email['question_' + lang.lang] + lang.is_required}">
                            <a-input v-model:value="enquiry.email" />
                        </a-form-item>
                        <label>{{ fields.contact_number['question_' + lang.lang] }}</label>
                        <a-row :gutter="24">
                            <a-col :span="12">
                                <a-form-item name="areacode" 
                                    :label="fields.areacode['question_' + lang.lang]"
                                    :rules="{required:true, message:fields.areacode['question_' + lang.lang] + lang.is_required}">
                                    <a-select v-model:value="enquiry.areacode" :options="phone_country_codes.value"
                                        :fieldNames="{ label: 'label', value: 'countryCode' }" />

                                </a-form-item>
                            </a-col>
                            <a-col :span="12">
                                <a-form-item name="phone" 
                                    :label="fields.phone['question_' + lang.lang]"
                                    :rules="{required:true, message:fields.phone['question_' + lang.lang] + lang.is_required}">
                                    <a-input v-model:value="enquiry.phone" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                    </template>
                    <a-form-item name="subjects" 
                        v-if="enquiry.apply != null"
                        :label="fields.subjects['question_' + lang.lang]"
                        :rules="{required:true, message:fields.subjects['question_' + lang.lang] + lang.is_required}">
                        <a-checkbox-group v-model:value="enquiry.subjects" style="width: 100%">
                            <a-checkbox v-for="option in fields.subjects.options" :value="option.value"
                                :style="radioStyle">{{ option['label_' + lang.lang] }}</a-checkbox>
                        </a-checkbox-group>
                    </a-form-item>
                    <a-form-item name="agree" v-if="enquiry.subjects">
                        <a-checkbox v-model:checked="enquiry.agree">
                            <span v-html="fields.agree['question_' + lang.lang]"></span>
                        </a-checkbox>
                    </a-form-item>
                    <br>
                    <a-form-item v-if="enquiry.agree && enquiry.privacy">
                        <a-button type="primary" html-type="submit">{{ lang.submit }}</a-button>
                    </a-form-item>
                </a-form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import enquiryLang  from '/lang/enquiry.json';

export default {
    components: {
    },
    props: ['fields', 'faqs', 'phone_country_codes'],
    data() {
        return {
            //lang: this.$page.props.lang,
            //fields:[],
            lang:{},
            admissionLinks: [],
            enquiry: {
                lang: 'zh',
                origin: 'MO',
                degree: 'B',
                admission: 'EXAM',
                profile: 'STD',
                apply: true,
                apply_number: null,
                surname: 'Jose',
                givenname: 'Chan',
                email: 'josechan@ipm.edu.mo',
                areacode: '853',
                phone: '63860836',
                subjects: ['ADM'],
                agree: null,
                privacy: null,
            },
            radioStyle: {
                display: 'flex',
                minHeight: '25px',
                lineHeight: '20px',
                marginLeft: '0'
            },
            rules: {
                origin: [{
                    required: true,
                    message: '必填欄位 Required field.'
                }],
                degree: [{
                    required: true,
                    message: '必填欄位 Required field.'
                }],
                admission: { required: true, },
                profile: { required: true, },
                apply: { required: true, },
                apply_number: [{
                    required: true,
                    pattern: "^[0-9]{6}",
                }],
                surname: { required: true, },
                givenname: { required: true, },
                email: [{
                    required: true,
                    type: 'email',
                }],
                areacode: [{
                    required: true,
                    pattern: /^[0-9]*$/,
                }],
                phone: [{
                    required: true,
                    pattern: /^[0-9]*$/,
                }],
                subjects: { required: true, },
                agree: [{
                    required: true,
                    validator: ((_rule, value) => {
                        if (value) return Promise.resolve();
                        return Promise.reject();
                    }),
                    //message: '您需要同意該條款You need to agree to the term.'
                }],
                privacy: [{
                    required: true,
                    validator: ((_rule, value) => {
                        if (value) return Promise.resolve();
                        return Promise.reject();
                    }),
                    //message: '您需要同意該條款You need to agree to the term.'
                }]
            },
            validateMessages: {
                required: '${label} ',
                types: {
                    email: '${label} ',
                    number: '${label} ',
                },
                number: {
                    //range: '${label} must be between ${min} and ${max}',
                    range: '${label} ' + ' ${min} - ${max}',
                },
            },

        }
    },
    created() {
        this.lang=enquiryLang[document.documentElement.lang];
        //loadLanguageAsync(this.$page.props.lang)
        this.phone_country_codes.value.forEach(v => v.label = v.label_zh + '/' + v.label_en)
        this.admissionLinks = this.fields.origin.options
        this.enquiry = {};
    },
    mounted() {
        //this.$inertia.get(route('language','zh'))
    },
    methods: {
        onFinish() {
            this.$inertia.post(route('enquiry.store'), this.enquiry, {
                onSuccess: (page) => {
                    this.enquiry = {}
                    console.log(page);
                },
                onError: (err) => {
                    console.log(err);
                }
            });
        },
        onChangeDegree() {
            delete this.enquiry.admission
        },
    },
}
</script>