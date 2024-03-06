<template>
  <DepartmentLayout title="表格" :breadcrumb="breadcrumb">
    <div class="flex-auto pb-3 text-right">
      <inertia-link :href="route('manage.forms.create')" class="ant-btn ant-btn-primary !rounded">Create Form</inertia-link>
    </div>
    <div class="container mx-auto pt-5">
      <div class="bg-white relative shadow rounded-lg overflow-x-auto">
        <a-table :dataSource="forms" :columns="columns">
          <template #headerCell="{ column }">
            {{ column.title }}
          </template>
          <template #bodyCell="{ column, text, record, index }">
            <template v-if="column.dataIndex == 'operation'">
              <inertia-link
                :href="route('manage.form.entries.index',record.id)"
                class="ant-btn">Response</inertia-link
              >
              <a
                :href="route('manage.entry.export', { form: record.id })"
                class="ant-btn">Export</a
              >
              <inertia-link
                :href="route('manage.form.fields.index', { form: record.id })"
                class="ant-btn"
              >Data Fields</inertia-link>
              <inertia-link 
                :href="route('manage.forms.edit',record.id)"
                class="ant-btn"
              >Edit</inertia-link>

              <a-popconfirm
                title="Confirm Delete"
                ok-text="Yes"
                cancel-text="No"
                @confirm="deleteConfirmed(record)"
                :disabled="record.entries_count > 0"
              >
                <a-button :disabled="record.entries_count > 0">Delete</a-button>
              </a-popconfirm>
              <a-button @click="backupRecords(record)" v-if="record.entries_count > 0">Backup</a-button>
            </template>
            <template v-else-if="column.type == 'yesno'">
              <span v-if="record[column.dataIndex] == 1">Yes</span>
              <span v-else>No</span>
            </template>
            <template v-else-if="column.dataIndex == 'entries'">
              {{ record.entries_count }}
            </template>
            <template v-else>
              {{ record[column.dataIndex] }}
            </template>
          </template>
        </a-table>
      </div>
      <p>From CAN NOT be delete, if Response is not empty.</p>
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
  props: ["departments","forms"],
  data() {
    return {
      breadcrumb:[
          {label:"人事處首頁" ,url:route('personnel.dashboard')},
          {label:"個資申報" ,url:null},
      ],
      loading: false,
      imageUrl: null,
      modal: {
        isOpen: false,
        data: {},
        title: "Modal",
        mode: "",
      },
      columns: [
        {
          title: "Name",
          i18n: "name",
          dataIndex: "name",
        },
        {
          title: "Title",
          i18n: "title",
          dataIndex: "title",
        },
        {
          title: "Require_login",
          i18n: "require_login",
          dataIndex: "require_login",
          type: "yesno",
        },
        {
          title: "Published",
          i18n: "published",
          dataIndex: "published",
          type: "yesno",
        },
        {
          title: "Entries",
          i18n: "entries",
          dataIndex: "entries",
          key: "entries",
        },
        {
          title: "Operation",
          i18n: "operation",
          dataIndex: "operation",
          key: "operation",
        },
      ]
    };
  },
  created() {},
  methods: {
    deleteConfirmed(record) {
      console.log("delete");
      console.log(record);
      this.$inertia.delete(route("manage.forms.destroy", { form: record.id }), {
        onSuccess: (page) => {
          console.log(page);
        },
        onError: (error) => {
          alert(error.message);
        },
      });
    },
    backupRecords(record) {
      if (!confirm("Do you sure want to backup?")) return;
      this.$inertia.post(route("manage.form.backup", record.id), {
        onSuccess: (page) => {
          console.log(page);
        },
        onError: (error) => {
          alert(error.message);
        },
      });
    },
  },
};
</script>
