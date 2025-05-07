<template>
    <DepartmentLayout title="所有查詢" :breadcrumb="breadcrumb">
        <div class="mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table :dataSource="enquiries.data" :columns="columns" :rowKey="record => record.id" :pagination="pagination" @change="onPaginationChange" >
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="viewRecord(record)">{{$t('view')}}</a-button>
                        </template>
                        <template v-else-if="column.dataIndex == 'created_at'">
                            {{ dateFormat(record.created_at) }}
                            <!-- <inertia-link :href="route('manage.department.faqs.show', {department:record.department_id, faq:record.id})">View</inertia-link> -->
                        </template>
                        <template v-else-if="column.dataIndex == 'origin'">
                            {{ optionFind(fields.origin.options, record.origin) }}
                        </template>
                        <template v-else-if="column.dataIndex == 'degree'">
                            {{ text }}
                            {{ optionFind(fields.degree.options, record.degree) }}
                        </template>
                        <template v-else-if="column.dataIndex == 'surname'">
                            {{ record.surname }}, {{ record.givenname }}
                        </template>
                        <template v-else-if="column.dataIndex == 'admission'">
                            <template v-if="record.admission">
                                {{ optionFind(fields.admission.options, record.admission) }}
                            </template>
                        </template>
                        <template v-else-if="column.dataIndex == 'admin_user'">
                            <span v-if="record.last_response && record.last_response.admin_user">
                                {{record.last_response.admin_user.username}}
                            </span>
                        </template>
                        <template v-else-if="column.dataIndex == 'response_status'">
                            <span v-if="record.question_count">
                                提問:{{ record.question_count }} / 回應:{{ record.response_count }}
                            </span>
                        </template>
                        <template v-else>
                            {{ record[column.dataIndex] }}
                        </template>
                    </template>
                </a-table>
            </div>
        </div>

        <!-- Modal Start-->
        <a-modal v-model:visible="modal.isOpen" :title="modal.title" width="60%">
            <a-form ref="modalRef" :model="modal.data" name="Teacher" :label-col="{ span: 8 }" :wrapper-col="{ span: 16 }"
                autocomplete="off">
                <a-form-item :label="fields.origin.short">
                    {{ optionFind(fields.origin.options, modal.data.origin) }}
                </a-form-item>
                <a-form-item :label="fields.degree.short">
                    {{ optionFind(fields.degree.options, modal.data.degree) }}
                </a-form-item>
                <a-form-item :label="fields.admission.short" v-if="modal.data.admission">
                    {{ optionFind(fields.admission.options, modal.data.admission) }}
                </a-form-item>
                <a-form-item :label="fields.profile.short">
                        {{ optionFind(fields.profile.options, modal.data.profile) }}
                        {{ modal.data.profile_other }}
                </a-form-item>
                <a-form-item :label="fields.apply.short">
                    {{ optionFind(fields.apply.options, modal.data.apply) }}
                    {{ modal.data.apply_number }}
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
                    <ol>
                        <li v-for="subject in modal.data.subjects">
                            {{ getOptionItem(configFields.subjects.options, subject) }}
                        </li>
                    </ol>
                </a-form-item>
                <a-form-item label="Question">
                    {{ modal.data.question }}
                </a-form-item>
                <a-form-item label="Has question">
                    {{ modal.data.has_question }}
                </a-form-item>
            </a-form>
            <template #footer>
                <a-button @click="modal.isOpen = false">{{ $t('close')}}</a-button>
            </template>
        </a-modal>
        <!-- Modal End-->
    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';
// import { loadLanguageAsync } from "laravel-vue-i18n";
import { defineComponent, reactive } from 'vue';
import dayjs from 'dayjs';

