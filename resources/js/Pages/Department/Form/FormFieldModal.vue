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

            <!-- Options editor (for radio, checkbox, dropdown) -->
            <template v-if="['radio', 'checkbox', 'dropdown'].includes(localData.type)">
                <div class="ant-form-item">
                    <div class="ant-form-item-label">
                        <label>Options</label>
                    </div>
                    <div class="ant-form-item-control">
                        <div class="ant-form-item-control-input">
                            <div class="flex flex-col gap-2">
                                <!-- Each option row with delete button -->
                                <a-form-item-rest v-for="(option, idx) in localData.options" :key="idx">
                                    <div class="flex items-center gap-2">
                                        <span class="min-w-[40px] text-right font-medium text-gray-600">
                                            <span v-if="option.value=='option_other'">其他.</span>
                                            <span v-else>{{ idx + 1 }}.</span>
                                        </span>
                                        <a-input 
                                            type="input" 
                                            v-model:value="option.label"
                                            placeholder="Option label"
                                            class="flex-1"
                                        />
                                        <a-button 
                                            type="text" 
                                            danger 
                                            size="small"
                                            @click="deleteOption(idx)"
                                            class="flex-shrink-0"
                                        >
                                            <CloseOutlined />
                                        </a-button>
                                    </div>
                                </a-form-item-rest>
                                <!-- Add buttons row -->
                                <div class="mt-1 flex flex-wrap items-center gap-2">
                                    <a-button type="primary" ghost @click="addOptionItem" class="flex-1">
                                        + Add option
                                    </a-button>
                                    <div class="flex items-center gap-1">
                                        <a-checkbox v-model:checked="hasOtherOption">
                                            Include "Other" option
                                        </a-checkbox>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500">"Other" option allows users to enter custom text</div>
                            </div>
                        </div>
                    </div>
                </div>

                <a-form-item label="Template" name="optionTemplate">
                    <a-select :options="optionTemplates" @change="onChangeOptionTemplate" />
                </a-form-item>
                <a-form-item 
                    v-if="['radio', 'checkbox'].includes(localData.type)"
                    label="Direction"
                >
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
import { CloseOutlined } from '@ant-design/icons-vue';

export default {
    components: {
        CloseOutlined,
    },
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
        };
    },
    computed: {
        hasOtherOption: {
            get() {
                return this.localData.options?.some(opt => opt.value === 'option_other') ?? false;
            },
            set(checked) {
                this.toggleOtherOption(checked);
            }
        }
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
                this.ensureOptionsArray();
            }
        },
        initialData: {
            deep: true,
            handler(newVal) {
                if (this.open && this.mode === 'EDIT') {
                    this.localData = this.cloneData(newVal);
                    this.ensureOptionsArray();
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
            this.sortOptions();
        },
        deleteOption(idx) {
            const removed = this.localData.options[idx];
            this.localData.options.splice(idx, 1);
            // If the deleted option was 'option_other', clean up its associated data
            if (removed && removed.value === 'option_other') {
                delete this.localData[this.localData.id + '_other'];
            }
            this.sortOptions();
        },
        toggleOtherOption(checked) {
            if (checked) {
                const exists = this.localData.options.some(opt => opt.value === 'option_other');
                if (!exists) {
                    this.localData.options.push({ value: 'option_other', label: '其他' });
                    this.sortOptions();
                } else {
                    this.$message?.warning('"Other" option already exists.');
                }
            } else {
                const index = this.localData.options.findIndex(opt => opt.value === 'option_other');
                if (index !== -1) {
                    this.localData.options.splice(index, 1);
                    delete this.localData[this.localData.id + '_other'];
                }
            }
        },
        sortOptions() {
            this.localData.options.sort((a, b) => {
                if (a.value === 'option_other') return 1;
                if (b.value === 'option_other') return -1;
                const numA = parseInt(a.value.replace('option_', ''), 10);
                const numB = parseInt(b.value.replace('option_', ''), 10);
                return numA - numB;
            });
        },
        onChangeOptionTemplate(value) {
            const template = this.optionTemplates.find(t => t.value == value);
            if (template) {
                this.localData.options = this.cloneData(template.template);
                this.sortOptions();
            }
        },
        onChangeType(value) {
            if (['textarea', 'longtext', 'richtext'].includes(value)) {
                if (!Number.isInteger(this.localData.options)) {
                    this.localData.options = 5;
                }
            } else if (['radio', 'checkbox', 'dropdown'].includes(value)) {
                if (!Array.isArray(this.localData.options)) {
                    this.localData.options = [{ value: 'option_1', label: 'option_1' }];
                }
            }
        },
        ensureOptionsArray() {
            const type = this.localData?.type;
            if (['radio', 'checkbox', 'dropdown'].includes(type) && !Array.isArray(this.localData.options)) {
                this.localData.options = [{ value: 'option_1', label: 'option_1' }];
            }
        },
    },
};
</script>