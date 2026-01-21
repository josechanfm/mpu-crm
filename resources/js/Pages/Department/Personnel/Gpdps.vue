<template>
    <DepartmentLayout title="財產申報提示" :breadcrumb="breadcrumb">
            <div class="mb-5">
                <a-form ref="importlRef" action="personnel/gpdps/export">
                    <span v-if="is('admin')">
                        <label for="import-file">
                                匯入('admin only')
                        </label>
                        <input type="file" id="import-file" hidden @change="onImport"/>
                    </span>
                </a-form>

            </div>

        <div class="flex-auto pb-3 text-right">
            <div class="mb-5">
                <a-form ref="importlRef" action="personnel/gpdps/export">
                    <a-range-picker v-model:value="exportCriteria.period" :format="dateFormat" :valueFormat="dateFormat"/>
                    <a-button type="primary" html-type="submit" @click="onExport" class="ml-5">
                        滙出
                    </a-button>
                </a-form>
            </div>
            {{ yesterdaySent }}
            <inertia-link :href="route('personnel.gpdps.emails')" class="ant ant-btn mr-5">已發送電郵</inertia-link>
            <a-button type="primary" @click="createRecord(record)">新增</a-button>
        </div>
        <div class="mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table :dataSource="gpdps.data" :columns="columns" :pagination="pagination" @change="onPaginationChange" :expand-column-width="200">
                    <template #headerCell="{ column }">
                        {{ column.title }}
                    </template>
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'operation'">
                            <a-button @click="editRecord(record)">修改</a-button>
                            <a-popconfirm title="是否確定刪除?" ok-text="Yes" cancel-text="No"
                                @confirm="deleteConfirmed(record)" :disabled="record.entries_count > 0">
                                <a-button :disabled="record.entries_count > 0">刪除</a-button>
                            </a-popconfirm>
                            <a-popconfirm title="是否確定個別發電郵提醒?" ok-text="Yes" cancel-text="No"
                                @confirm="sendEmailConfirmed(record)" :disabled="record.entries_count > 0">
                                <a-button :disabled="record.entries_count > 0">發電郵</a-button>
                            </a-popconfirm>
                        </template>
                        <template v-else-if="column.dataIndex=='sent_email_count'">
                            <span v-if="record.sent_email_count">
                                <inertia-link :href="route('personnel.gpdps.emails',{gpdp_id:record.id})">{{ record.sent_email_count }}</inertia-link>
                            </span>
                            <span v-else>
                                --
                            </span>
                            
                        </template>
                        <template v-else-if="column.dataIndex=='is_valid'">
                            <span :class="text?'':'text-orange-600'">{{ text==1?'有效':'無效' }}</span>
                        </template>
                        <template v-else>
                            {{ record[column.dataIndex] }}
                        </template>
                    </template>
                </a-table>
            </div>
            <p>*該頁面按產生申報義務日降序排列</p>
        </div>

        <!-- Modal Start-->
        <a-modal v-model:open="modal.isOpen" :title="modal.title" width="60%">
            <a-form ref="modalRef" :model="modal.data" name="formField" :label-col="{ style:{width:'150px'}  }" :wrapper-col="{ span: 20 }"
                autocomplete="off" :rules="rules" :validate-messages="validateMessages">
                <a-row>
                    <a-col :span="14">
                        <a-form-item label="姓名(中文)" name="name_zh">
                            <a-input type="inpuut" v-model:value="modal.data.name_zh"/>
                        </a-form-item>
                    </a-col>
                    <a-col :span="10">
                        <a-form-item label="員工編號" name="staff_num">
                            <a-input type="inpuut" v-model:value="modal.data.staff_num"/>
                        </a-form-item>
                    </a-col>
                </a-row>
                <a-row>
                    <a-col :span="24">
                        <a-form-item label="姓名(外文)" name="name_fr">
                            <a-input type="inpuut" v-model:value="modal.data.name_fr"/>
                        </a-form-item>
                    </a-col>
                </a-row>
                <a-row>
                    <a-col :span="12">
                        <a-form-item label="電郵地址" name="email">
                            <a-input type="inpuut" v-model:value="modal.data.email"/>
                        </a-form-item>
                    </a-col>
                    <a-col :span="12">
                        <a-form-item label="身份證明文件編號" name="id_num">
                            <a-input type="inpuut" v-model:value="modal.data.id_num"/>
                        </a-form-item>
                    </a-col>
                </a-row>
                <a-row>
                    <a-col :span="12">
                        <a-form-item label="現任職的實體/部門" name="current_department">
                            <a-input type="inpuut" v-model:value="modal.data.current_department"/>
                        </a-form-item>
                    </a-col>
                    <a-col :span="12">
                        <a-form-item label="現職位/職級/職務" name="current_position">
                            <a-input type="inpuut" v-model:value="modal.data.current_position"/>
                        </a-form-item>
                    </a-col>
                </a-row>
                <a-row>
                    <a-col :span="12">
                        <a-form-item label="原任職部門" name="original_department">
                            <a-input type="inpuut" v-model:value="modal.data.original_department"/>
                        </a-form-item>
                    </a-col>
                    <a-col :span="12">
                        <a-form-item label="原職位" name="original_postion">
                            <a-input type="inpuut" v-model:value="modal.data.original_position"/>
                        </a-form-item>
                    </a-col>
                </a-row>
                <a-row>
                    <a-col :span="12">
                        <a-form-item label="產生申報義務日期" name="date_start">
                            <a-date-picker v-model:value="modal.data.date_start" :format="dateFormat" :valueFormat="dateFormat" @change="dateStartChange()"/>
                        </a-form-item>
                        <a-form-item label="提醒日期(60日)" name="date_remind">
                            <a-date-picker v-model:value="modal.data.date_remind" :format="dateFormat" :valueFormat="dateFormat" />
                        </a-form-item>
                        <a-form-item label="到期日期(90日)" name="date_due">
                            <a-date-picker v-model:value="modal.data.date_due" :format="dateFormat" :valueFormat="dateFormat" />
                        </a-form-item>
                    </a-col>
                    <a-col :span="12">
                        <a-form-item label="任用或聘用方式" name="employment_type">
                            <a-select v-model:value="modal.data.employment_type" :options="employmentOptions" />
                        </a-form-item>
                        <a-form-item label="有效" name="in_valid">
                            <a-switch v-model:checked="modal.data.is_valid"/>
                        </a-form-item>
                    </a-col>
                </a-row>
                <a-row>
                    <a-col :span="24">
                        <a-form-item label="備註" name="remark">
                            <a-textarea v-model:value="modal.data.remark" />
                        </a-form-item>
                    </a-col>
                </a-row>
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
    props: ["gpdps","yesterdaySent"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"財產申報" ,url:null},
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
                total: this.gpdps.total,
                current: this.gpdps.current_page,
                pageSize: this.gpdps.per_page,
                defaultPageSize:40,
                showSizeChanger:true,
                pageSizeOptions:['10','20','30','40','50']
            },
            employmentOptions:[
                {value:'CONTRACT',label:'工作合同'},
                {value:'REQUISITION',label:'徵用'},
                {value:'APPOINT',label:'定期委任'}
            ],
            columns: [
                {
                    title: "員工編號",
                    i18n: "staff_num",
                    dataIndex: "staff_num",
                    width: 90,
                }, {
                    title: "姓名(中文)",
                    i18n: "name_zh",
                    dataIndex: "name_zh",
                    width: 100,
                }, {
                    title: "姓名(外文)",
                    i18n: "name_fr",
                    dataIndex: "name_fr",
                }, {
                    title: "電郵地址",
                    i18n: "email",
                    dataIndex: "email",
                }, {
                    title: "產生申報義務日期",
                    i18n: "date_start",
                    dataIndex: "date_start",
                    width: 150,
                }, {
                    title: "提醒日期(60日)",
                    i18n: "date_remind",
                    dataIndex: "date_remind",
                    width: 150,
                }, {
                    title: "到期日期(90日)",
                    i18n: "date_due",
                    dataIndex: "date_due",
                    width: 140,
                }, {
                    title: "已發送電郵",
                    i18n: "sent_email_count",
                    dataIndex: "sent_email_count",
                }, {
                    title: "有效",
                    i18n: "is_valid",
                    dataIndex: "is_valid",
                    width:60,
                }, {
                    title: "操作",
                    i18n: "operation",
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
        console.log(dayjs().format('YYYY-MM-DD'));
    }, 
    methods: {
        createRecord() {
            this.modal.data = {};
            this.modal.data.current_department='澳門理工大學';
            this.modal.data.employment_type='CONTRACT';
            this.modal.data.is_valid=1;
            this.modal.mode = "CREATE";
            this.modal.title="新增記錄";
            this.modal.isOpen = true;
        },
        editRecord(record) {
            this.modal.data = { ...record };
            this.modal.mode = "EDIT";
            this.modal.title="修改記錄";
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
        onPaginationChange(page, filters, sorter) {
            this.$inertia.get(
                route("personnel.gpdps.index"),
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
        sendEmailConfirmed(record){
            console.log(record);
            this.$inertia.post(route('personnel.gpdp.sendEmailReminder',record.id),{
                onSuccess: (page) => {
                    console.log(page)
                },
                onError: (err) => {
                    console.log(err);
                }
            });
        },
        onExport(){
            if(this.exportCriteria.period && this.exportCriteria.period.length==2){
                const params='period[]='+this.exportCriteria.period[0]+'&period[]='+this.exportCriteria.period[1];
                window.open('./gpdps/export?'+params);
            }
        },
        onImport(e){
            this.$inertia.post(route('personnel.gpdps.import'),e.target.files,{
                onSuccess:(page)=>{
                    console.log(page);
                },
                onError:(err)=>{
                    console.log(err);
                }
            })
        }
    },
};
</script>
