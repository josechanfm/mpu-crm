<template>
    <BlankLayout title="Survey">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                國家安全 National Security
            </h2>
        </template>
        <div class="bg-gray-100 p-5 dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
            <div id="pure-html">
                <div v-html="form.description"/>
            </div>
            <a-divider/>
            <a-form :model="formData" ref="formRef" name="default" layout="vertical"
                :validate-messages="validateMessages">
                <a-form-item
                    :label="formFields['department'].field_label" 
                    :name="formFields['department'].id" 
                    :rules="[{ required: formFields['department'].required, message:'為必填欄位 is Required'}]"
                >
                    <a-select 
                        v-model:value="formData[formFields['department'].id]" :options="formFields['department'].options" show-search
                    />
                </a-form-item>
                <a-form-item
                    :label="formFields['staff_no'].field_label" 
                    :name="formFields['staff_no'].id" 
                    :rules="[{ required: formFields['staff_no'].required, message:'為必填欄位 is Required'}]"
                >
                    <a-input v-model:value="formData[formFields['staff_no'].id]"/>
                </a-form-item>
                <a-form-item
                    :label="formFields['name_zh'].field_label" 
                    :name="formFields['name_zh'].id" 
                    :rules="[{ required: formFields['name_zh'].required, message:'為必填欄位 is Required'}]"
                >
                    <a-input v-model:value="formData[formFields['name_zh'].id]"/>
                </a-form-item>
                <a-form-item
                    :label="formFields['name_fn'].field_label" 
                    :name="formFields['name_fn'].id" 
                    :rules="[{ required: formFields['name_fn'].required, message:'為必填欄位 is Required'}]"
                >
                    <a-input v-model:value="formData[formFields['name_fn'].id]"/>
                </a-form-item>
                <a-form-item
                    :label="formFields['email'].field_label" 
                    :name="formFields['email'].id" 
                    :rules="[{ required: formFields['email'].required, type:'email', message:'為必電郵格式填欄位 is Required with Email format'}]"
                >
                    <a-input v-model:value="formData[formFields['email'].id]"/>
                </a-form-item>
                <a-form-item
                    :label="formFields['phone'].field_label" 
                    :name="formFields['phone'].id" 
                    :rules="[{ required: formFields['phone'].required, message:'為必填欄位 is Required'}]"
                >
                    <a-input v-model:value="formData[formFields['phone'].id]"/>
                </a-form-item>
                <a-form-item
                    :label="formFields['course_code'].field_label" 
                    :name="formFields['course_code'].id" 
                    :rules="[{ required: formFields['course_code'].required, message:'為必填欄位 is Required'}]"
                >
                    <a-radio-group v-model:value="formData[formFields['course_code'].id]">
                        <template v-for="course in JSON.parse(formFields['course_code']['extra'])">
                            <a-radio :style="radioStyle" :value="course.code" :disabled="vacancy(course.code)==0" >
                                {{ course.code }} ( {{ vacancy(course.code) }} Availables )
                            </a-radio>
                        </template>
                    </a-radio-group>
                </a-form-item>
                <div>
            <table>
                <tr>
                    <template v-for="(course, fieldName) in JSON.parse(formFields['course_code']['extra'])[0]">
                        <td>{{ fieldName[0].toUpperCase() }}{{ fieldName.slice(1) }}</td>
                    </template>
                </tr>
                <template v-for="course in JSON.parse(formFields['course_code']['extra'])">
                    <tr>
                        <template v-for="field in course">
                            <td>{{ field }}</td>
                        </template>
                    </tr>
                </template>
            </table>
        </div>
                <div class="text-center pt-10">
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
            courses:[
                {code:"C13",date:'13/10/2024',time:'9:30',quota:'32'},
                {code:"C14",date:'14/10/2024',time:'10:30',quota:'32'},
                {code:"C15",date:'15/10/2024',time:'11:30',quota:'32'},
                {code:"C16",date:'16/10/2024',time:'12:30',quota:'32'},
            ],
            richText: '<p>Jose</p>',
            dateFormat: 'YYYY-MM-DD',
            monthOptions:[...Array(12)].map((_, i) => ({
                value: (i + 1)
            })),
            radioStyle:{
                display: 'flex',
                height: '30px',
                lineHeight: '30px',
            },
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
        vacancy(courseCode){
            const courses=JSON.parse(this.formFields['course_code']['extra']);
            const quota=courses.find(c=>c.code==courseCode)['quota'];
            console.log(quota)
            const group= this.form.entry_groups.find(g=>g.field_value==courseCode);
            if(group){
                return  quota-group.count;
            }else{
                return quota;
            }

        }
    },
    computed:{
    }
}
</script>

<style scoped>
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

.limited-width {
  max-width: 100px;
  width: 100%; /* To ensure it takes the full width up to the max */
}

#pure-html {
  all: initial;
}
#pure-html * {
  all: revert;
}

</style>