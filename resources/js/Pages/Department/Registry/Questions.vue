<template>
    <DepartmentLayout title="須回應提問" :breadcrumb="breadcrumb">
        <div class="mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table :dataSource="department.enquiry_questions_open" :columns="columns" :row-key="record => record.root_id">
                    <template #bodyCell="{column, text, record, index}" >
                        <template v-if="column.dataIndex=='operation'">
                            <a-button @click="viewRecord(record)">瀏覽</a-button>
                            <inertia-link :href="route('registry.enquiry.questions.show', { question:record.id})" class="ant-btn">回應</inertia-link>
                        </template>
                        <template v-else-if="column.dataIndex=='enquiry_id'">
                            {{ text }}
                        </template>
                        <template v-else-if="column.dataIndex=='origin'">
                            {{ mapOptionsItem(fields.origin.options,record['enquiry']['origin']) }}
                        </template>
                        <template v-else-if="column.dataIndex=='admission' && record.enquiry.admission">
                            {{ mapOptionsItem(fields.admission.options,record['enquiry']['admission']) }}
                        </template>
                        <template v-else-if="column.dataIndex=='degree'">
                            {{ mapOptionsItem(fields.degree.options,record['enquiry']['degree']) }}
                        </template>
                        <template v-else-if="column.dataIndex=='fullname'">
                            {{ record['enquiry']['surname']}} {{record['enquiry']['givenname'] }}
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
                            <span v-if="record.last_response">
                                {{ record.last_response.admin_user?record.last_response.admin_user.name:'--' }}
                            </span>
                            <span v-else>
                                ---
                            </span>
                        </template>
                        <template v-else-if="column.dataIndex=='content'">
                            {{ record.content.substring(0,10) }}...
                        </template>
                        <template v-else-if="column.dataIndex=='status'">
                            
                            {{ record.is_closed?'完成':'跟進中' }}
                        </template>
                        <template v-else>
                                {{record[column.dataIndex]}}
                        </template>
                    </template>
                </a-table>
                <p>Shows only enquiries with question and not yet closed.</p>
            </div>
        </div>

        <!-- Modal Start-->
        <a-modal v-model:open="modal.isOpen" :title="modal.title" width="60%" >
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
            <a-form-item :label="fields.admission.short" v-if="modal.data.enquiry.admission">
                {{ optionFind(fields.admission.options,modal.data.enquiry.admission) }}
            </a-form-item>
            <a-form-item :label="fields.profile.short">
                {{ optionFind(fields.profile.options,modal.data.enquiry.profile) }}
                {{ modal.data.enquiry.profile_other }}
            </a-form-item>
            <a-form-item :label="fields.apply.short">
                {{ optionFind(fields.apply.options,modal.data.enquiry.apply) }}
                {{ modal.data.enquiry.apply_number }}
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
import { loadLanguageAsync } from "laravel-vue-i18n";
import { defineComponent, reactive } from 'vue';
import dayjs from 'dayjs';

export default {
    components: {
        DepartmentLayout,
        loadLanguageAsync
    },
    props: ['department','configFields'],
    data() {
        return {
            breadcrumb:[
                {label:"招生注冊處" ,url:route('registry.dashboard')},
                {label:"須回應提問" ,url:null},
            ],
            fields:[],
            modal:{
                isOpen:false,
                data:{},
                title:"Modal",
                mode:""
            },
        }
    },
    created(){       
        this.fields=this.configFields
    },
    mounted(){
        //loadLanguageAsync(this.$page.props.lang)
    },
    computed:{
        columns(){
            return [
                {
                    title: '日期',
                    dataIndex: 'created_at',
                    sorter: (a, b) => {
                        return new Date(a.enquiry.created_at).getTime() > new Date(b.enquiry.created_at).getTime()
                        //a.enquiry.phone.localeCompare(b.enquiry.)
                    },
                },{
                    title: '查詢編號',
                    dataIndex: 'enquiry_id',
                },{
                    title: '證件類別('+this.configFields.origin.short+')',
                    dataIndex: 'origin',
                    sorter: (a, b) => a.enquiry.origin.localeCompare(b.enquiry.origin),
                    filters: this.configFields.origin.options.map(option=>({
                        text:option['label_'+this.$t('lang')],
                        value: option.value
                    })),
                    filterMultiple: false,
                    onFilter: (value, record) => record.enquiry.origin == value

                },{
                    title: this.configFields.admission.short,
                    dataIndex: 'admission',
                    sorter: (a, b) => a.enquiry.admission.localeCompare(b.enquiry.admission)
                },{
                    title: this.configFields.degree.short,
                    dataIndex: 'degree',
                    sorter: (a, b) => a.enquiry.degree.localeCompare(b.enquiry.degree)
                },{
                    title: '姓, 名',
                    dataIndex: 'fullname',
                    sorter: (a, b) => a.enquiry.surname.localeCompare(b.enquiry.surename)
                // },{
                //     title: this.fields.email.short,
                //     dataIndex: 'email',
                //     sorter: (a, b) => a.enquiry.email.localeCompare(b.enquiry.email),
                // },{
                //     title: this.fields.phone.short,
                //     dataIndex: 'phone',
                //     sorter: (a, b) => a.enquiry.phone.localeCompare(b.enquiry.phone),
                // },{
                //     title: '提問',
                //     dataIndex: 'content',
                },{
                    title: '跟進人員',
                    dataIndex: 'admin_user',
                },{
                    title: '跟進情況',
                    dataIndex: 'status',
                    sorter: (a, b) => a.is_closed > b.is_closed,
                    filters: [{value:true,text:'完成'},{value:false,text:'跟進中'}],
                    filterMultiple: false,
                    onFilter: (value, record) => record.is_closed == value

                },{
                    title: '操作',
                    dataIndex: 'operation',
                    key: 'operation',
                },
            ]
        }
    },
    methods: {
        optionFind(options,item){
            if(Array.isArray(item)){
                var labels=options.filter(option=>item.includes(option.value))
                return labels.map(l=>l.label_zh).join("<br>")
            }else{
                var label=options.find(option=>option.value==item)['label_zh']
                return label
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
            this.modal.title="瀏 覽";
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
        dateFormat(date, format = 'YYYY-MM-DD HH:mm') {
            return dayjs(date).format(format);
        },
        mapOptionsItem(options,item){
            const option = options.find(o=>o.value==item)
            return option?option['label_zh']:'--'
        }

    },
}
</script>