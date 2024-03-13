<template>
    <div class="flex justify-between bg-gray-200">
        <div class="p-5">
            <a href="/">
            <img src="/images/mpu_banner.png" width="300" />
        </a>
        </div>
        <div class="hidden lg:block lg:p-5 lg:text-4xl font-medium text-slate-500">
            學位課程入學諮詢 <br>Degree Program Admission Enquiries
        </div>
    </div>
    <div class="py-12">
        <div class="lg:hidden text-2xl text-center font-medium text-slate-500">
            學位課程入學諮詢 <br>Degree Program Admission Enquiries
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <a-form ref="refEnquiry" name="enquiry" :model="enquiry" :rules="rules" layout="vertical"
                    @finish="onFinish">
                    <a-typography-title :level="3" class="pb-0">與 “理” 聯繫 Connect with MPU</a-typography-title>
                    <p>
                        歡迎與澳理大聯繫，諮詢學位課程入學事宜，請填寫以下資料，以便澳理大招生專員為你提供適切的入學相關資訊。<br>
                        Welcome to “Connect with MPU” for Degree Programmes Admission Enquiries. Kindly fill in the information below for further details.
                    </p>
                    <p>
                        感謝對澳門理工大學的支持。<br>
                        Thank you once again for your interest in MPU.
                    </p>
                    <p class="font-bold underline mb-0">資料收集聲明 | Data Collection Statement:</p>
                    <ol class="ml-8">
                        <li class="-indent-5">
                            &#x27A3;<span class="ml-2">澳門理工大學應申請人之要求提供相關學術及行政服務。</span><br>
                            Macao Polytechnic University (hereafter referred to as “the University”) provides relevant academic and administrative services at the request of applicants.
                        </li>
                        <li class="-indent-5">
                            &#x27A3;<span class="ml-2">澳門理工大學所收集的資料僅用作是次服務之用途。有關資料亦可在澳門理工大學內部及其他具法律規定或獲申請人授權的實體之間傳遞，以達至完成相關程序。網絡傳遞過程未能保證訊息絕對保密，且存在一定程度之風險。</span><br>
                            The data collected by the University will be used solely for the stated purposes.  They may be transferred within the University or the entities that are in accordance with legal provision or with your prior consent.  It is necessary to note that internet transmission bears risk and may not guarantee absolute confidentiality.
                        </li>
                        <li class="-indent-5">
                            &#x27A3;<span class="ml-2">為提供所要求的服務，有關申請須提供申請人身份識別及使用是次服務相關的資料，未能提供相關資料的申請將不予受理。</span><br>
                            To enable the provision of the requested services, it is mandatory for the applicants to contain personal-identification and information related to the use of this service.  Applications absent of the stated information will not be processed.
                        </li>
                    </ol>


                    <a-form-item name="privacy">
                        <a-checkbox v-model:checked="enquiry.privacy">
                            <span v-html="fields.privacy.question"></span>
                        </a-checkbox>
                    </a-form-item>


                    <a-form-item name="origin" :label="fields.origin.question" v-if="enquiry.privacy">
                        <a-radio-group v-model:value="enquiry.origin">
                            <a-radio v-for="option in fields.origin.options" :value="option.value" :style="radioStyle">{{
                                option.label }}</a-radio>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item name="degree" :label="fields.degree.question" v-if="enquiry.origin">
                        <a-radio-group v-model:value="enquiry.degree" @change="delete enquiry.admission">
                            <a-radio v-for="option in fields.degree.options" :value="option.value" :style="radioStyle">{{
                                option.label }}</a-radio>
                        </a-radio-group>
                    </a-form-item>

                    <a-form-item name="admission" v-if="enquiry.degree=='B'">
                        <div class="ant-col ant-form-item-label">
                            <label for="enquiry_admission" class="ant-form-item-required" title="入學途徑 Admission route">
                                {{fields.admission.question}}
                            </label>
                            <a-popover title="證件類別 ID Type">
                            <template #content>
                                <p><a href="https://www.mpu.edu.mo/admission_local/zh/index.php" target="_blank">澳門居民身份證
                                        MACAO ID</a></p>
                                <p><a href="https://www.mpu.edu.mo/admission_mainland/zh/index.php"
                                        target="_blank">中華人民共和國居民身份證 CHINA ID</a></p>
                                <p><a href="https://www.mpu.edu.mo/admission_overseas/zh/index.php" target="_blank">香港居民身份證
                                        HONG KONG ID</a></p>
                                <p><a href="https://www.mpu.edu.mo/admission_overseas/zh/index.php" target="_blank">台灣居民身份證
                                        TAIWAN ID</a></p>
                                <p><a href="https://www.mpu.edu.mo/admission_overseas/zh/index.php" target="_blank">外國護照
                                        PASSPORT</a></p>
                            </template>
                            <a-button type="link">(更多資訊 Read More)</a-button>
                        </a-popover>
                        </div>
                        <a-radio-group v-model:value="enquiry.admission" class="w-full">
                            <template v-if="enquiry.origin == 'MO' && enquiry.degree=='B'">
                                <a-radio :value="fields.admission.options[0].value" :style="radioStyle">
                                    {{ fields.admission.options[0].label }} {{ fields.admission.options[0].remark }}
                                </a-radio>
                                <a-radio :value="fields.admission.options[1].value" :style="radioStyle">
                                    {{ fields.admission.options[1].label }} {{ fields.admission.options[1].remark }}
                                </a-radio>
                                <a-radio :value="fields.admission.options[2].value" :style="radioStyle">
                                    {{ fields.admission.options[2].label }} {{ fields.admission.options[2].remark }}
                                </a-radio>
                                <a-radio :value="fields.admission.options[3].value" :style="radioStyle">
                                    {{ fields.admission.options[3].label }} {{ fields.admission.options[3].remark }}
                                </a-radio>
                                <a-radio :value="fields.admission.options[4].value">
                                    {{ fields.admission.options[4].label }} {{ fields.admission.options[4].remark }}
                                </a-radio>
                            </template>
                            <template v-else-if="enquiry.origin == 'CN' && enquiry.degree=='B'">
                                <a-radio :value="fields.admission.options[5].value" :style="radioStyle">
                                    {{ fields.admission.options[5].label }} {{ fields.admission.options[5].remark }}
                                </a-radio>
                                <a-radio :value="fields.admission.options[6].value">
                                    {{ fields.admission.options[6].label }} {{ fields.admission.options[6].remark }}
                                </a-radio>
                            </template>
                            <template v-else>
                                <a-radio :value="fields.admission.options[7].value" :style="radioStyle">
                                    {{ fields.admission.options[7].label }} {{ fields.admission.options[7].remark }}
                                </a-radio>
                                <a-radio :value="fields.admission.options[8].value" :style="radioStyle">
                                    {{ fields.admission.options[8].label }} {{ fields.admission.options[8].remark }}
                                </a-radio>
                            </template>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item name="profile" :label="fields.profile.question"
                        v-if="enquiry.admission || (enquiry.degree && enquiry.degree != 'B')">
                        <a-radio-group v-model:value="enquiry.profile" @change="delete enquiry.profile_other">
                            <a-radio v-for="option in fields.profile.options" :value="option.value" :style="radioStyle">{{
                                option.label }}</a-radio>
                            <a-input v-if="enquiry.profile === 'OTH'" style="width: 300px; margin-left: 10px"
                                v-model:value="enquiry.profile_other" />
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item name="apply" :label="fields.apply.question" v-if="enquiry.profile">
                        <a-radio-group v-model:value="enquiry.apply" @change="enquiry.apply_number=null">
                            <a-radio v-for="option in fields.apply.options" :value="option.value" :style="radioStyle">{{
                                option.label }}
                            </a-radio>
                            <template v-if="enquiry.apply">
                                <a-form-item name="apply_number">
                                    <a-input style="width: 100px; margin-left: 10px" v-model:value="enquiry.apply_number" />
                                    {{ fields.apply.other.label }}
                                </a-form-item>
                            </template>
                        </a-radio-group>
                    </a-form-item>

                    <template v-if="enquiry.apply != null">
                        <a-row :gutter="24">
                            <a-col :span="12">
                                <a-form-item name="surname" :label="fields.surname.question">
                                    <a-input v-model:value="enquiry.surname" />
                                </a-form-item>
                            </a-col>
                            <a-col :span="12">
                                <a-form-item name="givenname" :label="fields.givenname.question">
                                    <a-input v-model:value="enquiry.givenname" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-form-item name="email" :label="fields.email.question">
                            <a-input v-model:value="enquiry.email" />
                        </a-form-item>
                        <label>{{ fields.contact_number.question }}</label>
                        <a-row :gutter="24">
                            <a-col :span="12">
                                <a-form-item name="areacode" :label="fields.areacode.question">
                                    <a-select v-model:value="enquiry.areacode" :options="phone_country_codes.value"
                                        :fieldNames="{ label: 'label', value: 'countryCode' }" />

                                </a-form-item>
                            </a-col>
                            <a-col :span="12">
                                <a-form-item name="phone" :label="fields.phone.question">
                                    <a-input v-model:value="enquiry.phone" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                    </template>
                    <a-form-item name="subjects" :label="fields.subjects.question" v-if="enquiry.apply != null">
                        <a-checkbox-group v-model:value="enquiry.subjects" style="width: 100%">
                            <a-checkbox v-for="option in fields.subjects.options" :value="option.value"
                                :style="radioStyle">{{ option.label }}</a-checkbox>
                        </a-checkbox-group>
                    </a-form-item>
                    <a-form-item name="agree" v-if="enquiry.subjects">
                        <a-checkbox v-model:checked="enquiry.agree">
                            <span v-html="fields.agree.question"></span>
                        </a-checkbox>
                    </a-form-item>
                    <br>
                    <a-form-item v-if="enquiry.agree && enquiry.privacy">
                        <a-button type="primary" html-type="submit">提交 Submit</a-button>
                    </a-form-item>
                </a-form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';



