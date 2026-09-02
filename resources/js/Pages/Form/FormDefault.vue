<template>
    <WebLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                表格管理
            </h2>
        </template>
        
        <div class="mx-auto sm:px-6 lg:px-8">
            <!-- Alert Component -->
            <a-alert
                v-if="form.published==false"
                message="Your are in the form preview which is NOT YET PUBLISHED!"
                type="error"
                closable
            />
            <div class="mt-8 p-4 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="text-center font-bold">{{ form.title }}</div>
                <div v-if="form.banner">
                    <img :src="form.banner" style="width: 100%; height: auto;" alt="Banner Image"/>
                </div>
                <div class="mb-10" v-html="form.description"/>
                <a-form
                    :model="formData"
                    ref="formRef"
                    name="default"
                    layout="vertical"
                    :validate-messages="validateMessages"
                    @finish="onFinish"
                    @finishFailed="onFinishFailed"
                >
                    <template v-for="field in form.fields">
                        <template v-if="form.require_member" :key="field.id">
                            <a-form-item label="Member Id" :name="field.id" :rules="[{required:field.required, 'message':'必填欄位/Required'}]">
                                <a-input type="input" v-model:value="$page.props.user.id" />
                            </a-form-item>                        
                        </template>
                        <template v-if="field.type=='input'" :key="field.id">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required, 'message':'必填欄位/Required'}]">
                                <a-input type="input" v-model:value="formData[field.id]" />
                            </a-form-item>                        
                        </template>
                        <template v-else-if="field.type=='number'" >
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required, 'message':'必填欄位/Required'}]">
                                <a-input-number v-model:value="formData[field.id]" />
                            </a-form-item>                        
                        </template>
                        <template v-else-if="field.type=='select'" >
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required, 'message':'必填欄位/Required'}]">
                                <a-select
                                    v-model:value="formData[field.id]"
                                    :options="field.options"
                                ></a-select>
                            </a-form-item>                        
                        </template>
                        <template v-else-if="field.type=='radio'">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required, 'message':'必填欄位/Required'}]">
                                <div class="custom-option-group" :class="field.direction=='V' ? 'vertical' : ''">
                                    <label v-for="item in field.options" :key="item.value" class="custom-option">
                                        <input type="radio" :value="item.value" v-model="formData[field.id]" />
                                        <span class="custom-label">{{ item.label }}</span>
                                    </label>
                                </div>
                                <div v-if="formData[field.id] == 'option_other'" class="flex items-center gap-2 pl-2">
                                    <span class="ant-form-item-label" style="width: auto; padding: 0; font-weight: 500; white-space: nowrap;">
                                        請填寫/Please fill
                                    </span>
                                    <a-input type="input" v-model:value="formData[field.id + '_other']" class="flex-1" />
                                </div>

                                
                            </a-form-item>
                        </template>
                        <template v-else-if="field.type=='checkbox'" >
                            <a-form-item 
                                :label="field.field_label" 
                                :name="field.id" 
                                :rules="checkboxRules(field)"
                            >
                                <div class="custom-option-group" :class="field.direction=='V' ? 'vertical' : ''">
                                    <label v-for="item in field.options" :key="item.value" class="custom-option">
                                        <input 
                                            type="checkbox" 
                                            :value="item.value" 
                                            v-model="formData[field.id]"
                                        />
                                        <span class="custom-label">{{ item.label }}</span>
                                    </label>
                                </div>
                                <!-- "Other" text input -->
                                <div v-if="formData[field.id] && formData[field.id].includes('option_other')" class="flex items-center gap-2 pl-2">
                                    <span class="ant-form-item-label" style="width: auto; padding: 0; font-weight: 500; white-space: nowrap;">
                                        請填寫/Please fill
                                    </span>
                                    <a-input type="input" v-model:value="formData[field.id + '_other']" class="flex-1" />
                                </div>
                                <!-- Counter (visual feedback only) -->
                                <div v-if="field.extra?.option_max" class="text-xs mt-1">
                                    <span :class="formData[field.id]?.length >= parseInt(field.extra.option_max, 10) ? 'text-red-500' : 'text-gray-500'">
                                        已選/Already selected {{ formData[field.id]?.length || 0 }} /  {{ parseInt(field.extra.option_max, 10) }} 
                                    </span>
                                    <span v-if="formData[field.id]?.length >= parseInt(field.extra.option_max, 10)" class="text-red-500 ml-2">
                                        (已達上限/Max select)
                                    </span>
                                </div>
                            </a-form-item>
                        </template>
                        <template v-else-if="field.type=='dropdown'">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required, 'message':'必填欄位/Required'}]">
                                <a-select 
                                    v-model:value="formData[field.id]"
                                    :options="field.options"
                                />
                                <div v-if="formData[field.id] == 'option_other'" class="flex items-center gap-2 pl-2">
                                    <span class="ant-form-item-label" style="width: auto; padding: 0; font-weight: 500; white-space: nowrap;">
                                        請填寫/Please fill
                                        <!-- {{ field.options.find(o => o.value == 'option_other').label }} -->
                                    </span>
                                    <a-input type="input" v-model:value="formData[field.id + '_other']" class="flex-1" />
                                </div>

                            </a-form-item>                        
                        </template>
                        <template v-else-if="field.type=='longtext'" >
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required, 'message':'必填欄位/Required'}]">
                                <a-textarea v-model:value="formData[field.id]" />
                            </a-form-item>                        
                        </template>
                        <template v-else-if="field.type=='richtext'">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required, 'message':'必填欄位/Required'}]">
                                <quill-editor
                                    v-model="formData[field.id]"
                                    style="min-height:200px"
                                />
                            </a-form-item>                        
                        </template>
                        <template v-else-if="field.type=='datetime'">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required, 'message':'必填欄位/Required'}]" >
                                <a-date-picker v-model:value="formData[field.id]" :show-time="{ format: 'HH:mm' }" :format="dateTimeFormat" :valueFormat="dateTimeFormat" />
                            </a-form-item>                        
                        </template>
                        <template v-else-if="field.type=='date'" >
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required, 'message':'必填欄位/Required'}]" >
                                <a-date-picker v-model:value="formData[field.id]" :format="dateFormat" :valueFormat="dateFormat" />
                            </a-form-item>                        
                        </template>
                        <template v-else-if="field.type=='time'">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]" >
                                <a-date-picker v-model:value="formData[field.id]" picker="time" :show-time="{ format: 'HH:mm' }"  :format="timeFormat" :valueFormat="timeFormat" />
                            </a-form-item>                        
                        </template>
                        <template v-else-if="field.type=='email'">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required, 'message':'必填欄位/Required'},{type:'email'}]" >
                                <a-input type="input" v-model:value="formData[field.id]" />
                            </a-form-item>                        
                        </template>
                        <template v-else-if="field.type=='true_false'">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required, 'message':'必填欄位/Required'},{type:'boolean'}]" >
                                <a-checkbox v-model:checked="formData[field.id]" />
                            </a-form-item>                        
                        </template>
                        <template v-else-if="field.type=='html'" >
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required, 'message':'必填欄位/Required'},{type:'boolean'}]" >
                                <div v-html="field.extra"/>
                            </a-form-item>                        
                        </template>
                        <template v-else >
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required, 'message':'必填欄位/Required'}]" >
                                <p>Data type undefined</p>
                                {{ field }}
                            </a-form-item>                        
                        </template>
                    </template>
                    <div class="text-center pb-10">
                        <a-button type="primary" html-type="submit">遞交 Submit</a-button>
                    </div>
                    
                </a-form>
            </div>
        </div>
    </WebLayout>
