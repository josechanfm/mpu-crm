<template>
    <BlankLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                表格管理
            </h2>
        </template>
        <component :is="courseItem"/>
        <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg ">
            <a-form :model="formData" ref="formRef" name="default" layout="vertical"
                :validate-messages="validateMessages">
                <a-form-item :label="formFields['faculty'].field_label" :rules="[{ required: formFields['faculty'].required }]">
                    <a-select v-model:value="formData[formFields['faculty'].id]" :options="formFields['faculty'].options" />
                </a-form-item>
                <a-form-item :label="formFields['staff_num'].field_label" :rules="[{ required: formFields['staff_num'].required }]">
                    <a-input v-model:value="formData[formFields['staff_num'].id]" />
                </a-form-item>
                <a-form-item :label="formFields['fullname'].field_label" :rules="[{ required: formFields['fullname'].required }]">
                    <a-input v-model:value="formData[formFields['fullname'].id]" />
                </a-form-item>
                <div v-html="formFields['part_1'].extra" />
                <div>
                    <table>
                        <tr>
                            <th>課程類型（請說明）<br>Type of Training (Please indicate)</th>
                            <th>參加人數<br>No. of participants</th>
                            <th>最能配合工作的上課時間<br>The most convenient period</th>
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
                <div v-html="formFields['part_2'].extra" />
                <a-divider/>
                <div v-html="formFields['part_3'].extra" />
                    <table>
                            <tr>
                                <th width="400px"></th>
                                <th width="100px">小時<br>Hours</th>
                                <th width="200px">本人願意參加<br>I desire to participate</th>
                                <th>最適合本人的月份<br>The best month(s) for me</th>
                            </tr>
                            <tr>
                                <td><div v-html="getExtraContent(formFields['acquisition_1'].extra,'title')"/></td>
                                <td class="text-center"><div v-html="getExtraContent(formFields['acquisition_1'].extra,'hour')"/></td>
                                <td class="text-center"><a-checkbox v-model:checked="formData[formFields['acquisition_1'].id]" /></td>
                                <td><a-input v-model:value="formData[formFields['acquisition_2'].id]" /></td>
                            </tr>
                            <tr>
                                <td><div v-html="getExtraContent(formFields['budget_1'].extra,'title')"/></td>
                                <td class="text-center"><div v-html="getExtraContent(formFields['budget_1'].extra,'hour')"/></td>
                                <td class="text-center"><a-checkbox v-model:checked="formData[formFields['budget_1'].id]" /></td>
                                <td><a-input v-model:value="formData[formFields['budget_2'].id]" /></td>
                            </tr>
                            <tr>
                                <td><div v-html="getExtraContent(formFields['finance_1'].extra,'title')"/></td>
                                <td class="text-center"><div v-html="getExtraContent(formFields['finance_1'].extra,'hour')"/></td>
                                <td class="text-center"><a-checkbox v-model:checked="formData[formFields['finance_1'].id]" /></td>
                                <td><a-input v-model:value="formData[formFields['finance_2'].id]" /></td>
                            </tr>
                    </table>
                <div class="text-center pb-10">
                    <a-button @click="storeRecord" type="primary">遞交 Submit</a-button>
                </div>
            </a-form>
        </div>
    </BlankLayout>
</template>

<script>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import BlankLayout from '@/Layouts/BlankLayout.vue';
import { quillEditor } from 'vue3-quill';

export default {
    components: {
        MemberLayout,
        BlankLayout,
        quillEditor
    },
    props: ['form'],
    data() {
        return {
            formFields:{},
            formData: {

            },
            richText: '<p>Jose</p>',
            dateFormat: 'YYYY-MM-DD',
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
        Object.values(this.form.fields).forEach(f=>{
            this.formFields[f.field_name]=f
        })
        console.log(this.formFields);
    },
    methods: {
        onFormChange() {
            console.log(this.formData);
        },
        storeRecord() {
            this.$refs.formRef.validateFields().then(() => {
                this.$inertia.post(route('forms.store'), {
                    form: this.form,
                    fields: this.formData
                }, {
                    onSuccess: (page) => {
                        this.formData = {};
                    },
                    onError: (err) => {
                        console.log(err);
                    }
                });
            }).catch(err => {
                console.log(err);
            });
        },
        getExtraContent(extra, column){
            if(extra==null){
                return '---';
            }
            const option = JSON.parse(extra)
            return(option[column]);
        },
        courseItem(option){
            
        }

    },
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
</style>