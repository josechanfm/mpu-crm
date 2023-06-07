<template>
    <DepartmentLayout title="Dashboard" :department="inquiry.department">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                客戶服務管理
            </h2>
        </template>
        <a-collapse>
            <a-collapse-panel header="Inquiry Form">
                <ol class="list-disc pl-10">
                    <li>
                        <p class="font-bold">{{fields.origin.question }}</p>
                        <p>{{ optionFind(inquiry.origin,fields.origin.options) }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{fields.degree.question }}</p>
                        <p>{{ optionFind(inquiry.degree,fields.degree.options) }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{fields.admission.question }}</p>
                        <p>{{ optionFind(inquiry.admission,fields.admission.options) }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{fields.profile.question }}</p>
                        <p>{{ optionFind(inquiry.profile,fields.profile.options) }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{fields.apply.question }}</p>
                        <p>{{ optionFind(inquiry.apply,fields.apply.options) }}</p>
                    </li>
                    <li>
                        <p class="font-bold">Name</p>
                        <p>{{ inquiry.givenname }}, {{ inquiry.surname }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{fields.email.question }}</p>
                        <p>{{ inquiry.email }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{fields.phone.question }}</p>
                        <p>{{ inquiry.areacode }} - {{ inquiry.phone }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{fields.subjects.question }}</p>
                        <p v-for="item in optionFilter(inquiry.subjects,fields.subjects.options)">{{ item.label }}</p>
                    </li>
                </ol>
            </a-collapse-panel>
            <a-collapse-panel :header="'Question: '+inquiry.question.substring(0,30)">
                {{ inquiry.question }}
                <ol>
                    <li v-for="file in inquiry.media">
                        <img :src="file.original_url" width="100"/>
                    </li>
                </ol>
            </a-collapse-panel>
            <a-collapse-panel v-for="response in inquiry.responses" :header="'Response at: '+response.created_at" >
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
import InquiryBox from '@/Components/Department/InquiryBox.vue';
import { UploadOutlined } from '@ant-design/icons-vue';

export default {
    components: {
        DepartmentLayout,
        CommentCard,
        MailerBox,
        InquiryBox,
        UploadOutlined,
    },
    props: ['fields', 'inquiry'],
    data() {
        return {
            response:{
                inquiry_id:this.inquiry.id,
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
            this.$inertia.post(route('department.inquiry.response'),this.response,{
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