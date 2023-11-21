<template>
    <DepartmentLayout title="Dashboard" :department="department">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                客戶服務管理
            </h2>
        </template>
        <a-typography-title :level="4">List of Enquiry</a-typography-title>
        <div class="ant-table">
            <div class="ant-table-container">
                <div class="ant-table-content">
                    <table style="table-layout: auto;">
                        <thead class="ant-table-thead">
                            <tr>
                                <th class="ant-table-cell" colstart="0" colend="0">日期</th>
                                <th class="ant-table-cell" colstart="1" colend="1">查詢編號</th>
                                <th class="ant-table-cell" colstart="2" colend="2">證件類別(持有證件)</th>
                                <th class="ant-table-cell" colstart="3" colend="3">課程類別(入讀課程)</th>
                                <th class="ant-table-cell" colstart="4" colend="4">入學途徑</th>
                                <th class="ant-table-cell" colstart="5" colend="5">姓, 名</th>
                                <th class="ant-table-cell" colstart="6" colend="6">電話</th>
                                <th class="ant-table-cell" colstart="7" colend="7">跟進人員</th>
                                <th class="ant-table-cell" colstart="8" colend="8">跟進情況</th>
                                <th class="ant-table-cell" colstart="9" colend="9">操作</th>
                            </tr>
                        </thead>
                        <tbody class="ant-table-tbody">
                            <tr v-for="record in department.enquiries">
                                <td class="ant-table-cell">
                                    {{ dateFormat(record.created_at) }}</td>
                                <td class="ant-table-cell">{{ record.id }}</td>
                                <td class="ant-table-cell">
                                    {{ optionFind(fields.origin.options, record.origin) }}
                                </td>
                                <td class="ant-table-cell">
                                    {{ optionFind(fields.degree.options, record.degree) }}
                                </td>
                                <td class="ant-table-cell">{{ record.admission }}</td>
                                <td class="ant-table-cell">{{ record.surname }}, {{ record.givenname }}</td>
                                <td class="ant-table-cell">{{ record.phone }}</td>
                                <td class="ant-table-cell">
                                    <ol>
                                        <li v-for="question in record.questions">
                                            {{ question.user_id }}
                                        </li>
                                    </ol>
                                </td>
                                <td class="ant-table-cell">
                                    {{ record.questions.filter((q) => q.is_closed == 0).length }} /
                                    {{ record.questions.length }}
                                </td>
                                <td class="ant-table-cell">
                                    <a-button @click="viewRecord(record)">View</a-button>
                                    <inertia-link :href="route('manage.enquiries.show', { enquiry: record.id })">Response</inertia-link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

<!--
        <a-table :dataSource="department.enquiries" :columns="columns" :row-key="record => record.root_id">
            <template #bodyCell="{ column, text, record, index }">
                <template v-if="column.dataIndex == 'operation'">
                    <a-button @click="viewRecord(record)">View</a-button>
                    <inertia-link :href="route('manage.enquiries.show', { enquiry: record.id })">Response</inertia-link>
                </template>
                <template v-else-if="column.dataIndex == 'phone'">
                    {{ record['areacode'] }} - {{ record['phone'] }}
                </template>
                <template v-else-if="column.dataIndex == 'questions'">
                    {{ record.questions.filter((q) => q.is_closed == 0).length }} /
                    {{ record[column.dataIndex].length }}
                </template>
                <template v-else>
                    <span v-if="column.options">
                        {{ optionFind(column.options, text) }}
                    </span>
                    <span v-else>
                        {{ record[column.dataIndex] }}
                    </span>
                </template>
            </template>
        </a-table>
-->
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
                <a-button @click="modal.isOpen=false">Close</a-button>
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
    props: ['department', 'enquiries', 'fields'],
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
                    dataIndex: 'date',
                }, {
                    title: '查詢編號',
                    dataIndex: 'date',
                }, {
                    title: '證件類別(持有證件)',
                    dataIndex: 'origin',
                }, {
                    title: '課程類別(入讀課程)',
                    dataIndex: 'degree',
                }, {
                    title: '入學途徑',
                    dataIndex: 'admission',
                }, {
                    title: '姓, 名',
                    dataIndex: 'full_name',
                }, {
                    title: '電話',
                    dataIndex: 'phone',
                }, {
                    title: '跟進人員',
                    dataIndex: 'id',
                }, {
                    title: '跟進情況',
                    dataIndex: 'id',
                }, {
                    title: '操作',
                    dataIndex: 'operation',
                    key: 'operation',
                },
            ],
        }
    },
    created() {
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

    },
}
</script>
