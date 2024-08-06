<template>
  <DepartmentLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        From generator
      </h2>
    </template>
    <div class="container mx-auto">
      <div class="bg-white relative shadow  p-5 rounded-lg overflow-x-auto">
        <a-form
            ref="modalRef"
            :model="form"
            name="From"
            :label-col="{ span: 6 }"
            :wrapper-col="{ span: 18 }"
            autocomplete="off"
            :rules="rules"
            :validate-messages="validateMessages"
            @finish="onFinish"
        >
            <a-form-item label="Department" name="department_id" v-if="is('admin|master')">
                <a-select 
                  show-search 
                  v-model:value="form.department_id" 
                  :options="departments" 
                  :fieldNames="{value:'id',label:'abbr'}" 
                  optionFilterProp="abbr"
                  optionLabelProp="abbr"
                />
            </a-form-item>
            <a-form-item label="Form Name" name="name">
                <a-input v-model:value="form.name" />
            </a-form-item>
            <a-form-item label="Title" name="title">
                <a-input v-model:value="form.title" />
            </a-form-item>
                <div class="text-right">
                    <a @click="form.openWelcome=!form.openWelcome">Welcome Message</a>
                </div>
            <a-form-item label="Welcome Message" name="welcome" v-if="form.openWelcome">
                <quill-editor
                    v-model:value="form.welcome"
                    style="min-height: 200px"
                />
            </a-form-item>
            <a-form-item label="Description" name="description">
                <quill-editor
                    v-model:value="form.description"
                    style="min-height: 200px"
                />
            </a-form-item>
            <div class="text-right">
                <a @click="form.openThanks=!form.openThanks">Thank You Message</a>
            </div>
            <a-form-item label="Thank you Message" name="thankyou" v-if="form.openThanks">
                <a-textarea
                    v-model:value="form.thanks"
                    style="min-height: 200px"
                />
            </a-form-item>
            <a-form-item label="Require Login" name="require_login">
                <a-switch
                    v-model:checked="form.require_login"
                    :unCheckedValue="0"
                    :checkedValue="1"
                    @change="form.for_staff = false"
                />
                <span class="pl-3">Login is required</span>
            </a-form-item>
            <a-form-item
                label="For Staff"
                name="for_staff"
                v-if="form.require_login"
            >
                <a-switch
                    v-model:checked="form.for_staff"
                    :unCheckedValue="0"
                    :checkedValue="1"
                />
                <span class="pl-3">For member only</span>
            </a-form-item>
            <a-form-item label="Published" name="published">
                <a-switch
                    v-model:checked="form.published"
                    :unCheckedValue="0"
                    :checkedValue="1"
                    :disabled="form.entries_count > 0"
                />
                <span class="pl-3">The form is published</span
                ><br />
                <span v-if="form.entries_count > 0">
                    The form has responses, please backup before unpublished.</span
                >
            </a-form-item>
            <a-form-item label="Banner Image" name="banner_image">
              <div class="flex gap-5">
                  <div>
                    <img :src="form.banner_url" width="100"/>
                  </div>
                  <a-upload
                    v-model:file-list="form.banner_image"
                    :multiple="false"
                    :max-count="1"
                    :accept="'image/*'"
                    list-type="picture-card"
                    @change="handleBannerUpload"
                    >
                    <!--before upload preview-->
                    <div v-if="!form.banner_image">
                        <plus-outlined></plus-outlined>
                        <div class="ant-upload-text">Upload</div>
                    </div>
                  </a-upload>
              </div>
            </a-form-item>

            <a-form-item label="Thumb Image" name="thumb_image">
              <div class="flex gap-5">
                  <div>
                    <img :src="form.thumb_url" width="100"/>
                  </div>
                  <a-upload
                    v-model:file-list="form.thumb_image"
                    :multiple="false"
                    :max-count="1"
                    :accept="'image/*'"
                    list-type="picture-card"
                    @change="handleThumbUpload"
                    >
                    <!--before upload preview-->
                    <div v-if="!form.thumb_image">
                        <plus-outlined></plus-outlined>
                        <div class="ant-upload-text">Upload</div>
                    </div>
                  </a-upload>
              </div>
            </a-form-item>

            <a-form-item :wrapper-col="{ offset: 12, span: 10 }">
                <a-button type="primary" html-type="submit">Submit</a-button>
            </a-form-item>
        </a-form>
      </div>
    </div>

  </DepartmentLayout>
</template>

<script>
import DepartmentLayout from "@/Layouts/DepartmentLayout.vue";
import {
  UploadOutlined,
  LoadingOutlined,
  PlusOutlined,
  DeleteOutlined,
  InfoCircleFilled,
} from "@ant-design/icons-vue";
import Icon, { RestFilled } from "@ant-design/icons-vue";
import { quillEditor, Quill } from "vue3-quill";
import { message } from "ant-design-vue";

export default {
  components: {
    DepartmentLayout,
    UploadOutlined,
    LoadingOutlined,
    PlusOutlined,
    DeleteOutlined,
    RestFilled,
    quillEditor,
    message,
  },
  props: ["departments", "form"],
  data() {
    return {
      loading: false,
      imageUrl: null,
      rules: {
        name: { required: true },
        title: { required: true },
      },
      validateMessages: {
        required: "${label} is required!",
        types: {
          email: "${label} is not a valid email!",
          number: "${label} is not a valid number!",
        },
        number: {
          range: "${label} must be between ${min} and ${max}",
        },
      },
      labelCol: {
        style: {
          width: "150px",   
        },
      },
    };
  },
  created() {
  },
  mounted() {

  },
  methods: {
    onFinish(){
        console.log('on Finshed')
        if(this.form.id){
            console.log('Update')
            this.updateRecord()
        }else{
            console.log('Create')
            this.storeRecord()
        }
    },
    storeRecord() {
        this.$inertia.post(route("manage.forms.store"), this.form, {
          onSuccess: (page) => {
              this.imageUrl = null;
          },
          onError: (err) => {
              console.log(err);
          },
        });
    },
    updateRecord() {
        this.form._method = "PATCH";
        this.$inertia.post(
            route("manage.forms.update", this.form.id),this.form,{
                onSuccess: (page) => {
                    console.log(page);
                },
                onError: (error) => {
                    console.log(error);
                },
            }
        );
    },
    checkFileSize(file) {
      const isLessThan200KB = file.size / 1024 / 1024 < 2;
      if (!isLessThan200KB) {
        this.$message.error('Image must be smaller than 200KB!');
        return false;
      }
      return true;
    },
    handleBannerUpload(info) {
      if(!this.checkFileSize(info.file)){
        this.form.banner_image = null;
        return false
      }
      if (info.file.status === 'uploading') {
        this.loading = true;
      }
      if (info.file.status === 'done' ) {
        // Reset the form.banner_image to only include the valid file
        this.form.banner_image = [info.file.originFileObj];
        this.loading = false;
      }
    },

    handleThumbUpload(info) {
      if(!this.checkFileSize(info.file)){
        this.form.thumb_image = null;
        return false
      }
      if (info.file.status === 'uploading') {
        this.loading = true;
      }
      if (info.file.status === 'done' ) {
        // Reset the form.banner_image to only include the valid file
        this.form.thumb_image = [info.file.originFileObj];
        this.loading = false;
      }
    },    
  },
};

</script>
