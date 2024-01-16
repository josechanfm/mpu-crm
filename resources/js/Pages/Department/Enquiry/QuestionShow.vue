<template>
    <DepartmentLayout title="Dashboard" :department="department">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                客戶服務管理
            </h2>
        </template>
        
        <!-- Enquirer basic info-->
        <a-card title="Contact Info">
            <template #extra>
                <!-- <a-button type="primary">Edit</a-button> -->
            </template>
            <a-descriptions :column="2">
                <a-descriptions-item label="Surname">{{ enquiry.surname }}</a-descriptions-item>
                <a-descriptions-item label="Givenname">{{ enquiry.givenname }}</a-descriptions-item>
                <a-descriptions-item label="Phone">{{ enquiry.areacode }} - {{ enquiry.phone }}</a-descriptions-item>
                <a-descriptions-item label="Email">{{ enquiry.email }}</a-descriptions-item>
            </a-descriptions>
        </a-card>
        <!--// Enquirer basic info-->
        <!-- Question and Response list -->
        <a-collapse v-model:activeKey="activeKey">
            <!-- Enquiry From-->
            <a-collapse-panel header="Enquiry Form" style="background-color: #FADBD8;" key="question">
                <ol class="list-disc pl-10">
                    <li>
                        <p class="font-bold">{{ fields.origin.question }}</p>
                        <p>{{ optionFind(enquiry.origin, fields.origin.options) }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{ fields.degree.question }}</p>
                        <p>{{ optionFind(enquiry.degree, fields.degree.options) }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{ fields.admission.question }}</p>
                        <p>{{ optionFind(enquiry.admission, fields.admission.options) }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{ fields.profile.question }}</p>
                        <p>{{ optionFind(enquiry.profile, fields.profile.options) }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{ fields.apply.question }}</p>
                        <p>{{ optionFind(enquiry.apply, fields.apply.options) }}</p>
                    </li>
                    <li>
                        <p class="font-bold">Name</p>
                        <p>{{ enquiry.givenname }}, {{ enquiry.surname }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{ fields.email.question }}</p>
                        <p>{{ enquiry.email }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{ fields.phone.question }}</p>
                        <p>{{ enquiry.areacode }} - {{ enquiry.phone }}</p>
                    </li>
                    <li>
                        <p class="font-bold">{{ fields.subjects.question }}</p>
                        <p v-for="item in optionFilter(enquiry.subjects, fields.subjects.options)">{{ item.label }}</p>
                    </li>
                </ol>
            </a-collapse-panel>
            <!--// Enquiry From-->
            <!-- Enquiry question-->
            <template v-for="question in enquiry.questions" :key="question.id">
                <a-collapse-panel :header="questionNubmer(question)">
                    <template #extra>
                        <span v-if="question.admin_user">{{ question.admin_user.name }}</span>
                        <a-button type="primary" @click="toResponse(question)">回應</a-button>
                        {{ dateFormat(question.created_at) }}
                    </template>
                    {{ question.content }}
                    <a-divider/>
                    <ol>
                        <li v-for="file in question.media">
                            <a :href="file.original_url" target="_blank">
                                <template v-if="file.mime_type.includes('image/')">
                                    <img :src="file.original_url" width="100" />
                                </template>
                                <template v-else>
                                    {{ file.file_name }}
                                </template>
                            </a>
                        </li>
                    </ol>

                    <!-- Enquiry question response-->
                    <template v-for="response in question.responses">
                        <a-collapse>
                            <a-collapse-panel :header="responseNumber(response)" :style="responseStyle">
                                <template #extra>{{response.admin_user?response.admin_user.name:'---'}}@{{ dateFormat(response.created_at) }}</template>
                                <a-typography-title :level="4">{{ response.title }}</a-typography-title>
                                <p>{{ response.remark }}</p>
                                <span v-if="response.by_email">
                                    <p>Email: {{ response.email_address }}</p>
                                    <p>Subject: {{ response.email_subject }}</p>
                                    <p>Content: {{ response.email_content }}</p>
                                </span>
                                <ol>
                                    <li v-for="file in response.media">
                                        <a :href="file.original_url" target="_blank">
                                            <template v-if="file.mime_type.includes('image/')">
                                                <img :src="file.original_url" width="100" />
                                            </template>
                                            <template v-else>
                                                {{ file.file_name }}
                                            </template>
                                        </a>
                                    </li>
                                </ol>
                            </a-collapse-panel>
                        </a-collapse>

                        <hr>
                    </template>
                    <!--// Enquiry question response-->
                </a-collapse-panel>
            </template>
            <!--// Enquiry question-->
        </a-collapse>
        <!--// Question and Response list -->

        <!-- Response box -->
        <template v-if="myResponse.enquiry_question_id">
            <a-divider style="height: 2px; background-color: #7cb305" />
            <a-card :title="'Response' + questionNubmer(myResponse.question)">
                <template #extra><a-button type="link" @click="closeResponse">關閉回應</a-button></template>
                <a-form ref="refResponseForm" name="response" :model="myResponse" layout="vertical" @finish="onFinish">
                    <p><a-switch v-model:checked="myResponse.is_closed" />&nbsp;&nbsp;Closed</p>
                    <a-form-item name="title" label="Title"
                        :rules="[{ required: true, message: 'Summarize your response.' }]">
                        <a-input v-model:value="myResponse.title" />
                    </a-form-item>
                    <a-form-item name="remark" label="Remark"
                        :rules="[{ required: true, message: 'Required your response remark' }]">
                        <a-textarea v-model:value="myResponse.remark" :rows="10"></a-textarea>
                    </a-form-item>
                    <p><a-switch v-model:checked="myResponse.by_email" label="Email" />&nbsp;&nbsp;Response by email</p>
                    <span v-if="myResponse.by_email">
                        <a-form-item name="email_address" label="Email"
                            :rules="[{ required: true, type: 'email', message: 'Reciever Email Address' }]">
                            <a-input v-model:value="myResponse.email_address" />
                        </a-form-item>
                        <a-form-item name="email_subject" label="Subject"
                            :rules="[{ required: true, message: 'Subject title of the Email' }]">
                            <a-input v-model:value="myResponse.email_subject" />
                        </a-form-item>
                        <a-form-item name="email_content" label="Content"
                            :rules="[{ required: true, message: 'Content Body of the Email' }]">
                            <quill-editor v-model:value="myResponse.email_content" style="min-height:200px;" />
                        </a-form-item>
                        <p>A link will be added at the end of the email</p>
                    </span>
                    <a-form-item name="fileList">
                        <a-upload v-model:file-list="myResponse.fileList" :beforeUpload="() => false" :max-count="10"
                            list-type="picture">
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
        </template>
        <!--// Response box -->


    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';
