<template>
    <MasterLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Department
            </h2>
        </template>
        <a-table :dataSource="adminUsers" :columns="columns" :row-key="record => record.root_id">
            <template #bodyCell="{column, text, record, index}" >
                <template v-if="column.dataIndex=='operation'">
                    <a-button @click="editRecord(record)">Edit</a-button>
                    <!-- <inertia-link :href="route('manage.department.faqs.show', {department:record.department_id, faq:record.id})">View</inertia-link> -->
                </template>
                <template v-else-if="column.dataIndex=='departments'">
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
            {{ modal.data }}
        <a-form
            ref="modalRef"
            :model="modal.data"
            name="Teacher"
            layout="vertical"
            autocomplete="off"
            :rules="rules"
            :validate-messages="validateMessages"
        >
            <a-form-item label="Name" name="name" :rules="{required:true}">
                <a-input v-model:value="modal.data.name"                 />
            </a-form-item>
            <a-form-item label="Email" name="email" :rules="{required:true}">
                <a-input v-model:value="modal.data.email"/>
            </a-form-item>
            <a-form-item label="Departments" name="departments" :rules="{required:true}">
                <a-select 
                    mode="multiple"
                    v-model:value="modal.data.departments" 
                    :options="departments" 
                    :fieldNames="{value:'id',label:'abbr_zh'}"
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
    props: ['departments','adminUsers'],
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
                    title: 'Name',
                    dataIndex: 'name',
                },{
                    title: 'Email',
                    dataIndex: 'email',
                },{
                    title: 'Departments',
                    dataIndex: 'departments',
                },{
                    title: '操作',
                    dataIndex: 'operation',
                    key: 'operation',
                },
            ],

        }
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
            this.modal.data.departments=record.departments.map(department=>({value:department.id,label:department.abbr_zh}))
            this.modal.mode="EDIT";
            this.modal.title="修改";
            this.modal.isOpen=true;
        },
        storeRecord(){
            console.log(this.modal.data);
            this.$refs.modalRef.validateFields().then(()=>{
                this.$inertia.post(route('master.adminUser'),this.modal.data, {
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
                this.$inertia.patch(route('manage.department.faqs.update',{department:this.department.id, faq:this.modal.data.id}),this.modal.data, {
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