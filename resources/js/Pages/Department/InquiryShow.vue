<template>
    <DepartmentLayout title="Dashboard" :department="department">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                客戶服務管理
            </h2>
        </template>
        
        <CommentCard :inquiries="inquiries" :selected="inquiry"  @emailBox="emailModal" @inquiryBox="inquiryModal"/>

        <!-- Modal Start-->
        <a-modal v-model:visible="modal.isOpen" :title="modal.title" :footer="false" width="60%" >
            <MailerBox v-if="modal.model=='email'" :email="modal.data"/>
            <InquiryBox  v-else-if="modal.model=='inquiry'" :inquiry="modal.data"/>
            <div v-else>
                <p>No Modal content available</p>
            </div>
        <!-- <template #footer>
            <a-button v-if="modal.mode=='EDIT'" key="Update" type="primary"  @click="updateRecord()">Update</a-button>
            <a-button v-if="modal.mode=='CREATE'"  key="Store" type="primary" @click="storeRecord()">Add</a-button>
        </template> -->
    </a-modal>    
    <!-- Modal End-->
   
    </DepartmentLayout>

</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';
import CommentCard from '@/Components/Department/CommentCard.vue';
import MailerBox from '@/Components/Department/MailerBox.vue';
import InquiryBox from '@/Components/Department/InquiryBox.vue';

export default {
    components: {
        DepartmentLayout,
        CommentCard,
        MailerBox,
        InquiryBox
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
        }
    },
    created(){
    },
    methods: {
        emailModal(record){
            console.log(record);
            this.modal.data={},
            this.modal.model='email',
            this.modal.data.sender='no-replay@mpu.edu.mo';
            this.modal.data.receiver=record.email;
            this.modal.data.subject=record.title;
            this.modal.data.content="your response content here";
            this.modal.mode="EDIT";
            this.modal.title="e-Mail composer";
            this.modal.isOpen=true;
        },
        inquiryModal(record){
            console.log(record);
            this.modal.data={},
            this.modal.model='inquiry',
            this.modal.data={...record}
            this.modal.mode="EDIT";
            this.modal.title="Inquiry";
            this.modal.isOpen=true;
        },
        // storeRecord(){
        //     this.$refs.modalRef.validateFields().then(()=>{
        //         this.$inertia.post('/admin/teachers/', this.modal.data,{
        //             onSuccess:(page)=>{
        //                 this.modal.data={};
        //                 this.modal.isOpen=false;
        //             },
        //             onError:(err)=>{
        //                 console.log(err);
        //             }
        //         });
        //     }).catch(err => {
        //         console.log(err);
        //     });
        // },
        // updateRecord(){
        //     console.log(this.modal.data);
        //     this.$refs.modalRef.validateFields().then(()=>{
        //         this.$inertia.patch('/admin/teachers/' + this.modal.data.id, this.modal.data,{
        //             onSuccess:(page)=>{
        //                 this.modal.data={};
        //                 this.modal.isOpen=false;
        //                 console.log(page);
        //             },
        //             onError:(error)=>{
        //                 console.log(error);
        //             }
        //         });
        //     }).catch(err => {
        //         console.log("error", err);
        //     });
           
        // },
    },
}
</script>