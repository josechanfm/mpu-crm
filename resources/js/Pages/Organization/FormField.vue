<template>
    <OrganizationLayout title="Dashboard" :organization="organization">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Forms
            </h2>
        </template>
        <button @click="createRecord()"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Create Field</button>
            <a-table :dataSource="fields" :columns="columns">
                <template #bodyCell="{column, text, record, index}">
                    <template v-if="column.dataIndex=='operation'">
                        <a-button @click="editRecord(record)">Edit</a-button>
                        <a-button @click="deleteRecord(record)">Delete</a-button>
                    </template>
                    <template v-else>
                        {{record[column.dataIndex]}}
                    </template>
                </template>
            </a-table>

        <!-- Modal Start-->
        <a-modal v-model:visible="modal.isOpen" :title="modal.mode=='CREATE'?'Create Form':'Edit Form'" width="60%" >
            {{ modal.data }}
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
            <a-form-item label="Field Name" name="field_name">
                <a-input v-model:value="modal.data.field_name" />
            </a-form-item>
            <a-form-item label="Field Label" name="field_label">
                <a-input v-model:value="modal.data.field_label" />
            </a-form-item>
            <a-form-item label="Type" name="type">
                <a-input v-model:value="modal.data.type" />
            </a-form-item>
            <a-form-item label="Required" name="required">
                <a-input v-model:value="modal.data.required" />
            </a-form-item>
            <a-form-item label="Rule" name="rule">
                <a-input v-model:value="modal.data.rule" />
            </a-form-item>
            <a-form-item label="Validate" name="validate">
                <a-input v-model:value="modal.data.validate" />
            </a-form-item>
            <a-form-item label="Remark" name="remark">
                <a-textarea v-model:value="modal.data.remark" />
            </a-form-item>
        </a-form>
        <template #footer>
            <a-button v-if="modal.mode=='EDIT'" key="Update" type="primary"  @click="updateRecord()">Update</a-button>
            <a-button v-if="modal.mode=='CREATE'"  key="Store" type="primary" @click="storeRecord()">Add</a-button>
        </template>
    </a-modal>    
    <!-- Modal End-->
    </OrganizationLayout>

</template>

<script>
import OrganizationLayout from '@/Layouts/OrganizationLayout.vue';
import { defineComponent, reactive } from 'vue';

export default {
    components: {
        OrganizationLayout,
    },
    props: ['organization','form','fields'],
    data() {
        return {
            modal:{
                isOpen:false,
                data:{},
                title:"Modal",
                mode:""
            },
            columns:[
                {
                    title: 'Field Name',
                    dataIndex: 'field_name',
                },{
                    title: 'Field Label',
                    dataIndex: 'field_label',
                },{
                    title: 'Type',
                    dataIndex: 'type',
                },{
                    title: 'Required',
                    dataIndex: 'required',
                },{
                    title: 'Rule',
                    dataIndex: 'required',
                },{
                    title: 'Validate',
                    dataIndex: 'validate',
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
                this.$inertia.post(route('organization.form.fields.store',{
                    organization:this.organization.id,
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
                this.$inertia.patch(route('organization.form.fields.update', {
                    organization:this.organization.id,
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
            this.$inertia.delete(route('organization.form.fields.destroy', {
                organization:this.form.organization_id, form:this.form.id, field:record.id
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