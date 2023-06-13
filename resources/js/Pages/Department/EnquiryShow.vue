<template>
    <DepartmentLayout title="Dashboard" :department="enquiry.department">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                客戶服務管理
            </h2>
        </template>
        <a-collapse>
            <a-collapse-panel header="Enquiry Form">
                <ol class="list-disc pl-10">
                    <li>
                        <p class="font-bold">{{fields.origin.question }}</p>
                        <p>{{ optionFind(enquiry.origin,fields.origin.options) }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{fields.degree.question }}</p>
                        <p>{{ optionFind(enquiry.degree,fields.degree.options) }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{fields.admission.question }}</p>
                        <p>{{ optionFind(enquiry.admission,fields.admission.options) }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{fields.profile.question }}</p>
                        <p>{{ optionFind(enquiry.profile,fields.profile.options) }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{fields.apply.question }}</p>
                        <p>{{ optionFind(enquiry.apply,fields.apply.options) }}</p>
                    </li>
                    <li>
                        <p class="font-bold">Name</p>
                        <p>{{ enquiry.givenname }}, {{ enquiry.surname }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{fields.email.question }}</p>
                        <p>{{ enquiry.email }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{fields.phone.question }}</p>
                        <p>{{ enquiry.areacode }} - {{ enquiry.phone }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{fields.subjects.question }}</p>
                        <p v-for="item in optionFilter(enquiry.subjects,fields.subjects.options)">{{ item.label }}</p>
                    </li>
                </ol>
            </a-collapse-panel>
            <a-collapse-panel :header="'Question: '+enquiry.question.substring(0,30)">
                {{ enquiry.question }}
                <ol>
                    <li v-for="file in enquiry.media">
                        <img :src="file.original_url" width="100"/>
                    </li>
                </ol>
            </a-collapse-panel>
            <a-collapse-panel v-for="response in enquiry.responses" :header="'Response at: '+response.created_at" >
                <p>{{ response.title }}</p>
                <p>{{ response.remark }}</p>
                <span v-if="response.by_email">
                    <p>Email: {{ response.email_address }}</p>
                    <p>Subject: {{ response.email_subject }}</p>
                    <p>Content: {{ response.email_content }}</p>
                </span>
                <ol>
                    <li v-for="file in response.media">
                        <img :src="file.original_url" width="100"/>
                    </li>
                </ol>
            </a-collapse-panel>
        </a-collapse>
        <a-divider/>
        <a-divider style="height: 2px; background-color: #7cb305" />
        <a-card title="Response">
            <a-form 
                ref="refResponse" 
                name="response" 
                :model="response" 
                layout="vertical" 
                @finish="onFinish"
            >
                <a-form-item name="title" label="Title" :rules="[{required:true,message:'Summary of your response.'}]">
                    <a-input v-model:value="response.title" />
                </a-form-item>
                <a-form-item name="remark" label="Remark" :rules="[{required:true,message:'required your response remark'}]">
                    <a-textarea v-model:value="response.remark" :rows="10"></a-textarea>
                </a-form-item>
                <p><a-switch v-model:checked="response.by_email" label="aaa"/>&nbsp;&nbsp;Response by email</p>
                <span v-if="response.by_email">
                    <a-form-item name="email_address" label="email" :rules="[{required:true,type:'email',message:'aaaaaa'}]">
                        <a-input v-model:value="response.email_address"/>
                    </a-form-item>
                    <a-form-item name="email_subject" label="Subject" :rules="[{required:true}]">
                        <a-input v-model:value="response.email_subject"/>
                    </a-form-item>
                    <a-form-item name="email_content" label="Content" :rules="[{required:true}]">
                        <a-textarea v-model:value="response.email_content" :rows="10"></a-textarea>
                    </a-form-item>
                </span>


                <a-form-item name="fileList">
                    <a-upload 
                        v-model:file-list="response.fileList"
                        :beforeUpload="()=>false"
                        :max-count="10"
                        list-type="picture"
                    >
                        <a-button>
                            <upload-outlined></upload-outlined>
                            Upload
                        </a-button>
                    </a-upload>
                </a-form-item>

                <a-form-item>
                    <a-button type="primary" html-type="submit">Submit</a-button>
                </a-form-item>

            </a-form>
        </a-card>


    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';
import CommentCard from '@/Components/Department/CommentCard.vue';
import MailerBox from '@/Components/Department/MailerBox.vue';
import EnquiryBox from '@/Components/Department/EnquiryBox.vue';
import { UploadOutlined } from '@ant-design/icons-vue';

export default {
    components: {
        DepartmentLayout,
        CommentCard,
        MailerBox,
        EnquiryBox,
        UploadOutlined,
    },
    props: ['fields', 'enquiry'],
    data() {
        return {
            response:{
                enquiry_id:this.enquiry.id,
                title:null,
                remark:null,
                by_email: false,
                email_address: null,
                email_subject: null,
                email_content: null,
                fileList:[],
            },
            radioStyle: {
                display: 'flex',
                height: '30px',
                lineHeight: '30px',
                marginLeft: '0'
            },
        }
    },
    created() {
    },
    methods: {
        optionFind(value,options){
            return options.find(option => {
                return option['value']==value;
            })['label'];
        },
        optionFilter(values,options){
            var items=options.filter(option => {
                return values.includes(option['value']);
            });
            return items;
        },
        onFinish(){
            this.$inertia.post(route('enquiry.response'),this.response,{
                onSuccess:(page)=>{
                    this.response={};
                    console.log(page);
                },
                onError:(err)=>{
                    console.log(err);
                }
            });
            console.log(this.response);
        }
    },
}
</script>