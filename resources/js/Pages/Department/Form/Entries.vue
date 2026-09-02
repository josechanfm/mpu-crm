<template>
  <DepartmentLayout title="Entries">
    <div class="pb-3 float-right">
      <a :href="route('manage.entry.export', form.id)" class="ant-btn ant-btn-primary">滙出Excel</a>
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