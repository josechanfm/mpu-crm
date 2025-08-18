<template>
    <WebLayout title="工作項目" >
        <div class="mx-auto pt-5">
            <div class="flex-auto pb-3 text-right">
                
            </div>
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table :dataSource="purchases" :columns="columns">
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="viewRecord(record)">{{ $t('edit') }}</a-button>
                        </template>
                        <template v-else-if="column.dataIndex=='buyer' && record.user">
                            <span>{{ record.user.netid }}</span>
                        </template>
                        <template v-else-if="column.dataIndex=='items'">
                            <div v-for="item in record.items">
                                {{ item.name }} ({{ item.qty }})
                            </div>
                        </template>
                        <template v-else-if="column.dataIndex=='created_at'">
                            {{  formatDate(record.created_at) }}
                        </template>
                        <template v-else-if="column.dataIndex=='status'">
                            <span v-if="record.status==null" class="text-yellow-500">未支付</span>
                            <span v-else-if="record.status==0" class="text-red-500">支付失敗</span>
                            <span v-else-if="record.status==1" class="text-red-800">已支付</span>
                            <span v-else-if="record.status==2" class="text-blue-300">確認支付</span>
                            <span v-else-if="record.status==3" class="text-green-300">已領取</span>
                        </template>
                        <template v-else>
                            {{ record[column.dataIndex] }}
                        </template>
                    </template>
                </a-table>
            </div>
        </div>

    <!-- Modal Start-->
    <a-modal v-model:open="modal.isOpen" :title="modal.title" width="60%">
      <a-form
        :model="modal.data"
        ref="modalRef"
        name="default"
        layout="horizontal"
        :label-col="{ style:{width:'120px'}  }" :wrapper-col="{ span: 20 }"
      >
          <a-form-item label="User" >
            {{ modal.data.user.netid }}
          </a-form-item>
          <a-form-item label="Form" >
            <div modal.data.form_meta>
                <div>Faculty: {{ modal.data.form_meta?.faculty }}</div>
                <div>Degree: {{ modal.data.form_meta?.degree }}</div>
                <div>Phone: {{ modal.data.form_meta?.phone }}</div>
                <div>Email: {{ modal.data.form_meta?.email }}</div>
            </div>
          </a-form-item>
          <a-form-item label="Method" >
            {{ modal.data.payment_method }}
          </a-form-item>
          <a-form-item label="Amount" >
            {{ modal.data.amount }}
          </a-form-item>
          <a-form-item label="Items" >
            <div v-for="item in modal.data.items">
                {{ item.name }} ({{ item.qty }})
            </div>
          </a-form-item>
          <a-form-item label="Date" >
            {{  formatDate(modal.data.created_at) }}
          </a-form-item>
          <a-form-item label="Status" >
            <span v-if="modal.data.status==null">未支付</span>
            <span v-else-if="modal.data.status==0">支付失敗</span>
            <span v-else-if="modal.data.status==1">已支付</span>
            <span v-else-if="modal.data.status==2">確認支付</span>
          </a-form-item>

      </a-form>
      <template #footer>
        <a-button key="back" @click="modal.isOpen=false">{{ $t('close')}}</a-button>
      </template>
    </a-modal>
    <!-- Modal End-->

    </WebLayout>
</template>

<script>
import WebLayout from "@/Layouts/WebLayout.vue";
import {
    UploadOutlined,
    LoadingOutlined,
    PlusOutlined,
    DeleteOutlined,
    InfoCircleFilled,
} from "@ant-design/icons-vue";
import Icon, { RestFilled } from "@ant-design/icons-vue";
import { quillEditor, Quill } from "vue3-quill";
import { message } from "ant-design-vue";
import dayjs from 'dayjs';
import axios from "axios";

export default {
    components: {
        WebLayout,
        UploadOutlined, LoadingOutlined, PlusOutlined, DeleteOutlined,
        RestFilled,
        quillEditor,
        message,
        dayjs
    },
    props: ["purchases"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"招聘流程" ,url:route('personnel.recruitment.workflows.index')},
                {label:"工作項目", url: null },
            ],
            dateFormat: "YYYY-MM-DD",
            modal: {
                isOpen: false,
                data: {},
                title: "Modal",
                mode: ""
            },
            columns: [
                {
                    title: "Buyer",
                    dataIndex: "buyer",
                }, {
                    title: "Method",
                    dataIndex: "payment_method",
                }, {
                    title: "Amount",
                    dataIndex: "amount",
                }, {
                    title: "Items",
                    dataIndex: "items",
                }, {
                    title: "Date",
                    dataIndex: "created_at",
                }, {
                    title: "Status",
                    dataIndex: "status",
                }, {
                    title: "操作",
                    dataIndex: "operation",
                    key: "operation",
                    width: 240,
                },
            ],
        };
    },
    created() {

    },
    mounted() {
    }, 
    methods: {
        formatDate(dateString) {
            return dayjs(dateString).format('YYYY-MM-DD H:s');
        },

        viewRecord(record) {
            this.modal.data = { ...record };
            this.modal.mode = "VIEW";
            this.modal.title = "詳細";
            this.modal.isOpen = true;
        },
    },
};
</script>
