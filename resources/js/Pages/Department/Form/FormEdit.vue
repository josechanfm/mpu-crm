<template>
  <DepartmentLayout :title="form.id ? '編輯表格' : '新增表格'">
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
      <p v-if="form.title" class="text-sm text-gray-500">{{ form.title }}</p>
      <div class="ml-auto flex flex-wrap gap-2">
        <a-button :href="route('manage.forms.index')">返回</a-button>
        <a-button
          v-if="form.id"
          :href="route('manage.form.fields.index', { form: form.id })"
        >
          欄位
        </a-button>
      </div>
    </div>

    <div class="rounded-lg bg-white p-5 shadow">
      <a-form
        ref="modalRef"
        :model="form"
        name="formEdit"
        :label-col="{ span: 6 }"
        :wrapper-col="{ span: 16 }"
        autocomplete="off"
        :rules="rules"
        :validate-messages="validateMessages"
        @finish="onFinish"
      >
        <h2 class="mb-4 text-base font-medium">基本資料</h2>
        <a-form-item v-if="is('admin|master')" label="部門" name="department_id">
          <a-select
            show-search
            v-model:value="form.department_id"
            :options="departments"
            :fieldNames="{ value: 'id', label: 'abbr' }"
            optionFilterProp="abbr"
            optionLabelProp="abbr"
          />
        </a-form-item>
        <a-form-item label="表格名稱" name="name">
          <a-input v-model:value="form.name" />
          <p class="mt-1 text-xs text-gray-500">供內部辨識，並用作匯出檔名。</p>
        </a-form-item>
        <a-form-item label="標題" name="title">
          <a-input v-model:value="form.title" />
          <p class="mt-1 text-xs text-gray-500">顯示在表格頂端。</p>
        </a-form-item>

        <h2 class="mb-4 mt-8 text-base font-medium">內容</h2>
        <a-form-item label="說明" name="description">
          <quill-editor v-model="form.description" />
        </a-form-item>
        <a-form-item label="歡迎訊息">
          <a-switch v-model:checked="form.openWelcome" />
          <span class="ml-2 text-xs text-gray-500">填表前顯示</span>
        </a-form-item>
        <a-form-item v-if="form.openWelcome" label=" " :colon="false" name="welcome">
          <a-textarea v-model:value="form.welcome" :rows="6" />
        </a-form-item>
        <a-form-item label="致謝訊息">
          <a-switch v-model:checked="form.openThanks" />
          <span class="ml-2 text-xs text-gray-500">送出後顯示</span>
        </a-form-item>
        <template v-if="form.openThanks">
          <a-form-item label="收據編號" name="settings_receipt_num">
            <a-switch
              v-model:checked="form.settings.receipt_num"
              :unCheckedValue="0"
              :checkedValue="1"
            />
          </a-form-item>
          <a-form-item label=" " :colon="false" name="thankyou">
            <quill-editor v-model="form.thanks" />
          </a-form-item>
        </template>

        <h2 class="mb-4 mt-8 text-base font-medium">填寫設定</h2>
        <a-form-item label="需要登入" name="require_login">
          <a-switch v-model:checked="form.require_login" @change="onRequireLogin" />
        </a-form-item>
        <a-form-item v-if="form.require_login" label="只限教職員" name="for_staff">
          <a-switch v-model:checked="form.for_staff" />
        </a-form-item>
        <a-form-item label="已發佈" name="published">
          <a-switch v-model:checked="form.published" :disabled="form.entries_count > 0" />
          <p v-if="form.entries_count > 0" class="mt-1 text-xs text-gray-500">
            已有回覆。取消發佈前請先備份。
          </p>
        </a-form-item>
        <a-form-item label="顯示收據" name="show_receipt">
          <a-switch v-model:checked="form.show_receipt" />
          <span class="ml-2 text-xs text-gray-500">在致謝頁顯示收據</span>
        </a-form-item>

        <h2 class="mb-4 mt-8 text-base font-medium">圖片</h2>
        <a-form-item label="橫幅" name="banner_image">
          <div class="flex items-start gap-4">
            <img v-if="form.banner_url" :src="form.banner_url" alt="" class="h-24 w-40 rounded object-cover" />
            <a-upload
              v-model:file-list="form.banner_image"
              list-type="picture-card"
              accept="image/*"
              :max-count="1"
              :before-upload="keepLocalFile"
              @change="handleBannerUpload"
            >
              <div v-if="!form.banner_image?.length">
                <plus-outlined />
                <div class="mt-1">上傳</div>
              </div>
            </a-upload>
          </div>
          <p class="text-xs text-gray-500">JPG 或 PNG，小於 2MB。</p>
        </a-form-item>
        <a-form-item label="縮圖" name="thumb_image">
          <div class="flex items-start gap-4">
            <img v-if="form.thumb_url" :src="form.thumb_url" alt="" class="h-24 w-24 rounded object-cover" />
            <a-upload
              v-model:file-list="form.thumb_image"
              list-type="picture-card"
              accept="image/*"
              :max-count="1"
              :before-upload="keepLocalFile"
              @change="handleThumbUpload"
            >
              <div v-if="!form.thumb_image?.length">
                <plus-outlined />
                <div class="mt-1">上傳</div>
              </div>
            </a-upload>
          </div>
        </a-form-item>

        <h2 v-if="isLayoutPreset" class="mb-4 mt-8 text-base font-medium">版面</h2>
        <a-form-item v-if="isLayoutPreset" label="版面" name="layout">
          <a-select
            v-model:value="form.layout"
            :options="layoutStyle"
            optionFilterProp="label"
          />
        </a-form-item>

        <a-form-item :wrapper-col="{ offset: 6, span: 16 }">
          <a-button type="primary" html-type="submit">{{ form.id ? '儲存' : '建立' }}</a-button>
        </a-form-item>
      </a-form>
    </div>
  </DepartmentLayout>
