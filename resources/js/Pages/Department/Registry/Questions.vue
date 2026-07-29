<template>
    <DepartmentLayout title="須回應提問" :breadcrumb="breadcrumb">
        <div class="mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table 
                    :dataSource="questions.data" 
                    :columns="columns" 
                    :row-key="record => record.root_id" 
                    :pagination="pagination" 
                    @change="onPaginationChange"
                >
                    <template #bodyCell="{column, text, record, index}" >
                        <template v-if="column.dataIndex=='operation'">
                            <a-button @click="viewRecord(record)">瀏覽</a-button>
                            <a-button @click="goToRecord(record.id, $event)" type="default">回應</a-button>
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
    props: ['department','questions','configFields'],
    data() {
        return {
            breadcrumb:[
                {label:"招生注冊處" ,url:route('registry.dashboard')},
                {label:"須回應提問" ,url:null},
            ],
            filters:{
                status:[]
            },
            sorter: {
                field: null,
                order: null // 'ascend' or 'descend'
            },
            fields:[],
            modal:{
                isOpen:false,
                data:{},
                title:"Modal",
                mode:""
            },
            pagination: {
                total: this.questions.total,
                current: this.questions.current_page || 1,
                pageSize: this.questions.per_page || 10,
                defaultPageSize: 10,
                showSizeChanger: true,
                pageSizeOptions: ['10', '25', '50', '75', '100'],
                showTotal: (total, range) => `${range[0]}-${range[1]} of ${total} items`,
            },
        }
    },
    created(){       
        this.fields=this.configFields
    },
    mounted(){
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.forEach((value, key) => {
            if (key.startsWith('filters[status][')) {
                const filterKey='status';
                this.filters[filterKey] = this.filters[filterKey] || [];
                this.filters[filterKey].push(value);
            }
        });
        if (urlParams.size === 0) {
            this.filters.status.push('false');
        }
    },
    computed:{
        columns(){
            return [
                {
                    title: '日期',
                    dataIndex: 'created_at',
                    sorter: true, // Enable sorting
                    sortDirections: ['ascend', 'descend', 'ascend'],
                },{
                    title: '查詢編號',
                    dataIndex: 'enquiry_id',
                    sorter: true, // Enable sorting
                    sortDirections: ['ascend', 'descend', 'ascend'],
                },{
                    title: '證件類別('+this.configFields.origin.short+')',
                    dataIndex: 'origin',
                },{
                    title: this.configFields.admission.short,
                    dataIndex: 'admission',
                },{
                    title: this.configFields.degree.short,
                    dataIndex: 'degree',
                },{
                    title: '姓, 名',
                    dataIndex: 'fullname',
                },{
                    title: '跟進人員',
                    dataIndex: 'admin_user',
                },{
                    title: '跟進情況',
                    dataIndex: 'status',
                    filters: [{value:false,text:'跟進中'},{value:true,text:'完成'}],
                    filterMultiple: false,
                    defaultFilteredValue: this.filters['status'],
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
        },
        goToRecord(id, event) {
            const url = route('registry.enquiry.questions.show', { question: id });
            
            // Check if Shift key is pressed
            if (event && event.shiftKey) {
                // Open in new tab
                window.open(url, '_blank');
            } else {
                // Navigate in same tab
                this.$inertia.visit(url);
            }
        },
        onPaginationChange(page, filters, sorter) {
            // Update filters
            this.filters = {
                ...this.filters,
                ...filters
            };

            // Update sorter
            if (sorter && sorter.field) {
                this.sorter = {
                    field: sorter.field,
                    order: sorter.order
                };
            }

            // Update pagination state
            const currentPage = page.current || page;
            const pageSize = page.pageSize || this.pagination.pageSize;

            this.pagination.current = currentPage;
            this.pagination.pageSize = pageSize;

            // Make the request with updated parameters
            this.$inertia.get(
                route("registry.enquiry.questions.index"),
                {
                    page: currentPage,
                    per_page: pageSize,
                    filters: this.filters,
                    sort_field: this.sorter.field,
                    sort_order: this.sorter.order,
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        // Update pagination with new data
                        if (page.props.questions) {
                            this.pagination.total = page.props.questions.total;
                            this.pagination.current = page.props.questions.current_page;
                            this.pagination.pageSize = page.props.questions.per_page;
                        }
                    },
                    onError: (error) => {
                        console.log(error);
                    },
                }
            );
        },
    },
}
</script>