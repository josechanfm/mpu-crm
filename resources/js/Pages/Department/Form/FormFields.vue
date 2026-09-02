<template>
    <DepartmentLayout title="Dashboard" :department="department">
        <div class="flex pb-3 justify-end gap-2">
            <a-button :href="route('manage.forms.index')">Back</a-button>
            <a-button v-if="isDraggable" type="primary" ghost @click="reloadFormFields">Finish</a-button>
            <a-button v-else type="primary" ghost @click="isDraggable = !isDraggable">Drag sort</a-button>
            <a-button @click="createRecord()" type="primary">Create Field</a-button>
        </div>
        <a-table 
            :dataSource="formFields" 
            :columns="columns" 
            :customRow="customRow"
        >
            <template #bodyCell="{ column, record, index }">
                <template v-if="column.dataIndex === 'operation'">
                    <a-button @click="editRecord(record)" :disabled="form.published == true">Edit</a-button>
                    <a-popconfirm title="Are you sure delete this field?" ok-text="Yes" cancel-text="No"
                        @confirm="deleteRecord(record)" :disabled="form.published == true">
                        <a-button :disabled="form.published == true">Delete</a-button>
                    </a-popconfirm>
                    <a-popconfirm title="Are you sure to clone this field?" ok-text="Yes" cancel-text="No"
                        @confirm="cloneRecord(record)" :disabled="form.published == true">
                        <a-button :disabled="form.published == true">Clone</a-button>
                    </a-popconfirm>
                </template>
                <template v-else-if="column.dataIndex == 'dragger' && isDraggable">
                    <HolderOutlined />
                </template>
                <template v-else-if="column.dataIndex == 'required'">
                    {{ record.required }}
                </template>
                <template v-else-if="column.dataIndex == 'in_column'">
                    {{ record.in_column }}
                </template>
            </template>
        </a-table>
        <div>
            <div>備註:</div>
            <div>1. 若表格式發佈,其已建主的欄位內容修改.</div>
        </div>

        <!-- Modal Component -->
        <FormFieldModal
            v-model:open="modal.isOpen"
            :mode="modal.mode"
            :initialData="modal.data"
            :formId="form.id"
            :isAdminOrMaster="isAdminOrMaster"
            :optionTemplates="optionTemplates"
            @save="handleSaveField"
            @update="handleUpdateField"
        />
    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';
import { VueDraggableNext } from 'vue-draggable-next'
import { HolderOutlined } from "@ant-design/icons-vue";
import Sortable from "sortablejs";
import FormFieldModal from './FormFieldModal.vue';

