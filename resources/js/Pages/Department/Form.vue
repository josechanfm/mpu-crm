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
                <quill-editor
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
                <div v-if="form.media.length">
                    <inertia-link
                    :href="route('manage.form.deleteMedia', form.media[0].id)"
                    class="float-right text-red-500"
                    >
                    <svg
                        focusable="false"
                        class=""
                        data-icon="delete"
                        width="1em"
                        height="1em"
                        fill="currentColor"
                        aria-hidden="true"
                        viewBox="64 64 896 896"
                    >
                        <path
                        d="M360 184h-8c4.4 0 8-3.6 8-8v8h304v-8c0 4.4 3.6 8 8 8h-8v72h72v-80c0-35.3-28.7-64-64-64H352c-35.3 0-64 28.7-64 64v80h72v-72zm504 72H160c-17.7 0-32 14.3-32 32v32c0 4.4 3.6 8 8 8h60.4l24.7 523c1.6 34.1 29.8 61 63.9 61h454c34.2 0 62.3-26.8 63.9-61l24.7-523H888c4.4 0 8-3.6 8-8v-32c0-17.7-14.3-32-32-32zM731.3 840H292.7l-24.2-512h487l-24.2 512z"
                        ></path>
                    </svg>
                    </inertia-link>
                    <img :src="form.media[0].preview_url" width="100" />
                </div>
                <div v-else>
                    <a-upload
                    v-model:file-list="form.image"
                    :multiple="false"
                    :max-count="1"
                    list-type="picture-card"
                    :beforeUpload="
                        () => {
                        return false;
                        }
                    "
                    :show-upload-list="false"
                    @change="uploadChange"
                    >
                    <img v-if="imageUrl" :src="imageUrl" alt="banner" />
                    <div v-else>
                        <loading-outlined v-if="loading"></loading-outlined>
                        <plus-outlined v-else></plus-outlined>
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
    uploadChange(info) {
      console.log(info);
      const isJpgOrPng =
        info.file.type === "image/jpeg" || info.file.type === "image/png";
      if (!isJpgOrPng) {
        console.log("image format!");
        message.error("You can only upload JPG/PNG file!");
      }
      const isLt2M = info.file.size / 1024 / 1024 < 0.2;
      if (!isLt2M) {
        console.log("image size");
        message.error("Image must smaller than 2MB!");
      }

      if (isJpgOrPng && isLt2M) {
        this.getBase64(info.file, (base64Url) => {
          this.imageUrl = base64Url;
          this.loading = true;
        });
      } else {
        this.form.image = [];
      }
    },
    getBase64(img, callback) {
      const reader = new FileReader();
      reader.addEventListener("load", () => callback(reader.result));
      reader.readAsDataURL(img);
    },
  },
};
</script>
