<template>
    <div class="p-5 bg-gray-200">
        <a href="/">
            <img src="/images/mpu_banner.png" width="300"/>
        </a>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <a-form 
                    ref="refEnquiry" 
                    name="enquiry" 
                    :model="enquiry" 
                    :rules="rules"
                    layout="vertical" 
                    @finish="onFinish"
                >
                    <a-form-item name="origin" :label="fields.origin.question">
                        <a-radio-group v-model:value="enquiry.origin" >
                            <a-radio v-for="option in fields.origin.options" :value="option.value" :style="radioStyle">{{ option.label }}</a-radio>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item name="degree" :label="fields.degree.question" v-if="enquiry.origin">
                        <a-radio-group v-model:value="enquiry.degree" >
                            <a-radio v-for="option in fields.degree.options" :value="option.value" :style="radioStyle">{{ option.label }}</a-radio>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item name="admission" :label="fields.admission.question" v-if="enquiry.degree=='B'">
                        <a-radio-group v-model:value="enquiry.admission" >
                            <template v-if="enquiry.origin=='MO'">
                                <a-radio :value="fields.admission.options[0].value" :style="radioStyle">{{fields.admission.options[0].label}}</a-radio>
                                <a-radio :value="fields.admission.options[1].value" :style="radioStyle">{{fields.admission.options[1].label}}</a-radio>
                                <a-radio :value="fields.admission.options[2].value" :style="radioStyle">{{fields.admission.options[2].label}}</a-radio>
                                <a-radio :value="fields.admission.options[3].value" :style="radioStyle">{{fields.admission.options[3].label}}</a-radio>
                                <a-radio :value="fields.admission.options[4].value" >{{fields.admission.options[4].label}}</a-radio>
                            </template>                                
                            <template v-else-if="enquiry.origin=='CN'">
                                <a-radio :value="fields.admission.options[5].value" :style="radioStyle">{{fields.admission.options[5].label}}</a-radio>
                                <a-radio :value="fields.admission.options[6].value" >{{fields.admission.options[6].label}}</a-radio>
                            </template>
                            <template v-else>
                                <a-radio :value="fields.admission.options[7].value" :style="radioStyle">{{fields.admission.options[7].label}}</a-radio>
                                <a-radio :value="fields.admission.options[8].value" :style="radioStyle">{{fields.admission.options[8].label}}</a-radio>
                            </template>
                        </a-radio-group>
                        <br>
                    </a-form-item>
                    <a-form-item name="profile" :label="fields.profile.question" v-if="enquiry.admission || (enquiry.degree && enquiry.degree!='B')">
                        <a-radio-group v-model:value="enquiry.profile" >
                            <a-radio v-for="option in fields.profile.options" :value="option.value" :style="radioStyle">{{ option.label }}</a-radio>
                            <a-input v-if="enquiry.profile=== 'OTH'" style="width: 300px; margin-left: 10px" v-model="enquiry.profileOther"/>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item name="apply" :label="fields.apply.question" v-if="enquiry.profile">
                        <a-radio-group v-model:value="enquiry.apply" >
                            <a-radio v-for="option in fields.apply.options" :value="option.value" :style="radioStyle">{{ option.label }}</a-radio>
                            <a-input v-if="enquiry.apply" style="width: 100px; margin-left: 10px" v-model="enquiry.applyNumber"/>
                        </a-radio-group>
                    </a-form-item>
                    <template v-if="enquiry.apply!=null">
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
                            <a-input v-model:value="enquiry.email" :rules="[{ type: 'email' }]"/>
                        </a-form-item>
                        <label>{{fields.contact_number.question}}</label>
                        <a-row :gutter="24">
                            <a-col :span="12">
                                <a-form-item name="areacode" :label="fields.areacode.question">
                                    <a-input v-model:value="enquiry.areacode" />
                                </a-form-item>
                            </a-col>
                            <a-col :span="12">
                                <a-form-item  name="phone" :label="fields.phone.question">
                                    <a-input v-model:value="enquiry.phone" />
                                </a-form-item>
                            </a-col>
                        </a-row>     
                    </template>
                    <a-form-item name="subjects" :label="fields.subjects.question" v-if="enquiry.apply!=null">
                        <a-checkbox-group v-model:value="enquiry.subjects" style="width: 100%">
                            <a-checkbox 
                                v-for="option in fields.subjects.options"
                                :value="option.value"
                                :style="radioStyle"
                            >{{ option.label }}</a-checkbox>
                        </a-checkbox-group>
                    </a-form-item>
                    <a-form-item name="agree" v-if="enquiry.subjects && enquiry.subjects.length>0">
                        <a-checkbox v-model:checked="enquiry.agree"><span v-html="fields.agree.question"></span></a-checkbox>
                    </a-form-item>
                    <a-form-item>
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
    props: ['fields','faqs'],
    data() {
        return {
            lang:this.$page.props.lang, 
            enquiry:{
                // lang:'zh',
                // origin:null,
                // degree:null,
                // admission:null,
                // profile:null,
                // apply:null,
                // applyNumber:null,
                // surname:null,
                // givenname:null,
                // email:null,
                // areacode:null,
                // phone:null,
                // subjects:null,
                // agree:null,
                lang: 'zh',
                origin: 'MO',
                degree: 'B',
                admission: 'EXAM',
                profile: 'STD',
                apply:true,
                applyNumber:null,
                surname: 'Jose',
                givenname: 'Chan',
                email: 'josechan@ipm.edu.mo',
                areacode: '853',
                phone: '63860836',
                subjects: ['ADM'],
                agree:null,
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
                    message:'必填欄位 Required field.'
                }],
                degree:[{
                    required:true,
                    message:'必填欄位 Required field.'
                }],
                admission:[{
                    required:true,
                    message:'必填欄位 Required field.'
                }],
                profile:[{
                    required:true,
                    message:'必填欄位 Required field.'
                }],
                apply:[{
                    required:true,
                    message:'必填欄位 Required field.'
                }],
                surname:[{
                    required:true,
                    message:'必填欄位 Required field.'
                }],
                givenname:[{
                    required:true,
                    message:'必填欄位 Required field.'
                }],
                email:[{
                    required:true,
                    type: 'email',
                    message:'必填有效的電郵地址 Required field and must be a valid email.'
                }],
                areacode:[{
                    required:true,
                    pattern: /^[0-9]*$/,
                    message:'必填數字欄位 Required numeric field.'
                }],
                phone:[{
                    required:true,
                    pattern: /^[0-9]*$/,
                    message:'必填數字欄位 Required muneric field.'
                }],
                subjects:[{
                    required:true,
                    message:'必填欄位 Required field.'
                }],
                agree:[{
                    required:true,
                    message: '您需要同意該條款You need to agree to the term.'
                }]
            }
        }
    },
    mounted(){
        this.enquiry={};
    },
    methods: {
        onFinish(){
            this.$inertia.post(route('enquiry.store'),this.enquiry,{
                onSuccess:(page)=>{
                    this.enquiry={}
                    console.log(page);
                },
                onError:(err)=>{
                    console.log(err);
                }
            });
        }
    },
}
</script>