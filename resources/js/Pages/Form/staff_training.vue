<template>
    <BlankLayout title="Survey">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                問卷調查 Questionnaire
            </h2>
        </template>
        
        <div class="bg-teal-50 p-5 dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg ">
            <div id="pure-html">
                <div v-html="form.description" style="text-align: center"/>
            </div>
            <a-form :model="formData" ref="formRef" name="default" layout="vertical"
                :validate-messages="validateMessages">
                <a-row :gutter="20">
                    <a-col :span="8">
                        <a-form-item 
                            :label="formFields['faculty'].field_label" 
                            :name="formFields['faculty'].id" 
                            :rules="[{ required: formFields['faculty'].required, message:'為必填欄位 is Required'}]"
                        >
                            <a-select v-model:value="formData[formFields['faculty'].id]" :options="formFields['faculty'].options" />
                        </a-form-item>
                    </a-col>
                    <a-col :span="8">
                        <a-form-item 
                            :label="formFields['staff_num'].field_label" 
                            :name="formFields['staff_num'].id"  
                            :rules="[{ required: formFields['staff_num'].required, message:'為必填欄位 is Required' }]"
                        >
                            <a-input v-model:value="formData[formFields['staff_num'].id]" />
                        </a-form-item>
                    </a-col>
                    <a-col :span="8">
                        <a-form-item 
                            :label="formFields['fullname'].field_label" 
                            :name="formFields['fullname'].id"
                            :rules="[{ required: formFields['fullname'].required, message:'為必填欄位 is Required'}]"
                        >
                            <a-input v-model:value="formData[formFields['fullname'].id]" />
                        </a-form-item>
                    </a-col>
                </a-row>
                <div v-html="formFields['note_1'].extra" />
                <a-divider/>
                <div v-html="formFields['part_1'].extra" />
                <div>
                    <table>
                        <tr>
                            <th><span v-html="formFields['part_1_1'].extra"/></th>
                            <th><span v-html="formFields['part_1_2'].extra"/></th>
                            <th><span v-html="formFields['part_1_3'].extra"/></th>
                        </tr>
                        <tr>
                            <td><a-input v-model:value="formData[formFields['part_1_1'].id]" /></td>
                            <td><a-input v-model:value="formData[formFields['part_1_2'].id]" /></td>
                            <td><a-input v-model:value="formData[formFields['part_1_3'].id]" /></td>
                        </tr>
                        <tr>
                            <td><a-input v-model:value="formData[formFields['part_2_1'].id]" /></td>
                            <td><a-input v-model:value="formData[formFields['part_2_2'].id]" /></td>
                            <td><a-input v-model:value="formData[formFields['part_2_3'].id]" /></td>
                        </tr>
                        <tr>
                            <td><a-input v-model:value="formData[formFields['part_3_1'].id]" /></td>
                            <td><a-input v-model:value="formData[formFields['part_3_2'].id]" /></td>
                            <td><a-input v-model:value="formData[formFields['part_3_3'].id]" /></td>
                        </tr>
                    </table>
                </div>
                <a-divider/>
                <div v-html="formFields['part_2'].extra" />
                    <table>
                            <tr>
                                <th width="400px"></th>
                                <th width="100px"><span v-html="getExtraContent(formFields['table_header'].extra,'hour')"/></th>
                                <th width="200px"><span v-html="getExtraContent(formFields['table_header'].extra,'participate')"/></th>
                                <th><span v-html="getExtraContent(formFields['table_header'].extra,'month')"/></th>
                            </tr>
                            <template v-for="i in questionCount">
                                <tr>
                                    <td><div v-html="getExtraContent(formFields['question_'+i+'_1'].extra,'title')" /></td>
                                    <td class="text-center"><div v-html="getExtraContent(formFields['question_'+i+'_1'].extra,'hour')" /></td>
                                    <td class="text-center">
                                        <a-checkbox v-model:checked="formData[formFields['question_'+i+'_1'].id]" /></td>
                                    <td>
                                        <a-select 
                                            v-model:value="formData[formFields['question_'+i+'_2'].id]" 
                                            mode="multiple"
                                            style="width: 100%" 
                                            :options="monthOptions"
                                        />
                                        <!-- <a-input v-model:value="formData[formFields['question_'+i+'_2'].id]" @input="checkInputMonthOnly"/> -->
                                    </td>
                                </tr>
                            </template>
                            <template v-for="i in suggestCount">
                                <tr>
                                    <td>
                                        <div v-html="getExtraContent(formFields['suggest_'+i+'_1'].extra,'title')" />
                                        <a-input v-model:value="formData[formFields['suggest_'+i+'_3'].id]" />
                                    </td>
                                    <td><a-input v-model:value="formData[formFields['suggest_'+i+'_4'].id]" /></td>
                                    <td class="text-center">
                                        <a-checkbox v-model:checked="formData[formFields['suggest_'+i+'_1'].id]" /></td>
                                    <td>
                                        <a-select 
                                            v-model:value="formData[formFields['suggest_'+i+'_2'].id]" 
                                            mode="multiple"
                                            style="width: 100%" 
                                            :options="monthOptions"
                                        />  
                                    </td>
                                </tr>
                            </template>
                    </table>

                <div class="text-center pt-10">
                    <a-button @click="storeRecord" type="primary">遞交 Submit</a-button>
                </div>
            </a-form>
        </div>
        <div v-html="formFields['note_2'].extra" />

    </BlankLayout>
