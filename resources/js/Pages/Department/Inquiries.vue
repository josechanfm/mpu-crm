<template>
    <DepartmentLayout title="Dashboard" :department="department">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                客戶服務管理
            </h2>
        </template>
        List of Inquiry
        <a-table :dataSource="inquiries" :columns="columns" :row-key="record => record.root_id">
            <template #bodyCell="{column, text, record, index}" >
                <template v-if="column.dataIndex=='operation'">
                    <a-button @click="viewRecord(record)">View</a-button>
                    <inertia-link :href="route('manage.department.inquiries.show', {department:record.department_id, inquiry:record.id})">Response</inertia-link>
                </template>
                <template v-else-if="column.dataIndex=='phone'">
                    {{ record['areacode'] }} - {{ record['phone'] }}
                </template>
                <template v-else>
                    <span v-if="column.options">
                        {{ optionFind(column.options, text) }}
                    </span>
                    <span v-else>
                        {{record[column.dataIndex]}}
                    </span>
                </template>
            </template>
        </a-table>

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
                {{ optionFind(fields.origin.options,modal.data.origin) }}
            </a-form-item>
            <a-form-item :label="fields.degree.short">
                {{ optionFind(fields.degree.options,modal.data.degree) }}
            </a-form-item>
            <a-form-item :label="fields.admission.short">
                {{ optionFind(fields.admission.options,modal.data.admission) }}
            </a-form-item>
            <a-form-item :label="fields.profile.short">
                {{ optionFind(fields.profile.options,modal.data.profile) }}
            </a-form-item>

            <a-form-item :label="fields.surname.short">
                {{ modal.data.surname }}
            </a-form-item>
            <a-form-item :label="fields.givenname.short">
                {{ modal.data.givenname }}
            </a-form-item>
            <a-form-item :label="fields.email.short">
                {{ modal.data.email }}
            </a-form-item>
            <a-form-item :label="fields.phone.short">
                {{ modal.data.areacode }} - {{ modal.data.phone }}
            </a-form-item>
            <a-form-item :label="fields.subjects.short">
                {{ modal.data.subjects }}
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

export default {
    components: {
        DepartmentLayout,
    },
    props: ['department','inquiries','fields'],
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
                    title: this.fields.origin.short,
                    dataIndex: 'origin',
                    options:this.fields.origin.options,
                },{
                    title: this.fields.degree.short,
                    dataIndex: 'degree',
                    options:this.fields.degree.options,
                },{
                    title: this.fields.admission.short,
                    dataIndex: 'admission',
                    options:this.fields.admission.options,
                },{
                    title: this.fields.profile.short,
                    dataIndex: 'profile',
                    options:this.fields.profile.options,
                },{
                    title: this.fields.apply.short,
                    dataIndex: 'apply',
                    options:this.fields.apply.options,
                },{
                    title: this.fields.givenname.short,
                    dataIndex: 'givenname',
                },{
                    title: this.fields.surname.short,
                    dataIndex: 'surname',
                },{
                    title: this.fields.email.short,
                    dataIndex: 'email',
                },{
                    title: this.fields.phone.short,
                    dataIndex: 'phone',
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
            var label=options.find(option=>option.value==item)['label'].split(" ");
            return label[0];
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