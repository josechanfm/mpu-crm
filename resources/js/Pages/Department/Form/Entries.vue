<template>
  <DepartmentLayout title="Entries">
    <!-- <a-select
      v-model:value="selectedDisplayName"
      style="width: 120px"
      :options="entryColumns.filter((c) => c.dataIndex.substring(0, 6) == 'extra_')"
      :field-names="{ value: 'dataIndex', label: 'title' }"
    />
    <a-button @click="createEventAttendees" :disabled="!selectedDisplayName"
      >Event Attendees</a-button
    > -->
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
          <a-button @click="editRecord(record)">Edit</a-button>
          <a-button
            :href="route('form.entry.receipt', { entry: record.id, uuid: record.uuid })"
            target="_blank"
            class="ant-btn"
            >Receipt
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
        <template v-else-if="column.dataIndex == 'created_at'">
          {{ record[column.dataIndex] }}
        </template>
        <template v-else>
          {{ record[column.dataIndex] }}
        </template>
      </template>
    </a-table>

    <!-- Modal Start-->
    <a-modal v-model:open="modal.isOpen" title="View Only" width="60%">
      <a-form
        :model="modal.data"
        ref="formRef"
        name="default"
        layout="vertical"
        :validate-messages="validateMessages"
      >
        <template v-for="field in form.fields" :key="field.id">
          <div v-if="form.require_member">
            <a-form-item
              label="Member Id"
              :name="field.id"
              :rules="[{ required: field.required }]"
            >
              <a-input type="inpuut" v-model:value="$page.props.user.id" />
            </a-form-item>
          </div>

          <div v-if="field.type == 'input'">
            <a-form-item
              :label="field.field_label"
              :name="field.id"
              :rules="[{ required: field.required }]"
            >
              <a-input type="inpuut" v-model:value="formData[field.id]" />
            </a-form-item>
          </div>
          <div v-else-if="field.type == 'textarea'">
            <a-form-item
              :label="field.field_label"
              :name="field.id"
              :rules="[{ required: field.required }]"
            >
              <a-textarea v-model:value="formData[field.id]" />
            </a-form-item>
          </div>
          <div v-else-if="field.type == 'richtext'">
            <a-form-item
              :label="field.field_label"
              :name="field.id"
              :rules="[{ required: field.required }]"
            >
              <quill-editor
                v-model:value="formData[field.id]"
                style="min-height: 200px"
              />
            </a-form-item>
          </div>
          <div v-else-if="field.type == 'radio'">
            <a-form-item
              :label="field.field_label"
              :name="field.id"
              :rules="[{ required: field.required }]"
            >
              <a-radio-group v-model:value="formData[field.id]">
                <a-radio
                  v-for="option in field.options"
                  :key="option.id"
                  :style="field.direction == 'H' ? '' : verticalStyle"
                  :value="option.value"
                  >{{ option.label }}</a-radio
                >
              </a-radio-group>
            </a-form-item>
          </div>
          <div v-else-if="field.type == 'checkbox'">
            <a-form-item
              :label="field.field_label"
              :name="field.id"
              :rules="[{ required: field.required }]"
            >
              <a-checkbox-group v-model:value="formData[field.id]">
                <a-checkbox
                  v-for="option in field.options"
                  :key="option.id"
                  :style="field.direction == 'H' ? '' : verticalStyle"
                  :value="option.value"
                  >{{ option.label }}</a-checkbox
                >
              </a-checkbox-group>
            </a-form-item>
          </div>
          <div v-else-if="field.type == 'dropdown'">
            <a-form-item
              :label="field.field_label"
              :name="field.id"
              :rules="[{ required: field.required }]"
            >
              <a-select
                v-model:value="formData[field.id]"
                :options="field.options"
              ></a-select>
            </a-form-item>
          </div>
          <div v-else-if="field.type == 'true_false'">
              <a-form-item
                :label="field.field_label"
                :name="field.id"
                :rules="[{ required: field.required }]"
              >
                <a-switch v-model:checked="formData[field.id]" checkedValue="1" unCheckedValue="0"/>
              </a-form-item>
          </div>
          <div v-else-if="field.type == 'date'">
            <a-form-item
              :label="field.field_label"
              :name="field.id"
              :rules="[{ required: field.required }]"
            >
              <a-date-picker
                v-model:value="formData[field.id]"
                :format="dateFormat"
                :valueFormat="dateFormat"
              />
            </a-form-item>
          </div>
          <div v-else-if="field.type == 'datetime'">
            <a-form-item
              :label="field.field_label"
              :name="field.id"
              :rules="[{ required: field.required }]"
            >
              <a-date-picker
                v-model:value="formData[field.id]"
                show-time
                :format="dateTimeFormat"
                :valueFormat="dateTimeFormat"
              />
            </a-form-item>
          </div>
          <div v-else-if="field.type == 'email'">
            <a-form-item
              :label="field.field_label"
              :name="field.id"
              :rules="[{ required: field.required }, { type: 'email' }]"
            >
              <a-input type="inpuut" v-model:value="formData[field.id]" />
            </a-form-item>
          </div>
          <div v-else-if="field.type == 'photo'">
            <a-button v-if="!formData[field.id]" @click="showCropModal = true">upload_profile_image</a-button>
            <a-button v-else @click="deletePhoto(field.id)">delete_photo"</a-button>
            <div class="flex flex-wrap mt-4 mb-6">
              <div class="w-full md:w-1/2 px-3">
                <div v-if="avatarPreview !== null">
                  <img :src="avatarPreview" />
                </div>
                <div v-else>
                  <img :src="formData[field.id]" />
                </div>
              </div>
            </div>
          </div>
          <div v-else>
            <a-form-item
              :label="field.field_label"
              :name="field.id"
              :rules="[{ required: field.required }, { type: 'email' }]"
            >
              <p>Data type undefined</p>
            </a-form-item>
          </div>
        </template>
      </a-form>
      <template #footer>
        <a-button key="back" @click="modal.isOpen = false">cancel</a-button>
        <a-button key="submit" type="primary" @click="updateRecord">
          update</a-button>
      </template>
    </a-modal>
    <!-- Modal End-->
  </DepartmentLayout>