</template>

<script>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import BlankLayout from '@/Layouts/BlankLayout.vue';
import { quillEditor } from 'vue3-quill';
import { message } from 'ant-design-vue';
import { CheckOutlined } from '@ant-design/icons-vue';

export default {
    components: {
        MemberLayout,
        BlankLayout,
        quillEditor
    },
    props: ['form'],
    data() {
        return {
            questionCount:0,
            suggestCount:0,
            formFields:{},
            formData: {

            },
            richText: '<p>Jose</p>',
            dateFormat: 'YYYY-MM-DD',
            monthOptions:[...Array(12)].map((_, i) => ({
                value: (i + 1),
            })),
            columns: [
                {
                    title: 'Name',
                    dataIndex: 'name',
                }, {
                    title: 'Title',
                    dataIndex: 'title',
                }, {
                    title: 'With Login',
                    dataIndex: 'with_login',
                }, {
                    title: 'With Member',
                    dataIndex: 'with_member',
                }, {
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
                required: '${label}為必填欄位;',
                types: {
                    email: '${label}不是有效電郵;',
                    number: '${label}不是數字格式;',
                },
                number: {
                    range: '${label} 必須介於 ${min} 至 ${max};',
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
        Object.values(this.form.fields).forEach(f=>{
            this.formFields[f.field_name]=f
        })
        this.questionCount=Object.keys(this.formFields).filter(f=>f.includes('question_')).length/2
        this.suggestCount=Math.ceil(Object.keys(this.formFields).filter(f=>f.includes('suggest_')).length/4)
        console.log(this.suggestCount);
    },
    methods: {
        storeRecord() {
            let data={};
            for(const key in this.formData){
                if(Array.isArray(this.formData[key])){
                    data[key]=this.formData[key].join(', ')
                }else{
                    data[key]=this.formData[key]
                }
            }

            this.$refs.formRef.validateFields().then(() => {
                this.$inertia.post(route('forms.store'), {
                    form: this.form,
                    fields: data
                }, {
                    onSuccess: (page) => {
                        this.formData = {};
                    },
                    onError: (err) => {
                        console.log(err);
                    }
                });
            }).catch(err => {
                message.error('必填欄位未填寫。\nRequired fields are not completed.');
                console.log(err);
            });
        },
        getExtraContent(extra,column){
            if(extra==null){
                return {title:'Title',hour:'Hour'}
            }
            try{
                const option =JSON.parse(extra)
                return option[column]
            }catch(err){
                console.log(extra);
                return {title:'Title',hour:'Hour'}
            }                     
        },
    },
    computed:{
    }
}
</script>

<style>
.ant-form-vertical .ant-form-item-label {
    padding: 0px !important;
}
table{
    width:100%;
    border-collapse: collapse;
}
table tr th, table tr td{
    border: 1px solid;
}
#pure-html {
  all: initial
}

#pure-html * {
  all: revert;
}
</style>