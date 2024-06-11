<template>
    <DepartmentLayout title="招聘通告" :breadcrumb="breadcrumb">
        <div class="mx-auto pt-5">
            <div class="flex-auto pb-3 text-right">
                <a-button @click="createRecord">Create</a-button>
                
            </div>
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table :dataSource="tasks" :columns="columns" :expand-column-width="200">
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="editRecord(record)">Edit</a-button>
                        </template>
                        <template v-else-if="column.dataIndex=='department_id'">
                            {{ record.department.abbr }} - {{ record.department.name_zh }}
                        </template>
                        <template v-else>
                            {{ record[column.dataIndex] }}
                        </template>
                    </template>
                </a-table>
            </div>
        </div>


    <!-- Modal Start-->
    <a-modal v-model:visible="modal.isOpen" title="View Only" width="60%">
      <a-form
        :model="modal.data"
        ref="formRef"
        name="default"
        layout="vertical"
        :validate-messages="validateMessages"
      >
          <a-form-item label="name" name="name" >
            <a-input v-model:value="modal.data.name" />
          </a-form-item>
          <a-form-item label="Department" name="department_id" >
            <a-select v-model:value="modal.data.department_id" :options="departments.map(d=>({value:d.id,label:d.abbr+'-'+d.name_zh}))"/>
          </a-form-item>
          <a-form-item label="days" name="days" >
            <a-input v-model:value="modal.data.days" />
          </a-form-item>
          <a-form-item label="email" name="email" >
            <a-input v-model:value="modal.data.email" />
          </a-form-item>

      </a-form>
      <template #footer>
        <a-button key="back" @click="modal.isOpen = false">cancel</a-button>
        <a-button key="submit" type="primary" @click="updateRecord">
          update</a-button>
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
    props: ["departments","tasks"],
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
                mode: ""
            },
            // pagination: {
            //     total: this.tasks.total,
            //     current: this.tasks.current_page,
            //     pageSize: this.tasks.per_page,
            //     defaultPageSize:40,
            //     showSizeChanger:true,
            //     pageSizeOptions:['10','20','30','40','50']
            // },
            columns: [
                {
                    title: "Type",
                    dataIndex: "vacancy_type",
                }, {
                    title: "Sequence",
                    dataIndex: "sequence",
                }, {
                    title: "Name",
                    dataIndex: "name",
                }, {
                    title: "Department",
                    dataIndex: "department_id",
                }, {
                    title: "Days",
                    dataIndex: "days",
                }, {
                    title: "Email",
                    dataIndex: "email",
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

    },
    mounted() {
    }, 
    methods: {
        createRecord() {
            this.modal.data = {};
            this.modal.mode = "CREATE";
            this.modal.title = "新增";
            this.modal.isOpen = true;
        },
        editRecord(record) {
            this.modal.data = { ...record };
            this.modal.mode = "EDIT";
            this.modal.title = "修改";
            this.modal.isOpen = true;
        },
        storeRecord() {
            this.$refs.modalRef.validateFields().then(() => {
                this.$inertia.post(route('registry.faqs.store'), this.modal.data, {
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
                this.$inertia.patch(route('registry.faqs.update', { faq: this.modal.data.id }), this.modal.data, {
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

        deleteConfirmed(record) {
            console.log("delete");
            console.log(record);
            this.$inertia.delete(route("personnel.recruitment.vacancy.tasks.destroy", record.id ), {
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
                route("personnel.recruitment.vacancy.tasks.index"),
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
