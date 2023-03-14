<template>
    <OrganizationLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Forms
            </h2>
        </template>
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
            <a-input type="hidden" v-model:value="modal.data.id"/>
            <a-form-item label="Field Name" name="field">
                <a-input v-model:value="modal.data.field" />
            </a-form-item>
            <a-form-item label="Field Label" name="label">
                <a-input v-model:value="modal.data.label" />
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
    props: ['form','fields'],
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
                    dataIndex: 'field',
                },{
                    title: 'Field Label',
                    dataIndex: 'label',
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
        editRecord(record){
            this.modal.data={...record};
            this.modal.mode="EDIT";
            this.modal.isOpen=true;
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