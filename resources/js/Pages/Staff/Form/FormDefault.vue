<template>
    <StaffLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                表格管理
            </h2>
        </template>
            <div class="p-4 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg ">
                <div class="text-center">
                    <a-typography-title :level="3">{{ form.title }}</a-typography-title>
                </div>
                <div id="pure-html">
                    <div v-html="form.description" />
                </div>
            </div>
            <div class="mt-8 p-4 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg ">
                <div v-if="form.require_login">
                    <a-typography-title :level="4">
                        NetId: {{ $page.props.currentUser.username }}<br>
                        Email: {{ $page.props.currentUser.email }}
                    </a-typography-title>
                </div>
                <a-form
                    :model="formData"
                    ref="formRef"
                    name="default"
                    layout="vertical"
                    :validate-messages="validateMessages"
                >
                    <template v-for="field in form.fields">
                        <div v-if="form.require_member">
                            <a-form-item label="Member Id" :name="field.id" :rules="[{required:field.required}]">
                                <a-input v-model:value="$page.props.user.id" />
                            </a-form-item>                        
                        </div>
                        <div v-if="field.type=='input'">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]">
                                <a-input v-model:value="formData[field.id]" />
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='number'">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]">
                                <a-input-number v-model:value="formData[field.id]" />
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='select'">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]">
                                <a-select
                                    v-model:value="formData[field.id]"
                                    :options="JSON.parse(field.options)"
                                ></a-select>
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='radio'">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]">
                                <a-radio-group
                                    v-model:value="formData[field.id]"
                                    :options="JSON.parse(field.options)"
                                ></a-radio-group>
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='checkbox'">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]">
                                <a-checkbox-group
                                    v-model:value="formData[field.id]"
                                    :options="JSON.parse(field.options)"
                                ></a-checkbox-group>
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='textarea'">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]">
                                <a-textarea v-model:value="formData[field.id]" />
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='dropdown'">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]">
                                <a-select
                                    show-search
                                    v-model:value="formData[field.id]"
                                    :options="JSON.parse(field.options)"
                                    optionFilterProp="label"
                                />
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='richtext'">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]">
                                <quill-editor
                                    v-model:value="formData[field.id]"
                                    style="min-height:200px"
                                />
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='date'">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]" >
                                <a-date-picker v-model:value="formData[field.id]" :format="dateFormat" :valueFormat="dateFormat" />
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='email'">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required},{type:'email'}]" >
                                <a-input v-model:value="formData[field.id]" />
                            </a-form-item>                        
                        </div>
                        <div v-else>
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required},{type:'email'}]" >
                                <p>Data type undefined</p>
                            </a-form-item>                        
                        </div>
                    </template>
                    <div class="text-center pb-10">
                        <a-button @click="storeRecord" type="primary">遞交 Submit</a-button>
                    </div>
                    
                </a-form>
            </div>
    </StaffLayout>
</template>

<script>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import StaffLayout from '@/Layouts/StaffLayout.vue';
import { quillEditor } from 'vue3-quill';

export default {
    components: {
        MemberLayout,
        StaffLayout,
        quillEditor
    },
    props: ['form'],
    data() {
        return {
            formData:{

            },
            richText:'<p>Jose</p>',
            dateFormat:'YYYY-MM-DD',
            columns:[
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
            rules:{
                field:{required:true},
                label:{required:true},
            },
            validateMessages:{
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
    created(){
    },
    methods: {
        onFormChange(){
            console.log(this.formData);
        },
        storeRecord(){
            console.log(this.form);
            this.$refs.formRef.validateFields().then(()=>{
                this.$inertia.post(route('staff.forms.store'), {
                    form:this.form,
                    fields:this.formData
                },{
                    onSuccess:(page)=>{
                        this.formData={};
                    },
                    onError:(err)=>{
                        console.log(err);
                    }
                });
            }).catch(err => {
                console.log(err);
            });
        },

    },
}
</script>

<style scope>
.ant-form-vertical .ant-form-item-label{
    padding:0px !important;
}
#pure-html {
  all: initial
}

#pure-html * {
  all: revert;
}
</style>