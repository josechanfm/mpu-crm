<template>
    <DepartmentLayout title="Dashboard" :department="department">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                客戶服務管理
            </h2>
        </template>
        <a-typography-title :level="4">List of Enquiry Questions</a-typography-title>
        <a-table :dataSource="department.enquiry_questions_open" :columns="columns" :row-key="record => record.root_id">
            <template #bodyCell="{column, text, record, index}" >
                <template v-if="column.dataIndex=='operation'">
                    <a-button @click="viewRecord(record)">View</a-button>
                    <inertia-link :href="route('manage.enquiry.questions.show', { question:record.id})" class="ant-btn">Response</inertia-link>
                </template>
                <template v-else-if="column.dataIndex=='givenname'">
                    {{ record['enquiry']['givenname'] }}
                </template>
                <template v-else-if="column.dataIndex=='surname'">
                    {{ record['enquiry']['surname'] }}
                </template>
                <template v-else-if="column.dataIndex=='email'">
                    {{ record['enquiry']['email'] }} 
                </template>
                <template v-else-if="column.dataIndex=='phone'">
                    {{ record['enquiry']['areacode'] }} - {{ record['enquiry']['phone'] }}
                </template>
                <template v-else-if="column.dataIndex=='created_at'">
                    {{ dateFormat(record['created_at']) }}
                </template>
                <template v-else-if="column.dataIndex=='admin_user'">
                    {{ record.last_response.admin_user?record.last_response.admin_user.name:'---' }}
                </template>
                <!-- <template v-else-if="column.dataIndex=='escalated'">
                    {{ text==1?'Escalated':'--' }}
                </template> -->
                <template v-else>
                        {{record[column.dataIndex]}}
                </template>
            </template>
        </a-table>
        <p>Shows only enquiries with question and not yet closed.</p>

        <!-- Modal Start-->
        <a-modal v-model:visible="modal.isOpen" :title="modal.title" width="60%" >
        <a-form
            ref="modalRef"
            :model="modal.data"
            name="Teacher"
            :label-col="{ span: 8 }"
            :wrapper-col="{ span: 16 }"
            autocomplete="off"
        >
            <a-form-item :label="fields.origin.short">
                {{ optionFind(fields.origin.options,modal.data.enquiry.origin) }}
            </a-form-item>
            <a-form-item :label="fields.degree.short">
                {{ optionFind(fields.degree.options,modal.data.enquiry.degree) }}
            </a-form-item>
            <a-form-item :label="fields.admission.short">
                {{ optionFind(fields.admission.options,modal.data.enquiry.admission) }}
            </a-form-item>
            <a-form-item :label="fields.profile.short">
                {{ optionFind(fields.profile.options,modal.data.enquiry.profile) }}
            </a-form-item>
            <a-form-item :label="fields.surname.short">
                {{ modal.data.enquiry.surname }}
            </a-form-item>
            <a-form-item :label="fields.givenname.short">
                {{ modal.data.enquiry.givenname }}
            </a-form-item>
            <a-form-item :label="fields.email.short">
                {{ modal.data.enquiry.email }}
            </a-form-item>
            <a-form-item :label="fields.phone.short">
                {{ modal.data.enquiry.areacode }} - {{ modal.data.enquiry.phone }}
            </a-form-item>
            <a-form-item :label="fields.subjects.short">
                <span v-html="optionFind(fields.subjects.options,modal.data.enquiry.subjects)"/>
            </a-form-item>
            <a-form-item label="Question">
                {{ modal.data.question }}
            </a-form-item>
            <a-form-item label="Has question">
                {{ modal.data.has_question }}
            </a-form-item>
        </a-form>
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
import { defineComponent, reactive } from 'vue';
import dayjs from 'dayjs';

export default {
    components: {
        DepartmentLayout,
    },
    props: ['department','fields'],
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
                    title: this.fields.givenname.short,
                    dataIndex: 'givenname',
                    sorter: (a, b) => a.enquiry.givenname.localeCompare(b.enquiry.givenname)
                },{
                    title: this.fields.surname.short,
                    dataIndex: 'surname',
                    sorter: (a, b) => a.enquiry.surname.localeCompare(b.enquiry.surname),
                },{
                    title: this.fields.email.short,
                    dataIndex: 'email',
                    sorter: (a, b) => a.enquiry.email.localeCompare(b.enquiry.email),
                },{
                    title: this.fields.phone.short,
                    dataIndex: 'phone',
                    sorter: (a, b) => a.enquiry.phone.localeCompare(b.enquiry.phone),
                //},{
                //    title: 'Escalated',
                //    dataIndex: 'escalated'
                },{
                    title: '創建日期',
                    dataIndex: 'created_at',
                    sorter: (a, b) => {
                        return new Date(a.enquiry.created_at).getTime() > new Date(b.enquiry.created_at).getTime()
                        //a.enquiry.phone.localeCompare(b.enquiry.)
                    },
                },{
                    title: '提問',
                    dataIndex: 'content',
                },{
                    title: '最後回應',
                    dataIndex: 'admin_user',
                },{
                    title: '操作',
                    dataIndex: 'operation',
                    key: 'operation',
                },
            ],
        }
    },
    created(){
    },
    methods: {
        optionFind(options,item){
            if(Array.isArray(item)){
                var labels=options.filter(option=>item.includes(option.value))
                return labels.map(l=>l.label).join("<br>")
            }else{
                var label=options.find(option=>option.value==item)['label'].split(" ")
                return label[0]
            }
        },
        getOptionItem(options,item){
            var items=options.filter(option => {
                return item.includes(option['value']);
            });
            return items;
        },
        viewRecord(record){
            this.modal.data={...record};
            this.modal.mode="EDIT";
            this.modal.title="View";
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
        dateFormat(date, format = 'YY-MM-DD HH:mm') {
            return dayjs(date).format(format);
        },

    },
}
</script>