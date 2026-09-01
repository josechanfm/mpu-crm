<template>
    <DepartmentLayout title="Dashboard" :department="department">
        <div class="flex pb-3 justify-end gap-2">
            <a-button :href="route('manage.forms.index')">Back</a-button>
            <a-button v-if="isDraggable" type="primary" ghost @click="reloadFormFields">Finish</a-button>
            <a-button v-else type="primary" ghost @click="isDraggable = !isDraggable">Drag sort</a-button>
            <a-button @click="createRecord()" type="primary">Create Field</a-button>
        </div>
        <a-table 
            :dataSource="dataModel" 
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
import { defineComponent, reactive } from 'vue';
import { VueDraggableNext } from 'vue-draggable-next'
import { HolderOutlined } from "@ant-design/icons-vue";
import Sortable from "sortablejs";
import FormFieldModal from './FormFieldModal.vue';   // <-- new import

export default {
    components: {
        DepartmentLayout,
        draggable: VueDraggableNext,
        Sortable,
        HolderOutlined,
        FormFieldModal,   // <-- registered
    },
    props: ['department', 'form', 'fields','configs'],
    data() {
        return {
            modal: {
                isOpen: false,
                data: {},
                title: "Modal",
                mode: ""
            },
            dataModel: null,
            isDraggable: false,
            // labelCol is still used in the child, but moved there; we can keep or remove
            // Keep only data that is still used in parent
            labelCol: {
                style: {
                    width: '150px',
                },
            },
        }
    },
    created() {
        this.dataModel = this.fields
    },
    computed: {
        columns() {
            return [
                {
                    title: '拖拉',
                    width: '60px',
                    dataIndex: 'dragger',
                }, {
                    title: '欄位名稱',
                    i18n: 'field_label',
                    dataIndex: 'field_label',
                }, {
                    title: '欄位格式',
                    i18n: 'field_type',
                    dataIndex: 'type',
                }, {
                    title: '必填欄',
                    i18n: 'compulsory',
                    dataIndex: 'required',
                }, {
                    title: '顯示於輸出表格',
                    i18n: 'in_column',
                    dataIndex: 'in_column',
                }, {
                    title: '操作',
                    i18n: 'operation',
                    dataIndex: 'operation'
                }
            ]
        },
        // Permission check – adapt to your actual implementation
        isAdminOrMaster() {
            // Replace with your own logic, e.g.:
            // return this.$page.props.auth.user.isAdmin || this.$page.props.auth.user.isMaster;
            return this.is('admin | master'); // assuming this method exists
        },
        optionTemplates() {
            // Moved from data to computed to react to configs changes
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
            this.modal.data = {};
            this.modal.data.form_id = this.form.id;
            this.modal.data.direction = 'V';
            this.modal.mode = "CREATE";
            this.modal.isOpen = true;
        },
        editRecord(record) {
            this.modal.data = { ...record };
            // Ensure options is an array
            try {
                this.modal.data.options = this.modal.data.options;
            } catch (e) {
                this.modal.data.options = [{ value: 'option_1', label: 'option_1' }];
            }
            this.modal.mode = "EDIT";
            this.modal.isOpen = true;
        },
        // ---- Save/Update handlers (called by modal) ----
        handleSaveField(data) {
            this.$inertia.post(route('manage.form.fields.store', { form: this.form.id }), data, {
                onSuccess: (page) => {
                    this.modal.isOpen = false;
                    // Optionally reload or update list (fields are refreshed by Inertia)
                },
                onError: (err) => {
                    console.log(err);
                }
            });
        },
        handleUpdateField(data) {
            this.$inertia.patch(route('manage.form.fields.update', { form: this.form.id, field: data }), data, {
                onSuccess: (page) => {
                    this.modal.isOpen = false;
                    console.log(page);
                },
                onError: (error) => {
                    console.log(error);
                }
            });
        },
        // ---- Other record actions ----
        cloneRecord(record) {
            this.$inertia.post(route('manage.form.field.clone', { formField: record.id }), {
                preserveState: false,
                onSuccess: (page) => {
                    // page refreshed
                },
                onError: (err) => {
                    console.log(err);
                }
            });
        },
        deleteRecord(record) {
            this.$inertia.delete(route('manage.form.fields.destroy', {
                form: this.form.id, field: record.id
            }), {
                preserveState: false,
                onSuccess: (page) => {
                    console.log('the field has been deleted!');
                },
                onError: (error) => {
                    alert(error.message);
                }
            });
        },
        // ---- Drag & drop ----
        rowChange(event) {
            var data = []
            this.fields.forEach((field, idx) => {
                data.push({ id: field.id, form_id: field.form_id, sequence: idx })
            })
            console.log(data);
            this.$inertia.post(route("manage.form.fieldsSequence", this.form.id), data, {
                onSuccess: (page) => {
                    console.log(page);
                },
                onError: (error) => {
                    console.log(error);
                }
            });
        },
        reloadFormFields() {
            this.$inertia.reload({
                only: ["fields"],
                onSuccess: (page) => {
                    this.isDraggable = false;
                },
            });
        },
        customRow(record, index) {
            return {
                domProps: {
                    draggable: this.isDraggable
                },
                style: {
                    cursor: this.isDraggable ? 'move' : 'default'
                },
                onMouseenter: event => {
                    if (this.isDraggable) {
                        var ev = event || window.event
                        ev.target.draggable = true
                    }
                },
                onDragstart: event => {
                    if (this.isDraggable) {
                        var ev = event || window.event
                        ev.stopPropagation()
                        this.sourceObj = record
                    }
                },
                onDragover: event => {
                    if (this.isDraggable) {
                        var ev = event || window.event
                        ev.preventDefault()
                    }
                },
                onDrop: event => {
                    if (this.isDraggable) {
                        var ev = event || window.event
                        ev.stopPropagation()
                        this.targetObj = record
                        let sourceIndex = ''
                        let targetIndex = ''
                        this.dataModel.map((item, idx) => {
                            if (this.sourceObj == item) {
                                sourceIndex = idx
                            }
                            if (this.targetObj == item) {
                                targetIndex = idx
                            }
                        })
                        let arr = []
                        this.dataModel.map((item, idx) => {
                            if (sourceIndex == idx) {
                                arr.push(this.targetObj)
                            } else if (targetIndex == idx) {
                                arr.push(this.sourceObj)
                            } else {
                                arr.push(item)
                            }
                        })
                        arr.map((item, idx) => {
                            arr[idx].sequence = idx
                        })
                        this.dataModel = arr
                        this.$inertia.post(route("manage.form.fieldsSequence", this.form.id), this.dataModel, {
                            onSuccess: (page) => {
                                console.log(page);
                            },
                            onError: (error) => {
                                console.log(error);
                            },
                        });
                    }
                },
            }
        }
    },
    watch: {
        // no longer needed for modal type changes – moved to child
    }
}
</script>

<style scoped>
</style>