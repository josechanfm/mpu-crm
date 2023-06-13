<template>
    <a-form
        ref="modalRef"
        :model="enquiry"
        name="EnquiryBox"
        :label-col="{ span: 2 }"
        :wrapper-col="{ span: 22 }"
        autocomplete="off"
        :rules="rules"
        :validate-messages="validateMessages"
        enctype="multipart/form-data"
    >
        <a-form-item label="Title" name="title">
            <a-input v-model:value="enquiry.title"/>
        </a-form-item>
        <a-form-item label="Email" name="email">
            <a-input v-model:value="enquiry.email"/>
        </a-form-item>
        <a-form-item label="Phone" name="phone">
            <a-input v-model:value="enquiry.phone"/>
        </a-form-item>
        <a-form-item label="Content" name="content">
            <div style="background-color: white;">
              <quill-editor v-model:value="enquiry.content" style="min-height:200px;" />
            </div>
        </a-form-item>
        <a-form-item label="Response" name="response">
            <div style="background-color: white;">
              <quill-editor v-model:value="enquiry.response" style="min-height:200px;" />
            </div>
        </a-form-item>
        <div>
            <div style="text-align:right">
                <a-button @click="updateEnquiry"  style="display:inline-block" v-if="enquiry.id">Update</a-button>
                <a-button @click="storeEnquiry"  style="display:inline-block" v-else >Create</a-button>
            </div>
            <a-upload v-model:file-list="enquiry.files" :multiple="false">
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
  props: ['enquiry'],
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
        this.enquiry.is_html=!this.enquiry.is_html;
    },
    storeEnquiry(){
        console.log(this.enquiry)
        this.$refs.modalRef.validateFields().then(()=>{
            this.$inertia.post(
                route('manage.department.enquiries.store', 
                    { 
                        department: this.enquiry.department_id, 
                    }
                ),
                this.enquiry, 
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
    updateEnquiry(){
        console.log(this.enquiry)
        this.$refs.modalRef.validateFields().then(()=>{

            this.$inertia.patch(
                route('manage.department.enquiries.update', 
                    { 
                        department: this.enquiry.department_id, 
                        enquiry: this.enquiry.id 
                    }
                ), 
                this.enquiry, {
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