export default {
    components: {
    },
    props: ['fields', 'faqs', 'phone_country_codes'],
    data() {
        return {
            lang: this.$page.props.lang,
            enquiry: {
                lang: 'zh',
                origin: 'MO',
                degree: 'B',
                admission: 'EXAM',
                profile: 'STD',
                apply: true,
                applyNumber: null,
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
                admission: [{
                    required: true,
                    message: '必填欄位 Required field.'
                }],
                profile: [{
                    required: true,
                    message: '必填欄位 Required field.'
                }],
                apply: [{
                    required: true,
                    message: '必填欄位 Required field.'
                }],
                apply_number: [{
                    required: true,
                    pattern: "^[0-9]{6}",
                    message: '必須為6位數字 6 numeric digits are required.'
                }],
                surname: [{
                    required: true,
                    message: '必填欄位 Required field.'
                }],
                givenname: [{
                    required: true,
                    message: '必填欄位 Required field.'
                }],
                email: [{
                    required: true,
                    type: 'email',
                    message: '必填有效的電郵地址 Required field and must be a valid email.'
                }],
                areacode: [{
                    required: true,
                    pattern: /^[0-9]*$/,
                    message: '必填數字欄位 Required numeric field.'
                }],
                phone: [{
                    required: true,
                    pattern: /^[0-9]*$/,
                    message: '必填數字欄位 Required muneric field.'
                }],
                subjects: [{
                    required: true,
                    message: '必填欄位 Required field.'
                }],
                agree: [{
                    required: true,
                    validator: ((_rule, value)=>{
                        if(value) return Promise.resolve();
                        return Promise.reject();
                    }),
                    message: '您需要同意該條款You need to agree to the term.'
                }],
                privacy: [{
                    required: true,
                    validator: ((_rule, value)=>{
                        if(value) return Promise.resolve();
                        return Promise.reject();
                    }),
                    message: '您需要同意該條款You need to agree to the term.'
                }]
            }
        }
    },
    created() {
        this.phone_country_codes.value.forEach(v => v.label = v.labelZh + '/' + v.labelEn)
    },
    mounted() {
        this.enquiry = {};
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
        onChangeDegree(){
            delete this.enquiry.admission
        }
    },
}
</script>