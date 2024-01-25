<template>
    <DepartmentLayout title="Personnel">
       
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                個資申報義務 - 通知電郵
            </h2>
        </template>
        <div class="container mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table :dataSource="emails.data" :columns="columns"  :pagination="pagination" @change="onPaginationChange">
                    <template #headerCell="{ column }">
                        {{ column.title }}
                    </template>
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="showRecord(record)">Show</a-button>
                        </template>
                        <template v-else>
                            {{ record[column.dataIndex] }}
                        </template>
                    </template>
                </a-table>
            </div>
        </div>

        <!-- Modal Start-->
        <a-modal v-model:visible="modal.isOpen" :title="modal.title" width="60%" :okButtonProps="{hidden:true}">
            <a-form ref="modalRef" :model="modal.data" name="formField" :label-col="{ style:{width:'150px'}  }" :wrapper-col="{ span: 20 }"
                autocomplete="off" :rules="rules" :validate-messages="validateMessages">
                <a-form-item label="Admin user" name="admin_user_id">
                    {{ modal.data.admin_user_id}}
                </a-form-item>
                <a-form-item label="Sender" name="sender">
                    {{ modal.data.sender }}
                </a-form-item>
                <a-form-item label="Receiver)" name="receiver">
                    {{ modal.data.receiver}}
                </a-form-item>
                <a-form-item label="Created At" name="create_at">
                    {{ modal.data.created_at}}
                </a-form-item>
                <a-form-item label="Subjectd" name="subject">
                    {{ modal.data.created_at}}
                </a-form-item>
                <a-form-item label="Content" name="content">
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
import { quillEditor, Quill } from "vue3-quill";
import { message } from "ant-design-vue";
import dayjs from 'dayjs';

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
    props: ["emails"],
    data() {
        return {
            loading: false,
            imageUrl: null,
            modal: {
                isOpen: false,
                data: {},
                title: "Modal",
                mode: "",
            },
            dateFormat: "YYYY-MM-DD",
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
                    title: "Sender",
                    dataIndex: "sender",
                }, {
                    title: "Receiver",
                    dataIndex: "receiver",
                }, {
                    title: "Admin User",
                    dataIndex: "admin_user_id",
                }, {
                    title: "Created At",
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
    created() { },
    methods: {
        showRecord(record) {
            this.modal.data = record;
            this.modal.mode = "EDIT";
            this.modal.title="Email recored";
            this.modal.isOpen = true;
        },
        onPaginationChange(page, filters, sorter) {
            this.$inertia.get(
                route("personnel.gpdps.emails"),
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
