<template>
    <DepartmentLayout title="招聘通告" :breadcrumb="breadcrumb">
        <div class="mx-auto pt-5">
            <a-typography-title :level="3">{{ vacancy.code }} - {{ vacancy.title_zh }}</a-typography-title>
            <div class="flex-auto pb-3 text-right">
                <inertia-link :href="route('personnel.recruitment.vacancy.notices.create',{vacancy:vacancy.id})" class="ant-btn ant-btn-primary">新關通告</inertia-link>
            </div>
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table :dataSource="notices.data" :columns="columns" :pagination="pagination" @change="onPaginationChange" :expand-column-width="200">
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                        <inertia-link :href="route('personnel.recruitment.vacancy.notices.edit',{vacancy:record.rec_vacancy_id,notice:record.id})">修改</inertia-link>
                            <a-popconfirm title="是否確定刪除?" ok-text="Yes" cancel-text="No"
                                @confirm="deleteConfirmed(record)" :disabled="record.entries_count > 0">
                                <a-button :disabled="record.entries_count > 0">刪除</a-button>
                            </a-popconfirm>
                        </template>
                        <template v-else-if="column.dataIndex=='can_download'">
                            <span :class="text?'':'text-orange-600'">{{ text?'可下載':'有條件下載' }}</span>
                        </template>
                        <template v-else-if="column.dataIndex=='can_apply'">
                            <span :class="text?'':'text-orange-600'">{{ text?'可報名':'不可報名' }}</span>
                        </template>
                        <template v-else-if="column.dataIndex=='published'">
                            <span :class="text?'':'text-orange-600'">{{ text?'已發報':'未發報' }}</span>
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
        message,
        dayjs
    },
    props: ["vacancy","notices"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"職位招聘" ,url:route('personnel.recruitment.vacancies.index')},
                {label:"招聘通告" ,url:null},
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
                total: this.notices.total,
                current: this.notices.current_page,
                pageSize: this.notices.per_page,
                defaultPageSize:40,
                showSizeChanger:true,
                pageSizeOptions:['10','20','30','40','50']
            },
            columns: [
                {
                    title: "職位名稱",
                    dataIndex: "title_zh",
                    minWidth:200,
                }, {
                    title: "開始日期",
                    dataIndex: "date_start",
                    width: 150,
                }, {
                    title: "截止日期",
                    dataIndex: "date_end",
                    width: 150,
                }, {
                    title: "下載",
                    dataIndex: "can_download",
                    width: 100,
                }, {
                    title: "報名",
                    dataIndex: "can_apply",
                    width:100,
                }, {
                    title: "發佈",
                    dataIndex: "published",
                    width: 100,
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
            this.$inertia.delete(route("personnel.recruitment.vacancy.notices.destroy", record.id ), {
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
                route("personnel.recruitment.vacancy.notices.index"),
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