import CommentCard from '@/Components/Department/CommentCard.vue';
import MailerBox from '@/Components/Department/MailerBox.vue';
import EnquiryBox from '@/Components/Department/EnquiryBox.vue';
import { quillEditor } from 'vue3-quill';
import { ConsoleSqlOutlined, UploadOutlined } from '@ant-design/icons-vue';
import dayjs from 'dayjs';

export default {
    components: {
        DepartmentLayout,
        CommentCard,
        MailerBox,
        EnquiryBox,
        UploadOutlined,
        quillEditor,
    },
    props: ['fields', 'department', 'enquiry', 'active_question'],
    data() {
        return {
            myResponse: {
                enquiry_question_id: null,
                title: null,
                remark: null,
                by_email: false,
                email_address: null,
                email_subject: null,
                email_content: null,
                fileList: [],
            },
            radioStyle: {
                display: 'flex',
                height: '30px',
                lineHeight: '30px',
                marginLeft: '0'
            },
            responseStyle: "background: #A3E4D7;border-radius: 4px;margin-bottom: 24px;border: 0;overflow: hidden",
            activeKey: [],
        }
    },
    created() {
        this.activeKey.push(this.active_question)
    },
    methods: {
        toResponse(question) {
            if (question.is_closed) {
                if (!confirm('This Question is already CLOSED, would you like to response the question again?')) {
                    return;
                }
            }
            this.myResponse.email_address=this.enquiry.email
            this.myResponse.email_subject="Ticket No."+question.id+"/"+this.enquiry.id
            this.myResponse.title="Ticket No."+question.id+"/"+this.enquiry.id
            this.myResponse.enquiry_question_id = question.id
            this.myResponse.question = question
            window.scrollTo({ left: 0, top: document.body.scrollHeight, behavior: "smooth" });

        },
        closeResponse() {
            this.myResponse = {};
        },
        dateFormat(date, format = 'YYYY-MM-DD HH:mm') {
            return dayjs(date).format(format);
        },
        optionFind(value, options) {
            return options.find(option => {
                return option['value'] == value;
            })['label'];
        },
        optionFilter(values, options) {
            var items = options.filter(option => {
                return values.includes(option['value']);
            });
            return items;
        },
        onFinish() {
            // this.data= {...this.myResponse};
            // //this.$delete(data,'question');
            // console.log(data);
            this.$inertia.post(route('manage.enquiry.question.response'), this.myResponse, {
                onSuccess: (page) => {
                    this.myResponse = {};
                    console.log(page);
                },
                onError: (err) => {
                    console.log(err);
                }
            });
            //console.log(this.myResponse);
        },
        questionNubmer(question) {
            var caption = '提問編號 #' + question.id;
            if (question.enquiry_response_id) {
                caption += ', 追問回應編號#' + question.enquiry_response_id
            }
            if (question.is_closed) {
                caption += ' (Closed)'
            }
            return caption;
        },
        responseNumber(response) {
            return '回應編號 #' + response.id
        },
    },

}
</script>