<template>
    <DepartmentLayout title="Dashboard" :department="department">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                客戶服務管理
            </h2>
        </template>
        <a-typography-title :level="4">List of Enquiry</a-typography-title>


        <div class="container mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table :dataSource="enquiriesStat" :columns="columns" :rowKey="record => record.id"
                    @change="handleTableChange">
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="viewRecord(record)">Edit</a-button>
                        </template>
                        <template v-else-if="column.dataIndex == 'created_at'">
                            {{ dateFormat(record.created_at) }}
                            <!-- <inertia-link :href="route('manage.department.faqs.show', {department:record.department_id, faq:record.id})">View</inertia-link> -->
                        </template>
                        <template v-else-if="column.dataIndex == 'origin'">
                            {{ optionFind(fields.origin.options, record.origin) }}
                        </template>
                        <template v-else-if="column.dataIndex == 'degree'">
                            {{ optionFind(fields.degree.options, record.degree) }}
                        </template>
                        <template v-else-if="column.dataIndex == 'full_name'">
                            {{ record.surname }}, {{ record.givenname }}
                        </template>
                        <template v-else-if="column.dataIndex == 'admin_user'">
                            <span v-if="record.last_response">
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
                <a-form-item :label="fields.admission.short">
                    {{ optionFind(fields.admission.options, modal.data.admission) }}
                </a-form-item>
                <a-form-item :label="fields.profile.short">
                    {{ optionFind(fields.profile.options, modal.data.profile) }}
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
            <template #footer>
                <a-button @click="modal.isOpen = false">Close</a-button>
            </template>
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
        dayjs
    },
    props: ['department', 'enquiriesStat', 'fields'],
    data() {
        return {
            modal: {
                isOpen: false,
                data: {},
                title: "Modal",
                mode: ""
            },
            teacherStateLabels: {},
            columns: [
                {
                    title: '日期',
                    dataIndex: 'created_at',
                    sorter: {
                        compare: (a, b) => {
                            console.log(a)
                            return new Date(a.created_at).getTime() - new Date(b.created_at).getTime()
                        }
                    }

                }, {
                    title: '查詢編號',
                    dataIndex: 'id',
                    sorter: (a, b) => a.id - b.id
                }, {
                    title: '證件類別(持有證件)',
                    dataIndex: 'origin',
                    sorter: (a, b) => a.origin.localeCompare(b.origin),
                    filters: this.fields.origin.options,
                    filterMultiple: false,
                    onFilter: (value, record) => record.origin == value
                }, {
                    title: '課程類別(入讀課程)',
                    dataIndex: 'degree',
                    sorter: (a, b) => a.degree.localeCompare(b.degree),
                    filters: this.fields.degree.options,
                    filterMultiple: false,
                    onFilter: (value, record) => record.degree == value
                }, {
                    title: '入學途徑',
                    dataIndex: 'admission',
                    sorter: (a, b) => a.admission.localeCompare(b.admission),
                    filters: this.fields.admission.options,
                    filterMultiple: false,
                    onFilter: (value, record) => record.admission == value
                }, {
                    title: '姓, 名',
                    dataIndex: 'full_name',
                    sorter: (a, b) => a.surname.localeCompare(b.surname)
                }, {
                    title: '電話',
                    dataIndex: 'phone',
                    sorter: (a, b) => a.phone.localeCompare(b.phone)
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
            ],
        }
    },
    created() {
        this.fields.origin.options.forEach(o => o.text = o.label)
        this.fields.degree.options.forEach(o => o.text = o.label)
        this.fields.admission.options.forEach(o => o.text = o.label)
    },
    methods: {
        optionFind(options, item) {
            var label = options.find(option => option.value == item)['label'].split(" ");
            return label[0];
        },
        getOptionItem(options, item) {
            var items = options.filter(option => {
                return item.includes(option['value']);
            });
            return items;
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
        dateFormat(date, format = 'YY-MM-DD HH:mm') {
            return dayjs(date).format(format);
        },
        handleTableChange(pag, filters, sorter) {
            //console.log('params', pag, filters, sorter);

        },


    },
}
</script>
