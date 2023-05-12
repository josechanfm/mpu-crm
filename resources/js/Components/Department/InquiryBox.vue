<template>
    <a-form
        ref="modalRef"
        :model="inquiry"
        name="InquiryBox"
        :label-col="{ span: 2 }"
        :wrapper-col="{ span: 22 }"
        autocomplete="off"
        :rules="rules"
        :validate-messages="validateMessages"
        enctype="multipart/form-data"
    >
        <a-form-item label="Title" name="title">
            <a-input v-model:value="inquiry.title"/>
        </a-form-item>
        <a-form-item label="Email" name="email">
            <a-input v-model:value="inquiry.email"/>
        </a-form-item>
        <a-form-item label="Phone" name="phone">
            <a-input v-model:value="inquiry.phone"/>
        </a-form-item>
        <a-form-item label="Content" name="content">
            <div style="background-color: white;">
              <quill-editor v-model:value="inquiry.content" style="min-height:200px;" />
            </div>
        </a-form-item>
        <a-form-item label="Response" name="response">
            <div style="background-color: white;">
              <quill-editor v-model:value="inquiry.response" style="min-height:200px;" />
            </div>
        </a-form-item>
        <div>
            <div style="text-align:right">
                <a-button @click="updateInquiry"  style="display:inline-block" v-if="inquiry.id">Update</a-button>
                <a-button @click="storeInquiry"  style="display:inline-block" v-else >Create</a-button>
            </div>
            <a-upload v-model:file-list="inquiry.files" :multiple="false">
                <a-button>
                <upload-outlined accept="image/png, image/jpeg,.doc,.docx,.pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"></upload-outlined>
                    Upload
                </a-button>
            </a-upload>
        </div>            
    </a-form>
    
</template>


<script>
import Editor from '@tinymce/tinymce-vue';
import { quillEditor } from 'vue3-quill';
import dayjs from 'dayjs';
import { UploadOutlined } from '@ant-design/icons-vue';


export default {
  components: {
    Editor,
    quillEditor,
    dayjs,  
    UploadOutlined, 
  },
//   emits:['emailBox'],
  props: ['inquiry'],
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
        this.inquiry.is_html=!this.inquiry.is_html;
    },
    storeInquiry(){
        console.log(this.inquiry)
        this.$refs.modalRef.validateFields().then(()=>{
            this.$inertia.post(
                route('manage.department.inquiries.store', 
                    { 
                        department: this.inquiry.department_id, 
                    }
                ),
                this.inquiry, 
                {
                    onSuccess: (page) => {
                        console.log(page);
                    },
                    onError: (error) => {
                        console.log(error);
                    }
                }
            )
        }).catch(err => {
            console.log(err);
        });
    },
    updateInquiry(){
        console.log(this.inquiry)
        this.$refs.modalRef.validateFields().then(()=>{

            this.$inertia.patch(
                route('manage.department.inquiries.update', 
                    { 
                        department: this.inquiry.department_id, 
                        inquiry: this.inquiry.id 
                    }
                ), 
                this.inquiry, {
                    onSuccess: (page) => {
                        console.log(page);
                    },
                    onError: (error) => {
                        console.log(error);
                    }
                }
            )
        }).catch(err => {
            console.log(err);
        });
    },
  },
}
</script>
