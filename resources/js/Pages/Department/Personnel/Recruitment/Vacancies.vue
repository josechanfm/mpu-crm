<template>
    <DepartmentLayout title="職位招聘" :breadcrumb="breadcrumb">
        <div class="mx-auto pt-5">
            <div class="flex-auto pb-3 text-right">
                <inertia-link :href="route('personnel.recruitment.vacancies.create')" class="ant-btn ant-btn-primary">新增職位招聘</inertia-link>
            </div>
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table :dataSource="vacancies.data" :columns="columns" :pagination="pagination" @change="onPaginationChange" :expand-column-width="200">
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <inertia-link :href="route('personnel.recruitment.vacancy.notices.index',{vacancy:record.id})" class="ant-btn">招聘通告</inertia-link>
                            <inertia-link :href="route('personnel.recruitment.vacancies.edit',record.id)" class="ant-btn">修改</inertia-link>
                            <a-popconfirm title="是否確定刪除?" ok-text="Yes" cancel-text="No"
                                @confirm="deleteConfirmed(record)" :disabled="record.entries_count > 0">
                                <a-button :disabled="record.entries_count > 0">刪除</a-button>
                            </a-popconfirm>
                        </template>
                        <template v-else-if="column.dataIndex=='progress'">
                            <span :class="text?'':'text-orange-600'">{{ text?'進行中':'已完成' }}</span>
                        </template>
                        <template v-else-if="column.dataIndex=='active'">
                            <span :class="text?'':'text-orange-600'">{{ text?'有效':'無效' }}</span>
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
import DepartmentLayout from "@/Layouts/DepartmentLayout.vue";
import {
    UploadOutlined,
    LoadingOutlined,
    PlusOutlined,
    InfoCircleFilled,
} from "@ant-design/icons-vue";
import Icon, { RestFilled } from "@ant-design/icons-vue";
import { quillEditor, Quill } from "vue3-quill";
import { message } from "ant-design-vue";
import dayjs from 'dayjs';
import axios from "axios";

export default {
    components: {
        DepartmentLayout,
        UploadOutlined,
        LoadingOutlined,
        PlusOutlined,
        RestFilled,
        quillEditor,
        message,
        dayjs
    },
    props: ["vacancies"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"職位招聘" ,url:null},
            ],
            loading: false,
            imageUrl: null,
            importFile:null,
            dateFormat: "YYYY-MM-DD",
            exportCriteria:{
                period:[dayjs().subtract(1,'month').format(this.dateFormat),dayjs().format(this.dateFormat)],
                is_valid:true
            },
            modal: {
                isOpen: false,
                data: {},
                title: "Modal",
                mode: "",
            },
            pagination: {
                total: this.vacancies.total,
                current: this.vacancies.current_page,
                pageSize: this.vacancies.per_page,
                defaultPageSize:40,
                showSizeChanger:true,
                pageSizeOptions:['10','20','30','40','50']
            },
            columns: [
                {
                    title: "類別",
                    dataIndex: "type",
                    width: 90,
                }, {
                    title: "招聘編號",
                    dataIndex: "code",
                    width: 100,
                }, {
                    title: "職位名稱",
                    dataIndex: "title_zh",
                    maxWidth: 500,
                }, {
                    title: "開始日期",
                    dataIndex: "date_start",
                    width: 120,
                }, {
                    title: "截止日期",
                    dataIndex: "date_end",
                    width: 120,
                }, {
                    title: "發佈日期",
                    dataIndex: "date_publish",
                    width: 120,
                }, {
                    title: "程序進度",
                    dataIndex: "progress",
                    width: 80,
                }, {
                    title: "有效",
                    dataIndex: "active",
                    width:80,
                }, {
                    title: "操作",
                    dataIndex: "operation",
                    key: "operation",
                    width: 240,
                },
            ],
            rules:{
                email: { required: true, type:'email' },
                date_start: { required: true },
                date_remind: { required: true },
                date_due: { required: true },
            },
            validateMessages: {
                required: '${label} is required!',
                types: {
                    email: '${label} is not a valid email!',
                    number: '${label} is not a valid number!',
                },
                number: {
                    range: '${label} must be between ${min} and ${max}',
                },
            },

        };
    },
    created() {

    },
    mounted() {
    }, 
    methods: {
        deleteConfirmed(record) {
            console.log("delete");
            console.log(record);
            this.$inertia.delete(route("personnel.recruitment.vacancies.destroy", record.id ), {
                onSuccess: (page) => {
                    console.log(page);
                },
                onError: (error) => {
                    alert(error.message);
                },
            });
        },
        onPaginationChange(page, filters, sorter) {
            this.$inertia.get(
                route("personnel.recruitment.vacancies.index"),
                {
                    page: page.current,
                    per_page: page.pageSize,
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
    },
};
</script>
