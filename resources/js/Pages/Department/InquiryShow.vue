<template>
    <DepartmentLayout title="Dashboard" :department="department">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                客戶服務管理
            </h2>
        </template>
        <CommentBox :inquiries="inquiries" />

        <!-- Modal Start-->
        <a-modal v-model:visible="modal.isOpen" :title="modal.title" width="60%" >
        <a-form
            ref="modalRef"
            :model="modal.data"
            name="Teacher"
            :label-col="{ span: 8 }"
            :wrapper-col="{ span: 16 }"
            autocomplete="off"
            :rules="rules"
            :validate-messages="validateMessages"
        >
            <a-input type="hidden" v-model:value="modal.data.id"/>
            <a-form-item label="姓名(中文)" name="title">
                <a-input v-model:value="modal.data.title"/>
            </a-form-item>
            <a-form-item label="姓名(外文)" name="email">
                <a-input v-model:value="modal.data.email" />
            </a-form-item>
            <a-form-item label="別名" name="nickname">
                <a-input v-model:value="modal.data.phone" />
            </a-form-item>
            <a-form-item label="手機" name="content">
                <a-input v-model:value="modal.data.content" />
            </a-form-item>
        </a-form>
        <template #footer>
            <a-button v-if="modal.mode=='EDIT'" key="Update" type="primary"  @click="updateRecord()">Update</a-button>
            <a-button v-if="modal.mode=='CREATE'"  key="Store" type="primary" @click="storeRecord()">Add</a-button>
        </template>
    </a-modal>    
    <!-- Modal End-->



    
    </DepartmentLayout>

</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';
import CommentBox from '@/Components/Department/CommentBox.vue';

export default {
    components: {
        DepartmentLayout,
        CommentBox,
    },
    props: ['department','inquiries','inquiry'],
    data() {
        return {
            modal:{
                isOpen:false,
                data:{},
                title:"Modal",
                mode:""
            },
            teacherStateLabels:{},
            columns:[
                {
                    title: 'Id',
                    dataIndex: 'id',
                },{
                    title: 'Title',
                    dataIndex: 'title',
                },{
                    title: 'Email',
                    dataIndex: 'email',
                },{
                    title: 'Phone',
                    dataIndex: 'phone',
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
            this.modal.title="修改";
            this.modal.isOpen=true;
        },
        storeRecord(){
            this.$refs.modalRef.validateFields().then(()=>{
                this.$inertia.post('/admin/teachers/', this.modal.data,{
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
                this.$inertia.patch('/admin/teachers/' + this.modal.data.id, this.modal.data,{
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