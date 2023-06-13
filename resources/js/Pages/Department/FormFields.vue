<template>
    <DepartmentLayout title="Dashboard" :department="department">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                資料欄位管理
            </h2>
        </template>
        <button @click="createRecord()"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">新增資料欄位</button>
            <a-table :dataSource="fields" :columns="columns">
                <template #bodyCell="{column, text, record, index}">
                    <template v-if="column.dataIndex=='operation'">
                        <a-button @click="editRecord(record)">修改</a-button>
                        <a-button @click="deleteRecord(record)">刪除</a-button>
                    </template>
                    <template v-else>
                        {{record[column.dataIndex]}}
                    </template>
                </template>
            </a-table>

        <!-- Modal Start-->
        <a-modal v-model:visible="modal.isOpen" :title="modal.mode=='CREATE'?'新增':'修改'" width="60%" >
        <a-form
            ref="modalRef"
            :model="modal.data"
            name="Teacher"
            :label-col="{ span: 4 }"
            :wrapper-col="{ span: 20 }"
            autocomplete="off"
            :rules="rules"
            :validate-messages="validateMessages"
        >
            <a-form-item label="名稱" name="field_name">
                <a-input v-model:value="modal.data.field_name" />
            </a-form-item>
            <a-form-item label="標簽" name="field_label">
                <a-input v-model:value="modal.data.field_label" />
            </a-form-item>
            <a-form-item label="類型" name="type">
                <a-select v-model:value="modal.data.type" placeholder="欄位類型" :options="fieldTypes"/>
            </a-form-item>
            <a-form-item label="必填" name="require">
                <a-switch v-model:checked="modal.data.require" :unCheckedValue="0" :checkedValue="1"/>
            </a-form-item>
            <!-- <a-form-item label="規則" name="rule">
                <a-input v-model:value="modal.data.rule" />
            </a-form-item>
            <a-form-item label="驗證" name="validate">
                <a-input v-model:value="modal.data.validate" />
            </a-form-item> -->
            <a-form-item label="備注" name="remark">
                <a-textarea v-model:value="modal.data.remark" />
            </a-form-item>
        </a-form>
        <template #footer>
            <a-button v-if="modal.mode=='EDIT'" key="Update" type="primary"  @click="updateRecord()">更新</a-button>
            <a-button v-if="modal.mode=='CREATE'"  key="Store" type="primary" @click="storeRecord()">新增</a-button>
        </template>
    </a-modal>    
    <!-- Modal End-->
    </DepartmentLayout>

</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';
import { defineComponent, reactive } from 'vue';

export default {
    components: {
        DepartmentLayout,
    },
    props: ['department','form','fields'],
    data() {
        return {
            modal:{
                isOpen:false,
                data:{},
                title:"Modal",
                mode:""
            },
            fieldTypes:[
                {value:"input",label:"單行文字"},
                {value:"textarea",label:"多行文字"},
                {value:"largetext",label:"大篇幅文字"},
                {value:"radio",label:"單選"},
                {value:"checkbox",label:"多選"},
                {value:"true_false",label:"是/否"},
                {value:"date",label:"日期"},
                {value:"datetime",label:"日期時間"},
                {value:"email",label:"電郵"},
                {value:"number",label:"數值"},
                {value:"richtext",label:"富文本格式"},
            ],
            columns:[
                {
                    title: '名稱',
                    dataIndex: 'field_name',
                },{
                    title: '標簽',
                    dataIndex: 'field_label',
                },{
                    title: '類型',
                    dataIndex: 'type',
                },{
                    title: '必填',
                    dataIndex: 'required',
                },{
                    title: '操作',
                    dataIndex: 'operation',
                    key: 'operation',
                },
            ],
            rules:{
                field:{required:true},
                label:{required:true},
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
        createRecord(){
            this.modal.data={};
            this.modal.data.form_id=this.form.id;
            this.modal.mode="CREATE";
            this.modal.isOpen=true;
        },
        editRecord(record){
            this.modal.data={...record};
            this.modal.mode="EDIT";
            this.modal.isOpen=true;
        },
        storeRecord(){
            this.$refs.modalRef.validateFields().then(()=>{
                this.$inertia.post(route('form.fields.store',{
                    form:this.form.id
                }), this.modal.data, {
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
            console.log(this.modal.data);
            this.$refs.modalRef.validateFields().then(()=>{
                this.$inertia.patch(route('form.fields.update', {
                    form:this.form.id,
                    field:this.modal.data
                }), this.modal.data, {
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
        deleteRecord(record){
            if (!confirm('Are you sure want to remove?')) return;
            this.$inertia.delete(route('form.fields.destroy', {
                form:this.form.id, field:record.id
            }),{
                onSuccess: (page)=>{
                    console.log('the field has been deleted!');
                },
                onError: (error)=>{
                    alert(error.message);
                }

            });
        },
    },
}
</script>
