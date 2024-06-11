<template>
    <DepartmentLayout title="Workflows" :breadcrumb="breadcrumb">
        <div class="mx-auto pt-5">
            <div class="flex-auto pb-3 text-right">
                <a-button @click="createRecord" class="ant-btn ant-btn-primary">Create</a-button>
            </div>
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table :dataSource="workflow.activities" :columns="columns" :expand-column-width="200">
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="editRecord(record)">Edit</a-button>
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

            <!-- Modal Start-->
    <a-modal v-model:visible="modal.isOpen" :title="modal.title" width="60%">
      <a-form
        :model="modal.data"
        ref="modalRef"
        name="default"
        layout="horizontal"
        :rules="rules"
        :validate-messages="validateMessages"
        :label-col="{ style:{width:'120px'}  }" :wrapper-col="{ span: 20 }"
      >
          <a-form-item label="Name" name="name" >
            <a-input v-model:value="modal.data.name" />
          </a-form-item>
          <a-form-item label="Department" name="department_id" >
            <a-select v-model:value="modal.data.department_id" :options="departments.map(d=>({value:d.id,label:d.abbr+'-'+d.name_zh}))"/>
          </a-form-item>
          <a-row>
            <a-col :span="12">
                <a-form-item label="Plan Start" name="date_start" >
                    <a-date-picker v-model:value="modal.data.date_start" :valueFormat="dateFormat" :format="dateFormat"/>
                </a-form-item>
                <a-form-item label="Target Start" name="target_start" >
                    <a-date-picker v-model:value="modal.data.target_start" :valueFormat="dateFormat" :format="dateFormat"/>
                </a-form-item>
                <a-form-item label="Days" name="days" >
                    <a-input-number v-model:value="modal.data.days" />
                </a-form-item>
            </a-col>
            <a-col :span="12">
                <a-form-item label="Plan End" name="date_end" >
                    <a-date-picker v-model:value="modal.data.date_end" :valueFormat="dateFormat" :format="dateFormat"/>
                </a-form-item>
                <a-form-item label="Target End" name="target_end" >
                    <a-date-picker v-model:value="modal.data.target_end" :valueFormat="dateFormat" :format="dateFormat"/>
                </a-form-item>
                <a-form-item label="Active" name="active" >
                    
                </a-form-item>
            </a-col>
          </a-row>
          <a-form-item label="Email" name="email" >
            <a-input v-model:value="modal.data.email" />
          </a-form-item>
          <a-form-item label="Remark" name="remark" >
            <a-textarea v-model:value="modal.data.remark" />
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
    props: ["departments","workflow"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"招聘流程" ,url:route('personnel.recruitment.workflows.index')},
                {label:"工作項目", url: null },
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
            //     total: this.workflows.total,
            //     current: this.workflows.current_page,
            //     pageSize: this.workflows.per_page,
            //     defaultPageSize:40,
            //     showSizeChanger:true,
            //     pageSizeOptions:['10','20','30','40','50']
            // },
            columns: [
                {
                    title: "Sequence",
                    dataIndex: "sequence",
                    minWidth:200,
                }, {
                    title: "Name",
                    dataIndex: "name",
                    minWidth:200,
                }, {
                    title: "Plan Start",
                    dataIndex: "date_start",
                    width: 120,
                }, {
                    title: "Plan End",
                    dataIndex: "date_end",
                    width: 120,
                }, {
                    title: "Target start",
                    dataIndex: "target_start",
                    width: 120,
                }, {
                    title: "Target end",
                    dataIndex: "target_end",
                    width: 120,
                }, {
                    title: "Days",
                    dataIndex: "days",
                    width: 100,
                }, {
                    title: "Active",
                    dataIndex: "active",
                    width: 100,
                }, {
                    title: "操作",
                    dataIndex: "operation",
                    key: "operation",
                    width: 240,
                },
            ],
            rules:{
                date_start: { required: true },
                date_end: { required: true },
                email: { type:'email' },
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
                this.$inertia.post(route('personnel.recruitment.activities.store',{workflow:this.workflow.id}), this.modal.data, {
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
                this.$inertia.patch(route('personnel.recruitment.activities.update', {
                     workflow: this.workflow.id,
                     activity: this.modal.data.id
                }), this.modal.data, {
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
            this.$inertia.delete(route("personnel.recruitment.vacancy.workflows.destroy", record.id ), {
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
                route("personnel.recruitment.vacancy.workflows.index"),
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
