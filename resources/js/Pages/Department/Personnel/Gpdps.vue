<template>
    <DepartmentLayout title="Personnel">
       
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Personal Data Privacy
            </h2>
        </template>
        <div class="flex-auto pb-3 text-right">
            <a-button type="primary" @click="createRecord(record)">Create</a-button>
        </div>
        <div class="container mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table :dataSource="gpdps" :columns="columns">
                    <template #headerCell="{ column }">
                        {{ column.title }}
                    </template>
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="editRecord(record)">Edit</a-button>
                            <a-popconfirm title="Confirm Delete" ok-text="Yes" cancel-text="No"
                                @confirm="deleteConfirmed(record)" :disabled="record.entries_count > 0">
                                <a-button :disabled="record.entries_count > 0">Delete</a-button>
                            </a-popconfirm>
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
            <a-form ref="modalRef" :model="modal.data" name="formField" :label-col="{ span: 4 }" :wrapper-col="{ span: 20 }"
                autocomplete="off" :rules="rules" :validate-messages="validateMessages">
                <a-form-item label="Staff Number" name="staff_num">
                    <a-input v-model:value="modal.data.staff_num"/>
                </a-form-item>
                <a-form-item label="Name (Chinese)" name="name_zh">
                    <a-input v-model:value="modal.data.name_zh"/>
                </a-form-item>
                <a-form-item label="Name (Foreign)" name="name_fr">
                    <a-input v-model:value="modal.data.name_fr"/>
                </a-form-item>
                <a-form-item label="Email" name="email">
                    <a-input v-model:value="modal.data.email"/>
                </a-form-item>
                <a-form-item label="Start Date" name="date_start">
                    <a-date-picker v-model:value="modal.data.date_start" :format="dateFormat" :valueFormat="dateFormat" @change="dateStartChange()"/>
                </a-form-item>
                <a-form-item label="Remind Date" name="date_remind">
                    <a-date-picker v-model:value="modal.data.date_remind" :format="dateFormat" :valueFormat="dateFormat" />
                </a-form-item>
                <a-form-item label="Due Date" name="date_due">
                    <a-date-picker v-model:value="modal.data.date_due" :format="dateFormat" :valueFormat="dateFormat" />
                </a-form-item>
                <a-form-item label="Is Valid" name="in_valid">
                    <a-switch v-model:checked="modal.data.is_valid" :unCheckedValue="0" :checkedValue="1" />
                </a-form-item>
                <a-form-item label="Remark" name="remark">
                    <a-textarea v-model:value="modal.data.remark" />
                </a-form-item>
            </a-form>
            <template #footer>
                <a-button v-if="modal.mode == 'EDIT'" key="Update" type="primary" @click="updateRecord()">Update</a-button>
                <a-button v-if="modal.mode == 'CREATE'" key="Store" type="primary" @click="storeRecord()">Create</a-button>
            </template>
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
    props: ["gpdps"],
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
            columns: [
                {
                    title: "Staff Number",
                    i18n: "staff_num",
                    dataIndex: "staff_num",
                }, {
                    title: "Name (Chinese)",
                    i18n: "name_zh",
                    dataIndex: "name_zh",
                }, {
                    title: "Name (Foreign)",
                    i18n: "name_fr",
                    dataIndex: "name_fr",
                }, {
                    title: "Email",
                    i18n: "email",
                    dataIndex: "email",
                }, {
                    title: "Start Date",
                    i18n: "date_start",
                    dataIndex: "date_start",
                }, {
                    title: "Remind Date",
                    i18n: "date_remind",
                    dataIndex: "date_remind",
                }, {
                    title: "Due Date",
                    i18n: "date_due",
                    dataIndex: "date_due",
                }, {
                    title: "Is Valid",
                    i18n: "is_valid",
                    dataIndex: "is_valid",
                }, {
                    title: "Operation",
                    i18n: "operation",
                    dataIndex: "operation",
                    key: "operation",
                },
            ],
            rules:{
                name_zh: { required: true },
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
        createRecord() {
            this.modal.data = {};
            this.modal.mode = "CREATE";
            this.modal.title="Create Record";
            this.modal.isOpen = true;
        },
        editRecord(record) {
            this.modal.data = { ...record };
            this.modal.mode = "EDIT";
            this.modal.title="Edit Record";
            this.modal.isOpen = true;
        },
        dateStartChange(){
            this.modal.data.date_remind=dayjs(this.modal.data.date_start).add(60,'days').format(this.dateFormat)
            this.modal.data.date_due=dayjs(this.modal.data.date_start).add(90,'days').format(this.dateFormat)
            console.log(dayjs(this.modal.data.date_start).add(30,'days'))
            console.log(this.modal.data.date_start)
        }, 
        updateRecord(){
            this.$refs.modalRef.validateFields().then(() => {
                this.$inertia.patch(route('personnel.gpdps.update',this.modal.data.id), this.modal.data, {
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
        storeRecord(){
            this.$refs.modalRef.validateFields().then(() => {
                this.$inertia.post(route('personnel.gpdps.store'), this.modal.data, {
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
        deleteConfirmed(record) {
            console.log("delete");
            console.log(record);
            this.$inertia.delete(route("personnel.gpdps.destroy", record.id ), {
                onSuccess: (page) => {
                    console.log(page);
                },
                onError: (error) => {
                    alert(error.message);
                },
            });
        },
    },
};
</script>
