<template>
    <DepartmentLayout :title="form.title || '欄位'" :department="department">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-gray-500">{{ form.name }}</p>
            <div class="flex flex-wrap gap-2">
                <a-button :href="route('manage.forms.index')">返回</a-button>
                <a-button v-if="isDraggable" type="primary" ghost @click="reloadFormFields">完成排序</a-button>
                <a-button v-else type="primary" ghost @click="isDraggable = true">拖拉排序</a-button>
                <a-button type="primary" :disabled="form.published == true" @click="createRecord()">新增欄位</a-button>
            </div>
        </div>
        <a-alert
            v-if="form.published"
            class="mb-3"
            type="warning"
            show-icon
            message="表單已發佈，欄位不能再新增、修改、刪除或複製。"
        />
        <a-table
            :dataSource="formFields"
            :columns="columns"
            :customRow="customRow"
            row-key="id"
            :pagination="false"
        >
            <template #bodyCell="{ column, record }">
                <template v-if="column.dataIndex === 'operation'">
                    <div class="flex flex-wrap gap-2">
                        <a-button size="small" :disabled="form.published == true" @click="editRecord(record)">編輯</a-button>
                        <a-popconfirm title="確定刪除這個欄位？" ok-text="刪除" cancel-text="取消"
                            @confirm="deleteRecord(record)" :disabled="form.published == true">
                            <a-button size="small" danger :disabled="form.published == true">刪除</a-button>
                        </a-popconfirm>
                        <a-popconfirm title="確定複製這個欄位？" ok-text="複製" cancel-text="取消"
                            @confirm="cloneRecord(record)" :disabled="form.published == true">
                            <a-button size="small" :disabled="form.published == true">複製</a-button>
                        </a-popconfirm>
                    </div>
                </template>
                <template v-else-if="column.dataIndex == 'type'">
                    {{ typeLabels[record.type] || record.type }}
                </template>
                <template v-else-if="column.dataIndex == 'dragger' && isDraggable">
                    <HolderOutlined />
                </template>
                <template v-else-if="column.dataIndex == 'required' || column.dataIndex == 'in_column'">
                    <CheckCircleFilled
                        v-if="record[column.dataIndex] == true"
                        class="text-lg text-green-500"
                        aria-label="Yes"
                    />
                    <CloseCircleFilled
                        v-else
                        class="text-lg text-red-400"
                        aria-label="No"
                    />
                </template>
            </template>
        </a-table>

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
import { CheckCircleFilled, CloseCircleFilled, HolderOutlined } from "@ant-design/icons-vue";
import Sortable from "sortablejs";
import FormFieldModal from './FormFieldModal.vue';

export default {
    components: {
        DepartmentLayout,
        draggable: VueDraggableNext,
        Sortable,
        HolderOutlined,
        CheckCircleFilled,
        CloseCircleFilled,
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
        typeLabels() {
            return {
                input: '單行文字',
                longtext: '長編文字',
                richtext: '格式文字',
                radio: '單項選擇',
                checkbox: '多項選擇',
                dropdown: '下拉清單',
                true_false: '真/偽',
                datetime: '日期時間',
                date: '日期格式',
                time: '時間',
                email: '電郵欄位',
                number: '數值欄位',
                html: 'HTML 文字',
            };
        },
        columns() {
            const columns = [
                { title: '欄位名稱', dataIndex: 'field_label' },
                { title: '欄位格式', dataIndex: 'type', width: 140 },
                { title: '必填欄', dataIndex: 'required', width: 90, align: 'center' },
                { title: '顯示於輸出表格', dataIndex: 'in_column', width: 140, align: 'center' },
                { title: '操作', dataIndex: 'operation', width: 220 },
            ];
            if (this.isDraggable) {
                columns.unshift({ title: '', dataIndex: 'dragger', width: 48 });
            }
            return columns;
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