<template>
    <a-form
        ref="modalRef"
        :model="email"
        name="Mailerbox"
        :label-col="{ span: 2 }"
        :wrapper-col="{ span: 22 }"
        autocomplete="off"
        :rules="rules"
        :validate-messages="validateMessages"
        enctype="multipart/form-data"
    >
        <a-form-item label="Sender" name="sender">
            <a-input type="inpuut" v-model:value="email.sender"/>
        </a-form-item>
        <a-form-item label="Receiver" name="receiver">
            <a-input type="inpuut" v-model:value="email.receiver" />
        </a-form-item>
        <a-form-item label="subject" name="subject">
            <a-input type="inpuut" v-model:value="email.subject" />
        </a-form-item>
        <a-form-item label="Content" name="content">
            <a-button @click="switchRichTextMode">Switch Text Mode</a-button>
            <quill-editor v-if="email.is_html" v-model="email.content" style="min-height:200px;" :options="editorOption"/>
            <a-textarea v-else v-model:value="email.content" :rows="10"></a-textarea>
        </a-form-item>
        <div>
            <div style="text-align:right">
                <a-button @click="sendMail"  style="display:inline-block">Send</a-button>
            </div>
            <a-upload v-model:file-list="email.attachments" :multiple="false">
                <a-button>
                <upload-outlined accept="image/png, image/jpeg,.doc,.docx,.pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"></upload-outlined>
                    Upload
                </a-button>
            </a-upload>
        </div>            
    </a-form>
    
</template>


<script>
// import Editor from '@tinymce/tinymce-vue';
import QuillEditor from "@/Components/QuillEditor.vue";
import dayjs from 'dayjs';
import { UploadOutlined } from '@ant-design/icons-vue';


export default {
  components: {
    // Editor,
    QuillEditor,
    dayjs,  
    UploadOutlined, 
  },
//   emits:['emailBox'],
  props: ['email'],
  data() {
    return {
      editorOption: {
        modules:{
        },
        placeholder: 'Compose an epic...',
      },
      activeKey:0,
      rules:{
          name_zh:{required:true},
          mobile:{required:true},
          state:{required:true},
      },
      validateMessages:{
          required: '${label} is required!',
          types: {
              email: '${label} is not a valid email!',
              number: '${label} is not a valid number!',
          },
          number: {
              range: '${label} must be between ${min} and ${max}',
          },
      },
    }
  },
  methods: {
    switchRichTextMode(){
        this.email.is_html=!this.email.is_html;
    },
    sendMail(){
        // console.log(this.email)
        // return true;
        this.$refs.modalRef.validateFields().then(()=>{
            this.$inertia.post('/manage/mailers/', this.email,{
                onSuccess:(page)=>{
                    this.modal.data={};
                    this.modal.isOpen=false;
                },
                onError:(err)=>{
                    console.log(err);
                }
            });
        }).catch(err => {
            console.log(err);
        });
    },
  },
}
</script>
