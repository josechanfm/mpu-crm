<template>
  <DepartmentLayout title="Entries">
      <!-- Header -->
    <!-- Header -->
  <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <!-- Title -->
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
      <span>📄</span> View Entries
    </h2>

    <!-- Action Buttons -->
    <div class="flex flex-wrap items-center gap-2">
      <a-button type="primary" ghost :href="route('manage.entry.export', form.id)" class="ant-btn ant-btn-primary">
        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
        滙出Excel
      </a-button>
      <a-button :href="route('manage.form.entries.index', form.id)" type="primary">
        ← Back to List
      </a-button>
    </div>
  </div>


    <a-table
      :dataSource="entries"
      :columns="entryColumns"
      :row-selection="{ onChange: onChangeSelection, selectedRowKeys: selectedItems }"
      :rowKey="(record) => record.id"
    >
      <template #bodyCell="{ column, text, record, index }">
        <template v-if="column.dataIndex == 'operation'">
          <a-button :href="route('manage.form.entries.show', { form: form.id, entry: record.id })">
              View
          </a-button>
          <a-button @click="editRecord(record)">Edit</a-button>
          <a-button
            :href="route('form.entry.receipt', { entry: record.id, uuid: record.uuid })"
            target="_blank"
            class="ant-btn"
          >
            Receipt
          </a-button>
          <a-popconfirm
            title="Confirm to delete the record?"
            ok-text="Yes"
            cancel-text="No"
            @confirm="deleteRecord(record)"
          >
            <a-button danger>Delete</a-button>
          </a-popconfirm>
        </template>
        <template v-else-if="column.dataIndex=='net_id' && record.admin_user">
          {{ record.admin_user.username }}
        </template>
        <template v-else>
          {{ record[column.dataIndex] }}
        </template>
      </template>
    </a-table>

  </DepartmentLayout>
</template>

<script>
import DepartmentLayout from "@/Layouts/DepartmentLayout.vue";
import { message } from "ant-design-vue";

export default {
  components: {
    DepartmentLayout,
  },
  props: ["form", "entries", "entryColumns"],
  data() {
    return {
      modalVisible: false,
      selectedEntryId: null,
      selectedItems: [],
    };
  },
  methods: {
    onChangeSelection(selectedRowKeys, selectedRows) {
      this.selectedItems = selectedRowKeys;
    },
    editRecord(record) {
      this.selectedEntryId = record.id;
      this.modalVisible = true;
    },
    deleteRecord(record) {
      this.$inertia.delete(
        route("manage.form.entries.destroy", { form: this.form, entry: record }),
        {
          onSuccess: (page) => {
            message.success('Delete Success.');
          },
          onError: (err) => {
            console.log(err);
          },
        }
      );
    },
    refreshEntries() {
      // Reload only the entries list to reflect updates
      this.$inertia.reload({ only: ['entries'] });
    },
  },
};
</script>