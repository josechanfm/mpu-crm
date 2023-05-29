<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <a-radio-group v-model:value="inquiry.lang">
                    <a-radio-button value="zh">Chinese</a-radio-button>
                    <a-radio-button value="pt">Portuguese</a-radio-button>
                    <a-radio-button value="en">English</a-radio-button>
                </a-radio-group>
                <a-form 
                    ref="refInquiry" 
                    name="inquiry" 
                    :model="inquiry" 
                    layout="vertical" 
                    @finish="onFinish"
                >
                    <a-form-item name="origin" :label="inquiryLabels[lang].origin.question">
                        <a-radio-group v-model:value="inquiry.origin" >
                            <a-radio v-for="option in inquiryLabels.zh.origin.options" :value="option.value" :style="radioStyle">{{ option.label }}</a-radio>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item name="degree" :label="inquiryLabels[lang].degree.question" v-if="inquiry.origin">
                        <a-radio-group v-model:value="inquiry.degree" >
                            <a-radio v-for="option in inquiryLabels.zh.degree.options" :value="option.value" :style="radioStyle">{{ option.label }}</a-radio>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item name="admission" :label="inquiryLabels[lang].admission.question" v-if="inquiry.degree=='B'">
                        <a-radio-group v-model:value="inquiry.admission" >
                            <template v-if="inquiry.origin=='MO'">
                                <a-radio :value="inquiryLabels[lang].admission.options[0].value" :style="radioStyle">{{inquiryLabels[lang].admission.options[0].label}}</a-radio>
                                <a-radio :value="inquiryLabels[lang].admission.options[1].value" :style="radioStyle">{{inquiryLabels[lang].admission.options[1].label}}</a-radio>
                                <a-radio :value="inquiryLabels[lang].admission.options[2].value" :style="radioStyle">{{inquiryLabels[lang].admission.options[2].label}}</a-radio>
                                <a-radio :value="inquiryLabels[lang].admission.options[3].value" :style="radioStyle">{{inquiryLabels[lang].admission.options[3].label}}</a-radio>
                            </template>                                
                            <template v-else="inquiry.origin=='CN'">
                                <a-radio :value="inquiryLabels[lang].admission.options[4].value" :style="radioStyle">{{inquiryLabels[lang].admission.options[4].label}}</a-radio>
                                <a-radio :value="inquiryLabels[lang].admission.options[5].value" :style="radioStyle">{{inquiryLabels[lang].admission.options[5].label}}</a-radio>
                            </template>
                            <template v-else>
                                <a-radio :value="inquiryLabels[lang].admission.options[6].value" :style="radioStyle">{{inquiryLabels[lang].admission.options[6].label}}</a-radio>
                                <a-radio :value="inquiryLabels[lang].admission.options[7].value" :style="radioStyle">{{inquiryLabels[lang].admission.options[7].label}}</a-radio>
                            </template>
                        </a-radio-group>
                        <br>
                    </a-form-item>
                    <a-form-item name="profile" :label="inquiryLabels[lang].profile.question" v-if="inquiry.admission">
                        <a-radio-group v-model:value="inquiry.profile" >
                            <a-radio v-for="option in inquiryLabels.zh.profile.options" :value="option.value" :style="radioStyle">{{ option.label }}</a-radio>
                            <a-input v-if="inquiry.profile=== 'OTH'" style="width: 300px; margin-left: 10px" />
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item name="apply" :label="inquiryLabels[lang].apply.question" v-if="inquiry.profile">
                        <a-radio-group v-model:value="inquiry.apply" >
                            <a-radio v-for="option in inquiryLabels.zh.apply.options" :value="option.value" :style="radioStyle">{{ option.label }}</a-radio>
                            <a-input v-if="inquiry.apply=== 'YES'" style="width: 100px; margin-left: 10px" />
                        </a-radio-group>
                    </a-form-item>
                    <template v-if="inquiry.apply">
                        <a-row :gutter="24">
                            <a-col :span="12">
                                <a-form-item name="surname" :label="inquiryLabels[lang].surname.question">
                                    <a-input v-model:value="inquiry.surname" />
                                </a-form-item>
                            </a-col>
                            <a-col :span="12">
                                <a-form-item name="givenname" :label="inquiryLabels[lang].givenname.question">
                                            <a-input v-model:value="inquiry.givenname" />
                                        </a-form-item>
                            </a-col>
                        </a-row>     
                        <a-form-item name="email" :label="inquiryLabels[lang].email.question">
                            <a-input v-model:value="inquiry.email" :rules="[{ type: 'email' }]"/>
                        </a-form-item>
                        <label>{{inquiryLabels[lang].contact_number.question}}</label>
                        <a-row :gutter="24">
                            <a-col :span="12">
                                <a-form-item name="areacode" :label="inquiryLabels[lang].areacode.question">
                                    <a-input v-model:value="inquiry.areacode" />
                                </a-form-item>
                            </a-col>
                            <a-col :span="12">
                                <a-form-item  name="phone" :label="inquiryLabels[lang].phone.question">
                                    <a-input v-model:value="inquiry.phone" />
                                </a-form-item>
                            </a-col>
                        </a-row>     
                    </template>

                    <a-form-item name="subjects" :label="inquiryLabels[lang].subjects.question" v-if="inquiry.apply">
                        <a-checkbox-group v-model:value="inquiry.subjects" style="width: 100%">
                            <a-checkbox 
                                v-for="option in inquiryLabels[lang].subjects.options"
                                :value="option.value"
                                :style="radioStyle"
                            >{{ option.label }}</a-checkbox>
                        </a-checkbox-group>
                    </a-form-item>
                    <a-checkbox v-model:checked="inquiry.agree" v-if="inquiry.subjects">{{ inquiryLabels[lang].agree.question }}</a-checkbox>

                    <a-form-item>
                      <a-button type="primary" html-type="submit">Submit</a-button>
                    </a-form-item>
                    <a-button @click="getQuestion">GetQuestion</a-button>
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
    props: ['faqs'],
    data() {
        return {
            lang:this.$page.props.lang, 
            inquiry:{
                lang:'zh',
                origin:null,
                degree:null,
                admission:null,
                profile:null,
                apply:null,
                surname:null,
                givenname:null,
                email:null,
                ereacode:null,
                phone:null,
                subjects:null,
                agree:false,
            },
            inquiryLabels:{
                zh:{
                    origin:{
                        question:'擬入讀本校之學生現時所持有之證件 Type of personal Identification document held by the candidate',
                        options:[
                            {'value':'MO','label':'澳門居民身份證 MACAU ID'},
                            {'value':'CN','label':'中華人民共和國居民身份證 CHINA ID'},
                            {'value':'HK','label':'香港居民身份證 HONG KONG ID'},
                            {'value':'TW','label':'台灣居民身份證 TAIWAN ID'},
                            {'value':'FR','label':'外國護照 (非持有上述證件的外國居民) PASSPORT'}
                        ]
                    },
                    degree:{
                        question:'入讀課程類別 Apply programme',
                        options:[
                            {'value':'B','label':'學士學位 (本科) Bachelor'},
                            {'value':'M','label':'碩士學位 Master'},
                            {'value':'D','label':'博士學位 Doctoral'},
                        ]
                    },
                    admission:{
                        question:'入學途徑 Admission route',
                        options:[
                            {'value':'EXAM','label':'入學考試 Admission exams'},
                            {'value':'RECOMMEND','label':'澳門中學校長推薦新生入學計劃 Principals’ recommendation'},
                            {'value':'TELENT','label':'專才入學計劃 Local talents and professionals'},
                            {'value':'DIRECT','label':'直接入學 Direct admission'},
                            {'value':'OTHER','label':'其他 Others (現就讀於高等院校學士課程之學生適用 Applicable to students who are currently studying bachelor programs in higher education institutions)'},
                            {'value':'GAOKAO','label':'高考生 Mainland China GAOKAO students'},
                            {'value':'DIRECT','label':'直接入學 Direct admission (非高考生持國際課程或公開考試成績的內地籍高中應屆畢業生 Non-GAOKAO student and Fresh graduates from high schools who hold international curriculum or public exam result)'},
                            {'value':'EXAM','label':'入學考試 Admission exams'},
                            {'value':'DIRECT','label':'直接入學 Direct admission'}
                        ]
                    },
                    profile:{
                        question:'簡介 Profiles',
                        options:[
                            {'value':'STD','label':'學生 Student'},
                            {'value':'PAR','label':'家長 Parent'},
                            {'value':'TEA','label':'老師 Teacher'},
                            {'value':'EDU','label':'教育機構/顧問 Education centre/counsellor'},
                            {'value':'OTH','label':'其他 Other'}
                        ]
                    },
                    apply:{
                        question:'是否已報讀澳理大學位課程? Have you submitted an application for admission in current year?',
                        options:[
                            {'value':'NO','label':'否   No'},
                            {'value':'YES','label':'是 Yes'},
                        ]
                    },
                    surname:{
                        question:'姓 Surname',
                    },
                    givenname:{
                        question:'名 Given Name',
                    },
                    email:{
                        question:'電郵 Email',
                    },
                    contact_number:{
                        question: '聯絡電話 phone number',
                    },
                    areacode:{
                        question:'區號 Area code',
                    },
                    phone:{
                        question:'電話 Phone number',
                    },
                    subjects:{
                        question:'主旨 Subject',
                        options:[
                            {'value':'ADM','label':'入學要求 Admission Requirements'},
                            {'value':'PRO','label':'課程資訊 Programme Information'},
                            {'value':'APP','label':'報名資訊 Application Procedures'},
                            {'value':'FEE','label':'學費及奬學金 Fees and Scholarships'},
                            {'value':'RES','label':'錄取資訊 Admission Result'},
                            {'value':'UPD','label':'更新報名資訊 Updating Information on Application Form'},
                            {'value':'PAY','label':'繳費事宜 Payment Issue'},
                            {'value':'REG','label':'入學登記手續、體檢 Student registration and physical examination'},
                            {'value':'OTH','label':'其他 others'},
                        ]
                    },
                    agree:{
                        question:'本人同意澳門理工大學就招生資訊或活動發送電郵或短訊予本人。<br>I give my consent to Macau Polytechnic University for send me emails or SMS regarding admissions information or activities.'
                    }
                },
                en:{
                    origin:{
                        question:'en擬入讀本校之學生現時所持有之證件 Type of personal Identification document held by the candidate',
                        options:[
                            {'value':'MO','label':'澳門居民身份證 MACAU ID'},
                            {'value':'CN','label':'中華人民共和國居民身份證 CHINA ID'},
                            {'value':'HK','label':'香港居民身份證 HONG KONG ID'},
                            {'value':'TW','label':'台灣居民身份證 TAIWAN ID'},
                            {'value':'FR','label':'外國護照 (非持有上述證件的外國居民) PASSPORT'}
                        ]
                    },
                    degree:null,
                    admission:null,
                    profile:null,
                    apply:null,
                    surname:null,
                    givenname:null,
                    email:null,
                    ereacode:null,
                    phone:null,
                    subjects:null
                },
                pt:{
                    origin:{
                        question:'pt擬入讀本校之學生現時所持有之證件 Type of personal Identification document held by the candidate',
                        options:[
                            {'value':'MO','label':'澳門居民身份證 MACAU ID'},
                            {'value':'CN','label':'中華人民共和國居民身份證 CHINA ID'},
                            {'value':'HK','label':'香港居民身份證 HONG KONG ID'},
                            {'value':'TW','label':'台灣居民身份證 TAIWAN ID'},
                            {'value':'FR','label':'外國護照 (非持有上述證件的外國居民) PASSPORT'}
                        ]
                    },
                    degree:null,
                    admission:null,
                    profile:null,
                    apply:null,
                    surname:null,
                    givenname:null,
                    email:null,
                    ereacode:null,
                    phone:null,
                    subjects:null
                }
            },
            radioStyle:{
                display: 'flex',
                height: '30px',
                lineHeight: '30px',
                marginLeft: '0'
            },
            rules:{
                origin:[{
                    required:true,
                    message:'origin is required'
                }],
                degree:[{
                    required:true,
                    message:'origin is required'
                }],
                admission:[{
                    required:true,
                    message:'origin is required'
                }],
                profile:[{
                    required:true,
                    message:'origin is required'
                }],
                apply:[{
                    required:true,
                    message:'origin is required'
                }],
                surname:[{
                    required:true,
                    message:'origin is required'
                }],
                givenname:[{
                    required:true,
                    message:'origin is required'
                }],
                email:[{
                    required:true,
                    message:'origin is required'
                }],
                areacode:[{
                    required:true,
                    message:'origin is required'
                }],
                phone:[{
                    required:true,
                    message:'origin is required'
                }],
                subjects:[{
                    required:true,
                    message:'origin is required'
                }],
                agree:[{
                    required:true,
                }]
            }
        }
    },
    methods: {
        getQuestion(){
            if(this.inquiry.agree==false){
                alert('Agreed to the question');
                return false;
            }
            console.log(this.inquiry);
            this.$inertia.post(route('inquiry.store'),this.inquiry,{
                onSuccess:(page)=>{
                    console.log(page);
                },
                onError:(err)=>{
                    console.log(err);
                }
            });
        },
        onFinish(){
            console.log("Finish and submit");
            console.log(this.inquiry);
            axios.post(route('inquiry.store'),this.inquiry)
                .then(response=>{
                    console.log(response.data);
                    window.location.href= './question/'+response.data.id+"/"+response.data.token;
                });

        }
    },
}
</script>