export default {
    components: {
        DepartmentLayout,
        draggable: VueDraggableNext,
        Sortable,
        HolderOutlined,
        FormFieldModal,
    },
    props: ['department', 'form', 'fields', 'configs'],
    data() {
        return {
            // Modal state
            modal: {
                isOpen: false,
                data: {},      // data for the field being created/edited
                mode: '',      // 'CREATE' or 'EDIT'
            },
            // Table data
            formFields: null,
            isDraggable: false,
            labelCol: {
                style: { width: '150px' },
            },
        }
    },
    created() {
        this.formFields = this.fields;
    },
    computed: {
        columns() {
            return [
                { title: '拖拉', width: '60px', dataIndex: 'dragger' },
                { title: '欄位名稱', i18n: 'field_label', dataIndex: 'field_label' },
                { title: '欄位格式', i18n: 'field_type', dataIndex: 'type' },
                { title: '必填欄', i18n: 'compulsory', dataIndex: 'required' },
                { title: '顯示於輸出表格', i18n: 'in_column', dataIndex: 'in_column' },
                { title: '操作', i18n: 'operation', dataIndex: 'operation' }
            ]
        },
        isAdminOrMaster() {
            return this.is('admin | master');
        },
        optionTemplates() {
            return [
                { value: 'clear', label: 'Reset', template: [{ value: 'option_1', label: 'option_1' }] },
                {
                    value: 'agree', label: 'Level of Agreement', template: [
                        { value: 5, label: 'Strongly Agree' },
                        { value: 4, label: 'Argree' },
                        { value: 3, label: 'Neutual' },
                        { value: 2, label: 'Disagree' },
                        { value: 1, label: 'Strongly Disagree' },
                        { value: 0, label: 'Not Applicable' }
                    ]
                },
                { value: 'gender', label: 'Gender', template: [{ value: 'M', label: 'Male' }, { value: 'F', label: 'Female' }] },
                { value: 'department', label: 'Department', template: this.configs['departments'].map(d => ({ value: d.abbr, label: d.abbr + " " + d.name_zh })) },
                { value: 'faculty', label: 'Faculty', template: this.configs['faculties'].value },
            ];
        }
    },
    methods: {
        // ---- Modal control ----
        createRecord() {
            this.modal.data = {
                form_id: this.form.id,
                direction: 'V',
            };
            this.modal.mode = "CREATE";
            this.modal.isOpen = true;
        },
        editRecord(record) {
            // Clone the record and ensure options is an array for option-based types
            const data = { ...record };
            if (['radio', 'checkbox', 'dropdown'].includes(data.type) && !Array.isArray(data.options)) {
                data.options = [{ value: 'option_1', label: 'option_1' }];
            }
            this.modal.data = data;
            this.modal.mode = "EDIT";
            this.modal.isOpen = true;
        },
        // ---- Save/Update handlers ----
        handleSaveField(data) {
            this.$inertia.post(route('manage.form.fields.store', { form: this.form.id }), data, {
                onSuccess: () => {
                    this.modal.isOpen = false;
                },
                onError: (err) => console.log(err),
            });
        },
        handleUpdateField(data) {
            this.$inertia.patch(route('manage.form.fields.update', { form: this.form.id, field: data }), data, {
                onSuccess: () => {
                    this.modal.isOpen = false;
                },
                onError: (err) => console.log(err),
            });
        },
        // ---- Other actions ----
        cloneRecord(record) {
            this.$inertia.post(route('manage.form.field.clone', { formField: record.id }), {
                preserveState: false,
                onSuccess: () => {},
                onError: (err) => console.log(err),
            });
        },
        deleteRecord(record) {
            this.$inertia.delete(route('manage.form.fields.destroy', { form: this.form.id, field: record.id }), {
                preserveState: false,
                onSuccess: () => console.log('deleted'),
                onError: (err) => alert(err.message),
            });
        },
        reloadFormFields() {
            this.$inertia.reload({ only: ["fields"], onSuccess: () => { this.isDraggable = false; } });
        },
        // ---- Drag & drop ----
        customRow(record, index) {
            return {
                domProps: { draggable: this.isDraggable },
                style: { cursor: this.isDraggable ? 'move' : 'default' },
                onMouseenter: event => {
                    if (this.isDraggable) event.target.draggable = true;
                },
                onDragstart: event => {
                    if (this.isDraggable) {
                        event.stopPropagation();
                        this.sourceObj = record;
                    }
                },
                onDragover: event => {
                    if (this.isDraggable) event.preventDefault();
                },
                onDrop: event => {
                    if (this.isDraggable) {
                        event.stopPropagation();
                        this.targetObj = record;
                        let sourceIndex = '', targetIndex = '';
                        this.formFields.forEach((item, idx) => {
                            if (this.sourceObj == item) sourceIndex = idx;
                            if (this.targetObj == item) targetIndex = idx;
                        });
                        const arr = [];
                        this.formFields.forEach((item, idx) => {
                            if (sourceIndex == idx) arr.push(this.targetObj);
                            else if (targetIndex == idx) arr.push(this.sourceObj);
                            else arr.push(item);
                        });
                        arr.forEach((item, idx) => { arr[idx].sequence = idx; });
                        this.formFields = arr;
                        this.$inertia.post(route("manage.form.fieldsSequence", this.form.id), this.formFields, {
                            onSuccess: () => {},
                            onError: (err) => console.log(err),
                        });
                    }
                },
            }
        }
    },
}
</script>