export default {
    components: {
        DepartmentLayout,
        // loadLanguageAsync,
        dayjs
    },
    props: ['department','enquiries', 'enquiriesStat', 'configFields'],
    data() {
        return {
            filters:{
                origin:null,
                degree:null,
                admission:null
            },
            sorter: {
                field: null,
                order: null // 'ascend' or 'descend'
            },
            fields:[],
            breadcrumb:[
                {label:"招生注冊處" ,url:route('registry.dashboard')},
                {label:"所有查詢" ,url:null},
            ],
            modal: {
                isOpen: false,
                data: {},
                title: "Modal",
                mode: ""
            },
            pagination: {
                total: this.enquiries.total,
                current: this.enquiries.current_page,
                pageSize: this  .enquiries.per_page,
                defaultPageSize:40,
                showSizeChanger:true,
                pageSizeOptions:['10','20','30','40','50']
            },
        }   
    },
    created() {
            this.fields=this.configFields
            this.fields.origin.options.forEach(o => o.text = o.label)
            this.fields.degree.options.forEach(o => o.text = o.label)
            this.fields.admission.options.forEach(o => o.text = o.label)

            // Initialize filters from URL parameters if they exist
            if (this.$page.props.filters) {
                this.filters = {
                    ...this.filters,
                    ...this.$page.props.filters
                };
            }
            
            // Initialize sorter from URL parameters if they exist
            if (this.$page.props.sort_field) {
                this.sorter = {
                    field: this.$page.props.sort_field,
                    order: this.$page.props.sort_order
                };
            }
    },
    mounted(){
        //loadLanguageAsync(this.$page.props.lang)
    },
    computed: {
        columns() {
         return [
                {
                    title: '日期',
                    dataIndex: 'created_at',
                    sorter: {
                        compare: (a, b) => {
                            console.log(a)
                            return new Date(a.created_at).getTime() - new Date(b.created_at).getTime()
                        }
                    },
                    sortOrder: this.sorter.field=='created_at'?this.sorter.order:null,
                }, {
                    title: '查詢編號',
                    dataIndex: 'id',
                    sorter: (a, b) => a.id - b.id,
                    sortOrder: this.sorter.field=='id'?this.sorter.order:null,
                }, {
                    title: '證件類別(持有證件)',
                    dataIndex: 'origin',
                    sorter: (a, b) => a.origin.localeCompare(b.origin),
                    // sorter:this.sorter.field=='origin'?true:false,
                    sortOrder: this.sorter.field=='origin'?this.sorter.order:null,
                    filters: this.configFields.origin.options.map(option=>({
                        text:option['label_'+this.$t('lang')],
                        value: option.value
                    })),
                    filteredValue:this.filters.origin? [this.filters.origin] : null,
                    filterMultiple: false,
                    onFilter: (value, record) => record.origin == value,

                }, {
                    title: '課程類別(入讀課程)',
                    dataIndex: 'degree',
                    sorter: (a, b) => a.degree.localeCompare(b.degree),
                    sortOrder: this.sorter.field=='degree'?this.sorter.order:null,
                    filters: this.configFields.degree.options.map(option=>({
                        text:option['label_'+this.$t('lang')] + option.value,
                        value: option.value
                    })),
                    filteredValue:this.filters.degree? [this.filters.degree] : null,
                    filterMultiple: false,
                    // onFilter: (value, record) => record.degree == value
                }, {
                    title: '入學途徑',
                    dataIndex: 'admission',
                    sorter: (a, b) => a.admission.localeCompare(b.admission),
                    sortOrder: this.sorter.field=='admission'?this.sorter.order:null,
                    filters: this.configFields.admission.options.map(option=>({
                        text:option['label_'+this.$t('lang')],
                        value: option.value
                    })),
                    filteredValue:this.filters.admission? [this.filters.admission] : null,
                    filterMultiple: false,
                    // onFilter: (value, record) => record.admission == value
                }, {
                    title: '姓, 名',
                    dataIndex: 'surname',
                    //sorter: (a, b) => a.surname.localeCompare(b.surname),
                    sorter: (a, b) => safeStringCompare(a.degree, b.degree),
                    sortOrder: this.sorter.field=='surname'?this.sorter.order:null,
                }, {
                    title: '電話',
                    dataIndex: 'phone',
                    sorter: (a, b) => a.phone.localeCompare(b.phone),
                    sortOrder: this.sorter.field=='phone'?this.sorter.order:null,
                }, {
                    title: '最後回應',
                    dataIndex: 'admin_user',
                }, {
                    title: '跟進情況',
                    dataIndex: 'response_status',
                }, {
                    title: '操作',
                    dataIndex: 'operation',
                    key: 'operation',
                },
            ]
        }
    },
    methods: {
        onPaginationChange(page, filters, sorter) {

            this.filters = {
                ...this.filters,
                ...filters
            };
            if (sorter.field) {
                this.sorter = {
                    field: sorter.field,
                    order: sorter.order
                };
            }
            // if (sorter) {
            //     this.sorter = {
            //         ...this.sorter,
            //         ...sorter
            //     };
            // }            
            this.$inertia.get(
                route("registry.enquiries.index"),
                {
                    page: page.current,
                    per_page: page.pageSize,
                    filters:filters,
                    // sorter:sorter,
                    sort_field: this.sorter.field,
                    sort_order: this.sorter.order,
                },
                {
                onSuccess: (page) => {
                    console.log(page);
                },
                onError: (error) => {
                    console.log(error);
                },
                }
            );
        },
        optionFind(options, item) {
            const option =options.find(o=>o.value==item)
            return option?option['label_'+this.$t('lang')]:'--'
        },
        getOptionItem(options, item) {
            if(options){
                var items = options.filter(option => {
                    return item.includes(option['value']);
                });
                return items[0]['label_'+this.$t('lang')];
            }
            return null
        },
        viewRecord(record) {
            this.modal.data = { ...record };
            this.modal.mode = "EDIT";
            this.modal.title = this.$t('view');
            this.modal.isOpen = true;
        },
        storeRecord() {
            this.$refs.modalRef.validateFields().then(() => {
                this.$inertia.post('/admin/teachers/', this.modal.data, {
                    onSuccess: (page) => {
                        this.modal.data = {};
                        this.modal.isOpen = false;
                    },
                    onError: (err) => {
                        console.log(err);
                    }
                });
            }).catch(err => {
                console.log(err);
            });
        },
        updateRecord() {
            this.$refs.modalRef.validateFields().then(() => {
                this.$inertia.patch('/admin/teachers/' + this.modal.data.id, this.modal.data, {
                    onSuccess: (page) => {
                        this.modal.data = {};
                        this.modal.isOpen = false;
                        console.log(page);
                    },
                    onError: (error) => {
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


    },
}
</script>
