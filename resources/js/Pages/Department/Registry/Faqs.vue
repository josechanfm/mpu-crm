<template>
    <DepartmentLayout title="常見問題" :breadcrumb="breadcrumb">
        <a-button @click="createRecord">新增常見問題</a-button>
        <div class="mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table 
                    :dataSource="department.faqs" 
                    :columns="columns" 
                    :rowKey=" record  => record.id"
                    @change="handleTableChange"
                >
                    <template #bodyCell="{column, text, record, index}" >
                        <template v-if="column.dataIndex=='operation'">
                            <a-button @click="editRecord(record)">修改</a-button>
                        </template>
                        <template v-else-if="column.dataIndex=='origins'">
                            <div v-html="gatherLables(fields.origin.options,text)"></div>
                        </template>
                        <template v-else-if="column.dataIndex=='degrees'">
                            <div v-html="gatherLables(fields.degree.options,text)"></div>
                        </template>
                        <template v-else-if="column.dataIndex=='subjects'">
                            <div v-html="gatherLables(fields.subjects.options,text)"/>
                        </template>
                        <template v-else-if="column.dataIndex=='created_at'">
                            {{ formatDate(record[column.dataIndex]) }}
                        </template>
                        <template v-else-if="column.dataIndex=='valid'">
                            {{ text?'有效':'無效' }}
                        </template>
                        <template v-else>
                            {{record[column.dataIndex]}}
                        </template>
                    </template>
                </a-table>
            </div>
        </div>

        <!-- Modal Start-->
        <a-modal v-model:visible="modal.isOpen" :title="modal.title" width="60%" >
        <a-form
            ref="modalRef"
            :model="modal.data"
            name="Teacher"
            layout="vertical"
            autocomplete="off"
            :rules="rules"
            :validate-messages="validateMessages"
        >
            <a-form-item label="所持證件" name="origins" :rules="{required:true}">
                <a-select 
                    mode="multiple"
                    v-model:value="modal.data.origins" 
                    :options="fields.origin.options" 
                />
            </a-form-item>
            <a-form-item label="課程類別" name="degrees" :rules="{required:true}">
                <a-select 
                    mode="multiple"
                    v-model:value="modal.data.degrees" 
                    :options="fields.degree.options" 
                />
            </a-form-item>

            <a-form-item label="問題主旨" name="subjects" :rules="{required:true}">
                <a-select 
                    mode="multiple"
                    v-model:value="modal.data.subjects" 
                    :options="fields.subjects.options" 
                />
            </a-form-item>
            <a-form-item label="回應標題" name="question_zh" :rules="{required:true}">
                <a-input v-model:value="modal.data.question_zh" />
            </a-form-item>
            <a-form-item label="回應內容" name="answer_zh" :rules="{required:true}">
                <quill-editor v-model:value="modal.data.answer_zh" style="min-height:200px;" />
            </a-form-item>
            <a-form-item label="有效/無效" name="valid" :rules="{required:true}">
                <a-switch v-model:checked="modal.data.valid" checked-children="有效" un-checked-children="無效" />
            </a-form-item>
        </a-form>
        <template #footer>
            <a-button v-if="modal.mode=='EDIT'" key="Update" type="primary"  @click="updateRecord()">更新</a-button>
            <a-button v-if="modal.mode=='CREATE'"  key="Store" type="primary" @click="storeRecord()">保存</a-button>
        </template>
    </a-modal>    
    <!-- Modal End-->
    </DepartmentLayout>

</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';
import { quillEditor } from 'vue3-quill';
import dayjs from 'dayjs';

export default {
    components: {
        DepartmentLayout,
        quillEditor
    },
    props: ['department','fields'],
    data() {
        return {
            breadcrumb:[
                {label:"招生注冊處" ,url:route('personnel.dashboard')},
                {label:"常見問題" ,url:null},
            ],
            dateFormat:'YY-MM-DD HH:mm',
            modal:{
                isOpen:false,
                data:{},
                title:"Modal",
                mode:""
            },
            teacherStateLabels:{},
            columns:[
                {
                    title: '所持證件',
                    dataIndex: 'origins',
                    filters:this.fields.origin.options,
                    filterMultiple:false,
                    onFilter:(value,record)=>record.origins.includes('CN')
                },{
                    title: '課程類別',
                    dataIndex: 'degrees',
                    filters:this.fields.degree.options,
                    filterMultiple:false,
                    onFilter:(value,record)=>record.degrees.includes(value)
                },{
                    title: '問題主旨',
                    dataIndex: 'subjects',
                    filters:this.fields.subjects.options,
                    filterMultiple:false,
                    onFilter:(value,record)=>record.subjects.includes(value)
                },{
                    title: '回應標題',
                    dataIndex: 'question_zh',
                    sorter:{
                        compare:(a,b)=>a.question_zh.localeCompare(b.question_zh)
                    }
                },{
                    title: '更新日期',
                    dataIndex: 'created_at',
                },{
                    title: '有效/無效',
                    dataIndex: 'valid',
                },{
                    title: '操作',
                    dataIndex: 'operation',
                    key: 'operation',
                },
            ],
            rules:{
                name_zh:{required:true},
                mobile:{required:true},
                state:{required:true},
            },
            validateMessages:{
                required: '${label} is required!',
                types: {
                    email: '${label} is not a valid email!',
                    number: '${label} is not a valid number!',
                },
                number: {
                    range: '${label} must be between ${min} and ${max}',
                },
            },
        }
    },
    created(){
        this.fields.origin.options.forEach(o=>o.text=o.label)
        this.fields.degree.options.forEach(o=>o.text=o.label)
        this.fields.subjects.options.forEach(o=>o.text=o.label)
    },
    methods: {
        createRecord(){
            this.modal.data={};
            this.modal.data.department_id = this.department.id;
            this.modal.data.category_id=1;
            this.modal.mode="CREATE";
            this.modal.title="新增";
            this.modal.isOpen=true;
        },
        editRecord(record){
            this.modal.data={...record};
            this.modal.mode="EDIT";
            this.modal.title="修改";
            this.modal.isOpen=true;
        },
        storeRecord(){
            this.$refs.modalRef.validateFields().then(()=>{
                this.$inertia.post(route('registry.faqs.store'),this.modal.data, {
                    onSuccess:(page)=>{
                        this.modal.data={};
                        this.modal.isOpen=false;
                    },
                    onError:(err)=>{
                        console.log(err);
                    }
                });
            }).catch(err => {
                console.log(err);
            });
        },
        updateRecord(){
            this.$refs.modalRef.validateFields().then(()=>{
                this.$inertia.patch(route('registry.faqs.update',{faq:this.modal.data.id}),this.modal.data, {
                    onSuccess:(page)=>{
                        this.modal.data={};
                        this.modal.isOpen=false;
                        console.log(page);
                    },
                    onError:(error)=>{
                        console.log(error);
                    }
                });
            }).catch(err => {
                console.log("error", err);
            });
        },
        gatherLables(options, items){
            if(items==null){
                return null;
            }
            return options.reduce((a,c)=>{
                if(items.includes(c.value)){
                    a+=c.label+"<br>"
                }
                return a
            }, '') 
        },
        formatDate(date, format = 'YYYY-MM-DD HH:mm') {
            return dayjs(date).format(format);
        },
        handleTableChange(pag,filters,sorter){
            //console.log('params', pag, filters, sorter);

        },

    },
}
</script>