<template>
    <MasterLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Medias
            </h2>
        </template>
        <div class="container mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table :dataSource="medias" :columns="columns" :row-key="record => record.root_id">
                    <template #bodyCell="{column, text, record, index}" >
                        <template v-if="column.dataIndex=='operation'">
                            <a-button @click="editRecord(record)">Edit</a-button>
                            <!-- <inertia-link :href="route('manage.department.faqs.show', {department:record.department_id, faq:record.id})">View</inertia-link> -->
                        </template>
                        <template v-else-if="column.dataIndex=='thumbnail'">
                            <a :href="'/media/enquiry/'+record.id+'/'+record.file_name" target="_blank">
                                <template v-if="record.mime_type.includes('image/')">
                                    <img :src="'/media/enquiry/'+record.id+'/'+record.file_name" width="50px"/>
                                </template>
                                <template v-else>
                                    {{ record.file_name }}
                                </template>
                            </a>
                        </template>
                        <template v-else>
                            {{record[column.dataIndex]}}
                        </template>
                    </template>
                </a-table>
            </div>
        </div>


        <!-- Modal Start-->
        <a-modal v-model:open="modal.isOpen" :title="modal.title" width="60%" >
        <a-form
            ref="modalRef"
            :model="modal.data"
            name="Teacher"
            layout="vertical"
            autocomplete="off"
            :ruels="rules"
            :validate-messages="validateMessages"
        >
            <a-form-item label="Category Code" name="category_code">
                <a-select v-model:value="modal.data.category_code" :options="article_categories.value"/>
            </a-form-item>
            <a-form-item label="Department" name="department_id" v-if="modal.data.category_code=='DEPARTMENT'">
                <a-select v-model:value="modal.data.department_id" :options="departments" :fieldNames="{value:'id',label:'abbr'}"/>
            </a-form-item>
            <a-form-item label="Language" name="lang">
                <a-radio-group v-model:value="modal.data.lang" button-style="solid">
                    <a-radio-button value="zh">Chinese</a-radio-button>
                    <a-radio-button value="en">English</a-radio-button>
                    <a-radio-button value="pt">Portuguese</a-radio-button>
                </a-radio-group>
            </a-form-item>
            <a-form-item label="title" name="title">
                <a-input type="inpuut" v-model:value="modal.data.title"/>
            </a-form-item>
            <a-form-item label="Body" name="body">
                <a-textarea v-model:value="modal.data.body"/>
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
    props: ['article_categories','departments','medias'],
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
                    title: 'Collection',
                    dataIndex: 'collection_name',
                },{
                    title: 'Thumbnail',
                    dataIndex: 'thumbnail',
                },{
                    title: '操作',
                    dataIndex: 'operation',
                    key: 'operation',
                },
            ],
            rules:{
                lang:{required:true},
                name_zh:{required:true},
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
    methods: {
        createRecord(){
            this.modal.data={};
            this.modal.data.lang='zh'
            //this.modal.data.department_id = [];
            this.modal.mode="CREATE";
            this.modal.title="新增";
            this.modal.isOpen=true;
        },
        editRecord(record){
            this.modal.data={...record}
            this.modal.data.lang='zh'
            //this.modal.data.departments=record.departments.map(department=>({value:department.id,label:department.abbr_zh}));
            this.modal.mode="EDIT"
            this.modal.title="修改"
            this.modal.isOpen=true
        },
        storeRecord(){
            console.log(this.modal.data);
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
            console.log(this.modal.data);
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