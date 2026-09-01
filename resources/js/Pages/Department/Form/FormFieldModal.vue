<template>
    <a-modal
        :open="open"
        @update:open="$emit('update:open', $event)"
        :title="mode == 'CREATE' ? 'Create' : 'Update'"
        width="60%"
    >
        <a-form ref="modalRef" :model="localData" name="formField" :label-col="{ span: 4 }"
                :wrapper-col="{ span: 20 }" autocomplete="off" :rules="rules" :validate-messages="validateMessages">
            <a-form-item v-if="isAdminOrMaster" label="Field Name" name="field_name">
                <a-input type="input" v-model:value="localData.field_name" />
                <p class="text-red-500 text-xs">* Only shows for admin and master, suggest to keep it blank</p>
            </a-form-item>
            <a-form-item label="Field Label" name="field_label">
                <a-input type="input" v-model:value="localData.field_label" />
            </a-form-item>
            <a-form-item label="Field Type" name="type">
                <a-select v-model:value="localData.type" placeholder="Field Type" :options="fieldTypes"
                          @change="onChangeType" />
            </a-form-item>
            <a-form-item label="Rows" name="rows"
                         v-if="['textarea', 'longtext', 'richtext'].includes(localData.type)">
                <a-input-number v-model:value="localData.options" />
            </a-form-item>
            <template v-if="['radio', 'checkbox', 'dropdown'].includes(localData.type)">
                <a-form-item label="Options" name="options">
                    <a-radio-group>
                        <template v-for="option in localData.options">
                            <a-radio :style="verticalStyle" :value="option.value">
                                <a-input type="input" v-model:value="option.label" />
                            </a-radio>
                        </template>
                        <a-radio @click="addOptionItem"> Add option</a-radio>
                    </a-radio-group>
                </a-form-item>
                <a-form-item label="Template" name="optionTemplate">
                    <a-select :options="optionTemplates" @change="onChangeOptionTemplate" />
                </a-form-item>
                <a-form-item label="Template" name="optionTemplate"
                             v-if="['radio', 'checkbox'].includes(localData.type)">
                    <a-radio-group v-model:value="localData.direction">
                        <a-radio value="H">Horizontal</a-radio>
                        <a-radio value="V">Vertical</a-radio>
                    </a-radio-group>
                </a-form-item>
            </template>
            <a-form-item label="Compulsory" name="required">
                <a-switch v-model:checked="localData.required" />
            </a-form-item>
            <a-form-item label="Column data" name="in_column" v-if="localData.required">
                <a-switch v-model:checked="localData.in_column" />
            </a-form-item>
            <a-form-item label="Extra">
                <a-textarea v-model:value="localData.extra" />
            </a-form-item>
            <a-form-item label="Remark" name="remark">
                <a-textarea v-model:value="localData.remark" />
            </a-form-item>
        </a-form>
        <template #footer>
            <a-button key="back" @click="closeModal">Cancel</a-button>
            <a-button v-if="mode == 'EDIT'" key="Update" type="primary"
                      @click="updateRecord">Update</a-button>
            <a-button v-if="mode == 'CREATE'" key="Store" type="primary"
                      @click="storeRecord">Create</a-button>
        </template>
    </a-modal>
</template>

<script>
export default {
    props: {
        open: {
            type: Boolean,
            required: true,
        },
        mode: {
            type: String,
            default: 'CREATE',
        },
        initialData: {
            type: Object,
            default: () => ({}),
        },
        formId: {
            type: Number,
            required: true,
        },
        isAdminOrMaster: {
            type: Boolean,
            default: false,
        },
        optionTemplates: {
            type: Array,
            default: () => [],
        },
    },
    emits: ['update:open', 'save', 'update'],
    data() {
        return {
            localData: {},
            fieldTypes: [
                { value: "input", label: "單行文字" },
                { value: "longtext", label: "長編文字" },
                { value: "richtext", label: "格式文字" },
                { value: "radio", label: "單項選擇" },
                { value: "checkbox", label: "多項選擇" },
                { value: "dropdown", label: "下拉清單" },
                { value: "true_false", label: "真/偽" },
                { value: "datetime", label: "日期時間" },
                { value: "date", label: "日期格式" },
                { value: "time", label: "時間" },
                { value: "email", label: "電郵欄位" },
                { value: "number", label: "數值欄位" },
                { value: "html", label: "HMTL text" }
            ],
            rules: {
                field_label: { required: true },
                type: { required: true },
            },
            validateMessages: {
                required: '${label} is required!',
                types: {
                    email: '${label} is not a valid email!',
                    number: '${label} is not a valid number!',
                },
                number: {
                    range: '${label} must be between ${min} and ${max}',
                },
            },
            verticalStyle: {
                display: 'flex',
                height: '30px',
                lineHeight: '30px',
                width: '100%'
            },
        };
    },
    watch: {
        open(newVal) {
            if (newVal) {
                if (this.mode === 'CREATE') {
                    this.localData = {
                        form_id: this.formId,
                        direction: 'V',
                    };
                } else {
                    this.localData = this.cloneData(this.initialData);
                }
            }
        },
        initialData: {
            deep: true,
            handler(newVal) {
                if (this.open && this.mode === 'EDIT') {
                    this.localData = this.cloneData(newVal);
                }
            },
        },
    },
    methods: {
        cloneData(obj) {
            return JSON.parse(JSON.stringify(obj));
        },
        closeModal() {
            this.$emit('update:open', false);
        },
        storeRecord() {
            this.$refs.modalRef.validateFields().then(() => {
                this.$emit('save', this.localData);
            }).catch(err => {
                console.log(err);
            });
        },
        updateRecord() {
            this.$refs.modalRef.validateFields().then(() => {
                this.$emit('update', this.localData);
            }).catch(err => {
                console.log(err);
            });
        },
        addOptionItem() {
            const newOption = 'option_' + (this.localData.options.length + 1);
            this.localData.options.push({ value: newOption, label: newOption });
        },
        onChangeOptionTemplate(value) {
            const template = this.optionTemplates.find(t => t.value == value);
            if (template) {
                this.localData.options = this.cloneData(template.template);
            }
        },
        onChangeType(value) {
            if (['textarea', 'longtext', 'richtext'].includes(value)) {
                if (!Number.isInteger(this.localData.options)) {
                    this.localData.options = 5;
                }
            } else if (['radio', 'checkbox', 'dropdown'].includes(value)) {
                if (typeof this.localData.options !== 'object' || !Array.isArray(this.localData.options)) {
                    this.localData.options = [{ value: 'option_1', label: 'option_1' }];
                }
            }
        },
    },
};
</script>