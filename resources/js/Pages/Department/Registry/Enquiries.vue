<template>
    <DepartmentLayout title="所有查詢" :breadcrumb="breadcrumb">
        <div class="mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table :dataSource="enquiriesStat" :columns="columns" :rowKey="record => record.id" @change="handleTableChange">
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="viewRecord(record)">View</a-button>
                        </template>
                        <template v-else>
                            {{ record[column.dataIndex] }}
                        </template>
                    </template>
                </a-table>
            </div>
        </div>


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
        loadLanguageAsync,
        dayjs
    },
    props: ['department', 'enquiriesStat', 'fields'],
    data() {
        return {
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
            teacherStateLabels: {},
            columns: [
                {
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
            ],
        }
    },
    created() {
        // this.fields={...this.configFields['tw']}
        // this.fields.origin.options.forEach(o => o.text = o.label)
        // this.fields.degree.options.forEach(o => o.text = o.label)
        // this.fields.admission.options.forEach(o => o.text = o.label)
    },
    mounted(){
        loadLanguageAsync(this.$page.props.lang)
    },
    methods: {
        optionFind(options, item) {
            console.log(options)
            if(options){
                var label = options.find(option => option.value == item)['label'].split(" ");
                return label[0];
            }
            return null
        },
        getOptionItem(options, item) {
            console.log(options)
            if(options){
                var items = options.filter(option => {
                    return item.includes(option['value']);
                });
                return items;
            }
            return null
        },
        viewRecord(record) {
            this.modal.data = { ...record };
            this.modal.mode = "EDIT";
            this.modal.title = "View";
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
            console.log(this.modal.data);
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
        handleTableChange(pag, filters, sorter) {
            //console.log('params', pag, filters, sorter);

        },


    },
}
</script>
