<template>
    <a-modal
        :open="open"
        @update:open="$emit('update:open', $event)"
        :title="mode == 'CREATE' ? '新增欄位' : '編輯欄位'"
        :width="720"
    >
        <a-form ref="modalRef" :model="localData" name="formField" :label-col="{ span: 6 }"
                :wrapper-col="{ span: 18 }" autocomplete="off" :rules="rules" :validate-messages="validateMessages">
            <a-form-item label="欄位名稱" name="field_label">
                <a-input v-model:value="localData.field_label" />
            </a-form-item>
            <a-form-item label="欄位格式" name="type">
                <a-select v-model:value="localData.type" placeholder="選擇格式" :options="fieldTypes"
                          @change="onChangeType" />
            </a-form-item>
            <a-form-item v-if="isAdminOrMaster" label="內部名稱" name="field_name">
                <a-input v-model:value="localData.field_name" />
                <p class="mt-1 text-xs text-gray-500">只對管理員顯示，建議留空。</p>
            </a-form-item>
            <a-form-item label="行數" name="rows"
                         v-if="['textarea', 'longtext', 'richtext'].includes(localData.type)">
                <a-input-number v-model:value="localData.options" :min="1" />
            </a-form-item>

            <template v-if="['radio', 'checkbox', 'dropdown'].includes(localData.type)">
                <a-form-item label="選項範本">
                    <a-select :options="optionTemplates" placeholder="套用範本" @change="onChangeOptionTemplate" />
                </a-form-item>
                <a-form-item label="選項">
                    <div class="flex flex-col gap-2">
                        <a-form-item-rest v-for="(option, idx) in localData.options" :key="idx">
                            <div class="flex items-center gap-2">
                                <span class="w-10 shrink-0 text-right text-gray-500">
                                    {{ option.value == 'option_other' ? '其他' : (idx + 1) + '.' }}
                                </span>
                                <a-input v-model:value="option.label" placeholder="選項文字" class="flex-1" />
                                <a-button type="text" danger size="small" @click="deleteOption(idx)">
                                    <CloseOutlined />
                                </a-button>
                            </div>
                        </a-form-item-rest>
                        <div class="flex flex-wrap items-center gap-3">
                            <a-button type="primary" ghost @click="addOptionItem">新增選項</a-button>
                            <a-checkbox v-model:checked="hasOtherOption">加入「其他」，讓填表人自行輸入</a-checkbox>
                        </div>
                    </div>
                </a-form-item>
                <a-form-item v-if="['radio', 'checkbox'].includes(localData.type)" label="排列">
                    <a-radio-group v-model:value="localData.direction">
                        <a-radio value="H">橫向</a-radio>
                        <a-radio value="V">直向</a-radio>
                    </a-radio-group>
                </a-form-item>
                <a-form-item v-if="localData.type === 'checkbox'" label="最多可選">
                    <a-input-number class="w-28" :min="1" :value="optionMax" placeholder="不限" @change="setOptionMax" />
                    <span class="ml-2 text-xs text-gray-500">留空表示不限制</span>
                </a-form-item>
            </template>

            <a-form-item label="必填欄" name="required">
                <a-switch v-model:checked="localData.required" />
                <span class="ml-2 text-xs text-gray-500">填表時必須填寫</span>
            </a-form-item>
            <a-form-item label="顯示於輸出表格" name="in_column">
                <a-switch v-model:checked="localData.in_column" />
                <span class="ml-2 text-xs text-gray-500">在回覆列表和匯出中顯示</span>
            </a-form-item>
            <a-form-item label="備註" name="remark">
                <a-textarea v-model:value="localData.remark" :rows="3" />
            </a-form-item>
        </a-form>
        <template #footer>
            <a-button key="back" @click="closeModal">取消</a-button>
            <a-button v-if="mode == 'EDIT'" key="Update" type="primary"
                      @click="updateRecord">儲存</a-button>
            <a-button v-if="mode == 'CREATE'" key="Store" type="primary"
                      @click="storeRecord">建立</a-button>
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
                { value: "html", label: "HTML 文字" }
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
        optionMax() {
            const raw = this.localData.extra?.option_max;
            const count = parseInt(raw, 10);
            return Number.isFinite(count) && count > 0 ? count : null;
        },
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
                this.normalizeExtra();
            }
        },
        initialData: {
            deep: true,
            handler(newVal) {
                if (this.open && this.mode === 'EDIT') {
                    this.localData = this.cloneData(newVal);
                    this.ensureOptionsArray();
                    this.normalizeExtra();
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
        setOptionMax(value) {
            this.normalizeExtra();
            if (value == null || value === '') {
                delete this.localData.extra.option_max;
                return;
            }
            this.localData.extra.option_max = value;
        },
        normalizeExtra() {
            if (this.localData.type !== 'checkbox') return;
            if (!this.localData.extra || typeof this.localData.extra !== 'object' || Array.isArray(this.localData.extra)) {
                this.localData.extra = {};
            }
            const count = parseInt(this.localData.extra.option_max, 10);
            if (!Number.isFinite(count) || count < 1) {
                delete this.localData.extra.option_max;
            } else {
                this.localData.extra.option_max = count;
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
                this.normalizeExtra();
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