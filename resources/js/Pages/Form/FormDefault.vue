<template>
    <WebLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                表格管理
            </h2>
        </template>
        
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Component -->
            <a-alert
                v-if="form.published==false"
                message="Your are in the form preview which is not yet PUBLISHED!"
                type="error"
                closable
            />

            <div class="mt-8 p-4 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg ">
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
                        <div v-if="form.require_member" :key="field.id">
                            <a-form-item label="Member Id" :name="field.id" :rules="[{required:field.required}]">
                                <a-input type="inpuut" v-model:value="$page.props.user.id" />
                            </a-form-item>                        
                        </div>
                        <div v-if="field.type=='input'" :key="field.id">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]">
                                <a-input type="input" v-model:value="formData[field.id]" />
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='number'" :key="field.id">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]">
                                <a-input-number v-model:value="formData[field.id]" />
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='select'" :key="field.id">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]">
                                <a-select
                                    v-model:value="formData[field.id]"
                                    :options="field.options"
                                ></a-select>
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='radio'" :key="field.id">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]">
                                <a-radio-group v-model:value="formData[field.id]">
                                <template v-for="item in field.options" :key="item.value">
                                    <a-radio :style="field.direction=='V'?verticalStyle:null" :value="item.value">{{ item.label }}</a-radio>
                                </template>
                                </a-radio-group>
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='checkbox'" :key="field.id">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]">
                                <a-checkbox-group v-model:value="formData[field.id]" :class="field.direction=='V'?'checkbox-vertical':null">
                                    <template v-for="item in field.options" :key="item.value">
                                        <a-checkbox :style="verticalStyle" :value="item.value">{{ item.label }}</a-checkbox>
                                    </template>

                                </a-checkbox-group>
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='dropdown'" :key="field.id">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]">
                                <a-select 
                                    v-model:value="formData[field.id]"
                                    :options="field.options"
                                />
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='longtext'" :key="field.id">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]">
                                <a-textarea v-model:value="formData[field.id]" />
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='richtext'" :key="field.id">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]">
                                <quill-editor
                                    v-model="formData[field.id]"
                                    style="min-height:200px"
                                />
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='datetime'" :key="field.id">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]" >
                                <a-date-picker v-model:value="formData[field.id]" :show-time="{ format: 'HH:mm' }" :format="dateTimeFormat" :valueFormat="dateTimeFormat" />
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='date'" :key="field.id">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]" >
                                <a-date-picker v-model:value="formData[field.id]" :format="dateFormat" :valueFormat="dateFormat" />
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='time'" :key="field.id">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]" >
                                <a-date-picker v-model:value="formData[field.id]" picker="time" :show-time="{ format: 'HH:mm' }"  :format="timeFormat" :valueFormat="timeFormat" />
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='email'" :key="field.id">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required},{type:'email'}]" >
                                <a-input type="inpuut" v-model:value="formData[field.id]" />
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='true_false'" :key="field.id">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required},{type:'boolean'}]" >
                                <a-checkbox v-model:checked="formData[field.id]" />
                            </a-form-item>                        
                        </div>
                        <div v-else-if="field.type=='html'" :key="field.id">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required},{type:'boolean'}]" >
                                <div v-html="field.extra"/>
                            </a-form-item>                        
                        </div>
                        <div v-else :key="field.id">
                            <a-form-item :label="field.field_label" :name="field.id" :rules="[{required:field.required}]" >
                                <p>Data type undefined</p>
                                {{ field }}
                            </a-form-item>                        
                        </div>
                    </template>
                    <div class="text-center pb-10">
                        <!-- <a-button @click="storeRecord" type="primary">遞交 Submit</a-button> -->
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
            formData:{

            },
            richText:'<p>Jose</p>',
            dateTimeFormat:'YYYY-MM-DD HH:mm',
            dateFormat:'YYYY-MM-DD',
            timeFormat:'HH:mm',
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
            verticalStyle:{
                display: 'flex',
                height: '30px',
                lineHeight: '30px',
            }
        }
    },
    created(){
    },
    methods: {
        // onFormChange(){
        //     console.log('onFromchange',this.formData);
        // },
        // storeRecord(){
        //     console.log(this.form);
        //     console.log(this.formData);
        //     this.$refs.formRef.validateFields().then(()=>{
        //         this.$inertia.post(route('forms.store'), {
        //             form:this.form,
        //             fields:this.formData
        //         },{
        //             onSuccess:(page)=>{
        //                 this.formData={};
        //             },
        //             onError:(err)=>{
        //                 console.log(err);
        //             }
        //         });
        //     }).catch(err => {
        //         console.log(err);
        //     });
        // },
        onFinish(values){
            console.log('onFinish',values)
            // this.$inertia.post(route('forms.store'), {
            //     form:this.form,
            //     fields:this.formData
            // },{
            //     onSuccess:(page)=>{
            //         this.formData={};
            //     },
            //     onError:(err)=>{
            //         console.log(err);
            //     }
            // });

        },
        onFinishFailed(errorInfo ){
            this.$message.error('Required Field not missing!');
            window.scrollTo({
                top: 0,
                behavior: 'smooth' // Optional: smooth scroll
            });
        }


    },
}
</script>

<style>
.ant-form-vertical .ant-form-item-label{
    padding:0px !important;
}
.checkbox-vertical {
    display: block;
    margin-right: 0;
}
</style>