<template>
    <div class="flex justify-center items-center h-screen">
      <a-card class="w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4">Upload PDF</h1>
        <a-form @submit.prevent="submit">
          <a-form-item label="Title" required>
            <a-input type="inpuut" v-model:value="pdfFile.title" placeholder="Enter the title" />
          </a-form-item>
          <a-form-item label="Description">
            <a-textarea v-model="pdfFile.description" placeholder="Enter a description" />
          </a-form-item>
          <a-form-item>
            <a-upload
              :file-list="pdfFile.file"
              accept=".pdf"
              @change="handleFileChange"
              :before-upload="beforeUpload"
              :show-upload-list="false"
              :auto-upload="false"  
            >
              <a-button>
                <UploadOutlined /> Click to Upload
              </a-button>
            </a-upload>
          </a-form-item>
          <a-form-item>
            <a-button type="primary" html-type="submit">
              Upload
            </a-button>
          </a-form-item>
        </a-form>
        <p v-if="successMessage" class="text-green-500 mt-4">{{ successMessage }}</p>
        <p v-if="errorMessage" class="text-red-500 mt-4">{{ errorMessage }}</p>
      </a-card>
    </div>
  </template>
  
  <script>
  import { message } from 'ant-design-vue';
  import { UploadOutlined } from '@ant-design/icons-vue';

  export default {
    components:{
        UploadOutlined
    },
    props: [],
    data() {
      return {
        pdfFile:{
            title: '', // Title input
            description: '', // Description input
            file: null,
        },
        successMessage: '',
        errorMessage: '',
      };
    },
    methods: {
      handleFileChange(info) {
        if (info.file.status === 'done') {
          this.successMessage = 'File uploaded successfully.';
          this.errorMessage = '';
        } else if (info.file.status === 'error') {
          this.errorMessage = 'Upload failed.';
          this.successMessage = '';
        }
        this.file = info.file.originFileObj;
      },
      beforeUpload(file) {
        const isPdf = file.type === 'application/pdf';
        const isUnder2MB = file.size / 1024 / 1024 < 5; // Check if file size is less than 2MB
  
        if (!isPdf) {
          message.error('You can only upload PDF files!');
          return false; // Prevent upload
        }
  
        if (!isUnder2MB) {
          message.error('File size must be smaller than 5MB!');
          return false; // Prevent upload
        }
        this.pdfFile.file = [...(this.pdfFile.file || []), file];
        return false; // Allow upload
      },
      submit() {
        if (!this.pdfFile.file) {
          message.error('Please select a file to upload.');
          return;
        }
        // Use Inertia for the API call
        this.$inertia.post(this.route('ebooks.store'), this.pdfFile, {
          onSuccess: () => {
            this.successMessage = 'File uploaded successfully.';
            this.errorMessage = '';
          },
          onError: (errors) => {
            this.errorMessage = 'Upload failed: ' + errors;
            this.successMessage = '';
          },
        });
      },
    },
  };
  </script>
  
  <style scoped>
  /* Add any additional styles here */
  </style>