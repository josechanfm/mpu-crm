<template>
    <MasterLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Department
            </h2>
        </template>
        <div class="container mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                Master dashboard
            </div>
        </div>

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