</template>

<script>
import DepartmentLayout from "@/Layouts/DepartmentLayout.vue";
import { message } from "ant-design-vue";
//import CropperModal from "@/Components/Member/CropperModal.vue";

export default {
  components: {
    DepartmentLayout,
    //CropperModal,
  },
  props: ["form", "entries", "entryColumns"],
  data() {
    return {
      dateFormat: "YYYY-MM-DD",
      selectedDisplayName: null,
      avatarPreview: null,
      formData: {},
      rowSelection: {},
      selectedItems: [],
      showCropModal: false,
      modal: {
        isOpen: false,
        data: {},
        title: "Modal",
        mode: "",
      },
      validateMessages: {
        required: "必填欄位 ",
        types: {
          email: "不是有效電郵",
          number: "不是數字格式",
        },
        number: {
          range: "必須介於 ${min} 至 ${max}",
        },
      },
      verticalStyle: {
        display: "flex",
        height: "30px",
        lineHeight: "30px",
        width: "100%",
        marginLeft: "20px",
      },
    };
  },
  methods: {
    onChangeSelection(a, b) {
      this.selectedItems = a;
    },
    setCroppedImageData(data) {
      this.avatarPreview = data.imageUrl;
      this.formData[this.form.fields.find((x) => x.type == "photo").id] = data;
      //console.log(data);
    },
    deletePhoto(id) {
      this.formData["delete_photo"] = true;
      this.formData[id] = "";
    },
    updateRecord() {
      console.log(this.formData);
      this.$inertia.patch(
        route("manage.form.entries.update", { form: this.form, entry: this.modal.data }),
        { fields: this.formData },
        {
          onSuccess: (page) => {
            message.success(this.$t("update_success"));
            this.modal.isOpen = false;
          },
          onError: (err) => {
            console.log(err);
          },
        }
      );
    },
    editRecord(record) {
      this.formData = {};
      this.modal.data = record;
      this.modal.isOpen = true;
      this.modal.data.records.forEach((element) => {
        this.formData[element.form_field_id] = element.field_value;
        // console.log(this.form.fields.find((x) => x.id == element.form_field_id));
        // if (
        //   this.form.fields.find((x) => x.id == element.form_field_id).type == "checkbox"
        // ) {
        //   this.formData[element.form_field_id] = element.field_value;
        // } else {
        //   this.formData[element.form_field_id] = element.field_value;
        // }
      });
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
    getFieldValue(field) {
      const fv = this.modal.data.records.find((r) => r.form_field_id == field.id);
      if (fv) {
        return fv.field_value;
      }
      return "";
    },
  },
};
</script>
