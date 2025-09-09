<template>
    <DepartmentLayout title="財產申報提示 - 已發送電郵" :breadcrumb="breadcrumb">
        <div class="flex-auto pb-3 text-right">
            <div class="mb-5">
                <a-range-picker v-model:value="dateRange" :format="dateFormat" :valueFormat="dateFormat"/>
                <a-button type="primary" :danger="withRange()" html-type="submit" @click="onRefresh" class="ml-5">
                    按時期列出
                </a-button>
            </div>
        </div>

        <div class="mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table :dataSource="emails.data" :columns="columns"  :pagination="pagination" @change="onPaginationChange">
                    <template #headerCell="{ column }">
                        {{ column.title }}
                    </template>
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="showRecord(record)">查看電郵</a-button>
                        </template>
                        <template v-else-if="column.dataIndex == 'sender'">
                            {{ record[column.dataIndex] }}
                        </template>
                        <template v-else-if="column.dataIndex == 'created_at'">
                            {{ formatDate(record[column.dataIndex])}}
                        </template>
                        <template v-else>
                            {{ record[column.dataIndex] }}
                        </template>
                    </template>
                </a-table>
            </div>
        </div>

        <!-- Modal Start-->
        <a-modal v-model:open="modal.isOpen" :title="modal.title" width="60%" :okButtonProps="{hidden:true}">
            <a-form ref="modalRef" :model="modal.data" name="formField" :label-col="{ style:{width:'150px'}  }" :wrapper-col="{ span: 20 }"
                autocomplete="off" :rules="rules" :validate-messages="validateMessages">
                <a-form-item label="人動發件人" name="admin_user_id">
                    {{ modal.data.admin_user_id}}
                </a-form-item>
                <a-form-item label="寄件人" name="sender">
                    {{ modal.data.sender }}
                </a-form-item>
                <a-form-item label="收件人" name="receiver">
                    {{ modal.data.receiver}}
                </a-form-item>
                <a-form-item label="發出日期" name="created_at">
                    {{ formatDate(modal.data.created_at)}}
                </a-form-item>
                <a-form-item label="主指" name="subject">
                    {{ modal.data.subject}}
                </a-form-item>
                <a-form-item label="郵件內文" name="content">
                    <div v-html="modal.data.content"/>
                </a-form-item>
            </a-form>
        </a-modal>
        <!-- Modal End-->



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
    props: ["emails"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"已發送電郵" ,url:null},
            ],
            loading: false,
            imageUrl: null,
            dateFormat: "YYYY-MM-DD",
            dateRange:null,
            modal: {
                isOpen: false,
                data: {},
                title: "Modal",
                mode: "",
            },
            pagination: {
                total: this.emails.total,
                current: this.emails.current_page,
                pageSize: this.emails.per_page,
            },
            employmentOptions:[
                {value:'CONTRACT',label:'工作合同'},
                {value:'REQUISITION',label:'徵用'},
                {value:'APPOINT',label:'定期委任'}
            ],
            columns: [
                {
                    title: "寄件人",
                    dataIndex: "sender",
                }, {
                    title: "收件人",
                    dataIndex: "receiver",
                }, {
                    title: "手動發件人",
                    dataIndex: "admin_user_id",
                }, {
                    title: "發出日期",
                    dataIndex: "created_at",
                }, {
                    title: "操作",
                    dataIndex: "operation",
                    key: "operation",
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
        const url = new URL(window.location)
        const bar = url.searchParams.get('period[]')
        this.dateRange=[dayjs().subtract(1,'day').format(this.dateFormat),dayjs().format(this.dateFormat)];
    },
    methods: {
        showRecord(record) {
            this.modal.data = record;
            this.modal.mode = "EDIT";
            this.modal.title="Email recored";
            this.modal.isOpen = true;
        },
        onPaginationChange(page, filters, sorter) {
            const url = new URL(window.location)
            const hasPeriod = url.searchParams.get('date_range[]')

            this.$inertia.get(route("personnel.gpdps.emails"),
                {
                    page: page.current,
                    per_page: page.pageSize,
                    date_range:hasPeriod?this.formatDateRange(this.dateRange):null
                },{
                    onSuccess: (page) => {
                        //console.log(page);
                    },
                    onError: (error) => {
                        console.log(error);
                    },
                }
            );
        },
        onRefresh(){
            
            this.$inertia.get(route('personnel.gpdps.emails'),
                {
                    date_range:this.dateRange?this.formatDateRange(this.dateRange):null
                },{
                    onSuccess: (page) => {
                        //console.log(page);
                    },
                    onError: (error) => {
                        console.log(error);
                    },
                }
            );
        },
        formatDate(date, format = 'YYYY-MM-DD HH:mm') {
            return dayjs(date).format(format);
        },
        formatDateRange(date, format='YYYY-MM-DD'){
            date[0]=dayjs(date[0]).format(format)+' 00:00:00'
            date[1]=dayjs(date[1]).format(format)+' 23:59:00'
            return date
        },
        withRange(){
            const url = new URL(window.location)
            const hasDateRange = url.searchParams.get('date_range[]')
            return hasDateRange!=null
        }

    },
};
</script>
