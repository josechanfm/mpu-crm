<template>
  <DepartmentLayout title="Entries">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
        <span>📋</span> Entries
      </h2>
      <div class="flex flex-wrap items-center gap-2">
        <!-- Export Button (honours selected rows) -->
        <a-button type="primary" ghost @click="exportEntries">
          <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
          </svg>
          滙出Excel
        </a-button>
        <a-button :href="route('manage.forms.index')" type="primary">
          ← Back to Forms
        </a-button>
      </div>
    </div>

    <!-- Table -->
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
          <a-button
            :href="route('form.entry.receipt', { entry: record.id, uuid: record.uuid })"
            target="_blank"
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
      selectedItems: [],
    };
  },
  methods: {
    onChangeSelection(selectedRowKeys) {
      this.selectedItems = selectedRowKeys;
    },
    exportEntries() {
      // Build export URL with selected IDs (or empty for all)
      const ids = this.selectedItems.length > 0 ? this.selectedItems : [];
      const url = route('manage.entry.export', {
        form: this.form.id,
        ids: ids.join(',') // or use query param
      });
      // Trigger download by opening the URL
      window.open(url, '_blank');
    },
    deleteRecord(record) {
      this.$inertia.delete(
        route("manage.form.entries.destroy", { form: this.form, entry: record }),
        {
          onSuccess: () => {
            message.success('Delete Success.');
          },
          onError: (err) => {
            console.log(err);
          },
        }
      );
    },
  },
};
</script>