</template>

<script>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import WebLayout from '@/Layouts/WebLayout.vue';
import QuillEditor from "@/Components/QuillEditor.vue";
export default {
    components: {
        MemberLayout,
        WebLayout,
        QuillEditor
    },
    props: ['form'],
    data() {
        return {
            formData: {},
            richText: '<p>Jose</p>',
            dateTimeFormat: 'YYYY-MM-DD HH:mm',
            dateFormat: 'YYYY-MM-DD',
            timeFormat: 'HH:mm',
            columns: [
                {
                    title: 'Name',
                    dataIndex: 'name',
                },{
                    title: 'Title',
                    dataIndex: 'title',
                },{
                    title: 'With Login',
                    dataIndex: 'with_login',
                },{
                    title: 'With Member',
                    dataIndex: 'with_member',
                },{
                    title: 'Action',
                    dataIndex: 'operation',
                    key: 'operation',
                },
            ],
            rules: {
                field: { required: true },
                label: { required: true },
            },
            validateMessages: {
                required: '${label}為必填欄位; ${label} is required!',
                types: {
                    email: '${label}不是有效電郵; ${label} is not a valid email!',
                    number: '${label}不是數字格式; ${label} is not a valid number!',
                },
                number: {
                    range: '${label} 必須介於 ${min} 至 ${max}; ${label} must be between ${min} and ${max}',
                },
            },
            labelCol: {
                style: {
                    width: '150px',
                },
            },
        }
    },
    created() {
        // Initialize formData with default values based on field type
        if (this.form && this.form.fields) {
            this.form.fields.forEach(field => {
                // Default value by type
                switch (field.type) {
                    case 'checkbox':
                        this.formData[field.id] = [];
                        break;
                    case 'radio':
                        this.formData[field.id] = [];
                    case 'select':
                    case 'dropdown':
                        this.formData[field.id] = null;
                        break;
                    case 'true_false':
                        this.formData[field.id] = false;
                        break;
                    default:
                        this.formData[field.id] = '';
                        break;
                }

                // If the field has an "Other" option, prepare the _other key
                // Safely check that options is an array before using .some()
                if (Array.isArray(field.options) && field.options.some(opt => opt.value === 'option_other')) {
                    this.formData[field.id + '_other'] = '';   // empty string, not null
                }
            });
        }
    },
    methods: {
        onFinish(values) {

            if(this.form.published == false){
                alert('Hold on! The for has NOT YET published, could not formally submitted!')
                return false;
            }
            
            this.$inertia.post(route('forms.store'), {
                formId: this.form.id,
                fields: this.formData
            }, {
                onSuccess: (page) => {
                    this.formData = {};
                    // Optionally reset after success
                },
                onError: (err) => {
                    console.log(err);
                }
            });
        },
        onFinishFailed(errorInfo) {
            this.$message.error('Required Field not missing!');
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        },
        checkboxRules(field) {
            const rules = [];
            if (field.required) {
                rules.push({ required: true, message: '必填欄位/Required' });
            }
            const max = field.extra?.option_max;
            if (max) {
                const intMax = parseInt(max, 10);
                if (!isNaN(intMax) && intMax > 0) {
                    rules.push({
                        validator: (rule, value) => {
                            if (!value) return Promise.resolve();
                            if (Array.isArray(value) && value.length > intMax) {
                                return Promise.reject(`最多選擇 ${intMax} 項`);
                            }
                            return Promise.resolve();
                        }
                    });
                }
            }
            return rules;
        }
    },
}
</script>

<style>
.ant-form-vertical .ant-form-item-label {
    padding: 0px !important;
}

/* ========== Custom Radio / Checkbox ========== */
.custom-option-group {
    display: flex;
    flex-wrap: wrap;
    gap: 8px 20px;
}

.custom-option-group.vertical {
    flex-direction: column;
    align-items: flex-start;
}

.custom-option {
    display: inline-flex;
    align-items: flex-start;
    cursor: pointer;
}

.custom-option input[type="radio"],
.custom-option input[type="checkbox"] {
    flex-shrink: 0;
    margin-top: 2px;
}

.custom-label {
    margin-left: 4px;
    white-space: normal;
    word-wrap: break-word;
    overflow-wrap: break-word;
    word-break: break-word;
}
</style>