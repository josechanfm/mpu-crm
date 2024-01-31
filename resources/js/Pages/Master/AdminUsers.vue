<template>
    <MasterLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Admin users
            </h2>
        </template>
        <a-button @click="createRecord">Add Admin User</a-button>
        <a-table :dataSource="adminUsers" :columns="columns" :row-key="record => record.root_id">
            <template #bodyCell="{column, text, record, index}" >
                <template v-if="column.dataIndex=='operation'">
                    <a-button @click="editRecord(record)">Edit</a-button>
                    <!-- <inertia-link :href="route('manage.department.faqs.show', {department:record.department_id, faq:record.id})">View</inertia-link> -->
                </template>
                <template v-else-if="column.dataIndex=='manage_departments'">
                    <ol>
                        <li v-for="role in record.roles">{{ role.name }}</li>    
                    </ol>
                </template>
                <template v-else-if="column.dataIndex=='belong_departments'">
                    <ol>
                        <li v-for="department in record.departments">{{ department.name_zh }}</li>    
                    </ol>
                </template>
                <template v-else>
                    {{record[column.dataIndex]}}
                </template>
            </template>
        </a-table>


        <!-- Modal Start-->
        <a-modal v-model:visible="modal.isOpen" :title="modal.title" width="60%" >
        <a-form
            ref="modalRef"
            :model="modal.data"
            name="Teacher"
            layout="vertical"
            autocomplete="off"
            :ruels="rules"
            :validate-messages="validateMessages"
        >
            <a-form-item label="Username" name="username">
                <a-input v-model:value="modal.data.username"                 />
            </a-form-item>
            <a-form-item label="Display Name" name="name">
                <a-input v-model:value="modal.data.name"                 />
            </a-form-item>
            <a-form-item label="Email" name="email">
                <a-input v-model:value="modal.data.email"/>
            </a-form-item>
            <a-form-item label="Password" name="password">
                <a-input v-model:value="modal.data.password"/>
            </a-form-item>
            <a-form-item label="Retype Password" name="retype_password">
                <a-input v-model:value="modal.data.retype_password"/>
            </a-form-item>
            <a-form-item label="Departments" name="departments">
                <a-select 
                    mode="multiple"
                    v-model:value="modal.data.belong_departments" 
                    :options="departments" 
                    :fieldNames="{value:'abbr'}"
                />
            </a-form-item>
            <a-form-item label="Manage Departments" name="manage_departments">
                <a-select 
                    mode="multiple"
                    v-model:value="modal.data.manage_departments" 
                    :options="roles" 
                    :fieldNames="{value:'name'}"
                />
            </a-form-item>
        </a-form>
        <template #footer>
            <a-button v-if="modal.mode=='EDIT'" key="Update" type="primary"  @click="updateRecord()">Update</a-button>
            <a-button v-if="modal.mode=='CREATE'"  key="Store" type="primary" @click="storeRecord()">Add</a-button>
        </template>
    </a-modal>    
    <!-- Modal End-->

    </MasterLayout>
</template>

<script>
import MasterLayout from '@/Layouts/MasterLayout.vue';

export default {
    components: {
        MasterLayout
    },
    props: ['roles','departments','adminUsers'],
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
                    title: 'Username',
                    dataIndex: 'username',
                },{
                    title: 'Display Name',
                    dataIndex: 'name',
                },{
                    title: 'Email',
                    dataIndex: 'email',
                },{
                    title: 'Belong Departments',
                    dataIndex: 'belong_departments',
                },{
                    title: 'Manage Departments',
                    dataIndex: 'manage_departments',
                },{
                    title: '操作',
                    dataIndex: 'operation',
                    key: 'operation',
                },
            ],
            rules:{
                name:{required:true},
                email:{required:true},
                password:{required:true},
                retype_password:{required:true},
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
    },
    mounted(){
    },
    methods: {
        createRecord(){
            this.modal.data={};
            //this.modal.data.department_id = [];
            this.modal.mode="CREATE";
            this.modal.title="新增";
            this.modal.isOpen=true;
        },
        editRecord(record){
            this.modal.data={...record};
            this.modal.data.belong_departments=record.departments.map(department=>department.abbr);
            this.modal.data.manage_departments=record.roles.map(role=>role.name);
            this.modal.mode="EDIT";
            this.modal.title="修改";
            this.modal.isOpen=true;
        },
        storeRecord(){
            this.$refs.modalRef.validateFields().then(()=>{
                this.$inertia.post(route('master.adminUsers.store'),this.modal.data, {
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
                this.$inertia.patch(route('master.adminUsers.update',this.modal.data.id),this.modal.data, {
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
    },
}
</script>