</template>

<script>
import DepartmentLayout from "@/Layouts/DepartmentLayout.vue";
import { PlusOutlined } from "@ant-design/icons-vue";
import QuillEditor from "@/Components/QuillEditor.vue";

export default {
  components: {
    DepartmentLayout,
    PlusOutlined,
    QuillEditor,
  },
  props: ["departments", "form"],
  data() {
    return {
      loading: false,
      rules: {
        name: { required: true },
        title: { required: true },
      },
      validateMessages: {
        required: "${label} 為必填",
      },
      layoutStyle: [
        { value: "FormDefaultWebLayout", label: "網頁版面" },
        { value: "FormDefaultBlankLayout", label: "空白版面" },
      ],
    };
  },
  created() {
    if (!this.form.layout) {
      this.form.layout = "FormDefaultWebLayout";
    }
    if (!this.form.settings || typeof this.form.settings !== "object") {
      this.form.settings = { receipt_num: 1 };
    }
    this.form.openWelcome = Boolean(this.form.welcome);
    this.form.openThanks = Boolean(this.form.thanks);
  },
  computed: {
    isLayoutPreset() {
      return this.layoutStyle.some((opt) => opt.value === this.form.layout);
    },
  },
  methods: {
    onRequireLogin(checked) {
      if (!checked) this.form.for_staff = false;
    },
    onFinish() {
      if (this.form.id) this.updateRecord();
      else this.storeRecord();
    },
    storeRecord() {
      this.$inertia.post(route("manage.forms.store"), this.form, {
        onError: (err) => console.log(err),
      });
    },
    updateRecord() {
      this.form._method = "PATCH";
      this.$inertia.post(route("manage.forms.update", this.form.id), this.form, {
        onError: (error) => console.log("form update error", error),
      });
    },
    checkFileSize(file) {
      const underLimit = file.size / 1024 / 1024 < 2;
      if (!underLimit) {
        this.$message.error("圖片需小於 2MB");
      }
      return underLimit;
    },
    keepLocalFile() {
      return false;
    },
    handleBannerUpload(info) {
      if (!this.checkFileSize(info.file)) {
        this.form.banner_image = [];
        return;
      }
      if (info.file.status === "done") {
        this.form.banner_image = [info.file.originFileObj];
      }
    },
    handleThumbUpload(info) {
      if (!this.checkFileSize(info.file)) {
        this.form.thumb_image = [];
        return;
      }
      if (info.file.status === "done") {
        this.form.thumb_image = [info.file.originFileObj];
      }
    },
  },
};
</script>
