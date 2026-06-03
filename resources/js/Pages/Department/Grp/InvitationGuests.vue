<template>
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Guest List</h3>
            <div class="space-x-2">
                <!-- New button -->
                <a-button 
                    type="primary" 
                    :disabled="selectedRowKeys.length === 0" 
                    @click="sendEmailsToSelected"
                    :loading="sendingEmails"
                >
                    Send Email to Selected ({{ selectedRowKeys.length }})
                </a-button>

                <!-- <a-button @click="downloadPdf(true)" :loading="pdfLoading">
                    Download Test Invitation PDF
                </a-button> -->
                <a-button @click="downloadPdf(false)" :loading="pdfLoading" :disabled="!event.id">
                    Download Real Invitation PDF
                </a-button>

                <a-button @click="openImportModal">
                    <UploadOutlined /> Import CSV
                </a-button>
                <a-button @click="exportGuests">
                    <DownloadOutlined /> Export Excel
                </a-button>
                <a-button type="primary" @click="openGuestModal(null)">
                    <PlusOutlined /> Add Guest
                </a-button>
            </div>
        </div>
        <!-- Guest Table -->
        <a-table
            :dataSource="event.guests"
            :columns="guestColumns"
            :loading="loading"
            :pagination="pagination"
            @change="onTableChange"
            row-key="id"
            :row-selection="rowSelection"
        >
            <template #bodyCell="{ column, record }">
                <template v-if="column.dataIndex === 'rsvp_status'">
                    <a-tag :color="getRsvpColor(record.rsvp_status)">
                        {{ record.rsvp_status.toUpperCase() }}
                    </a-tag>
                </template>
                <template v-else-if="column.dataIndex === 'responded_at'">
                    {{ record.responded_at ? formatDate(record.responded_at) : '-' }}
                </template>
                <template v-else-if="column.dataIndex === 'operation'">
                    <a-space>
                        <a-button type="link" size="small" @click="openGuestModal(record)">Edit</a-button>
                        <a-button type="link" size="small" danger @click="deleteGuest(record)">Delete</a-button>
                    </a-space>
                </template>
                <template v-else>
                    {{ record[column.dataIndex] }}
                </template>
            </template>
        </a-table>

        <!-- Guest Modal (Create/Edit) -->
        <a-modal
            v-model:open="guestModal.visible"
            :title="guestModal.title"
            width="600px"
            @ok="saveGuest"
            @cancel="guestModal.visible = false"
            :confirmLoading="saving"
        >
            <a-form :model="guestForm" layout="vertical">
                <a-form-item label="Name" required>
                    <a-input type="input" v-model:value="guestForm.name" />
                </a-form-item>
                <a-form-item label="Email" required>
                    <a-input type="input" v-model:value="guestForm.email" />
                </a-form-item>
                <a-form-item label="Organization">
                    <a-input type="input" v-model:value="guestForm.organization" />
                </a-form-item>
                <a-form-item label="Phone">
                    <a-input type="input" v-model:value="guestForm.phone" />
                </a-form-item>
                <a-form-item label="RSVP Status">
                    <a-select v-model:value="guestForm.rsvp_status">
                        <a-select-option value="pending">Pending</a-select-option>
                        <a-select-option value="yes">Yes</a-select-option>
                        <a-select-option value="no">No</a-select-option>
                        <a-select-option value="maybe">Maybe</a-select-option>
                    </a-select>
                </a-form-item>
                <a-form-item label="Guests Count">
                    <a-input type="input"-number 
                        v-model:value="guestForm.guests_count" 
                        :min="1" 
                        :max="event.max_guests_per_invitation || 1" 
                    />
                </a-form-item>
                <a-form-item label="Dietary Needs">
                    <a-textarea v-model:value="guestForm.dietary_needs" :rows="2" />
                </a-form-item>
                <a-form-item label="Notes">
                    <a-textarea v-model:value="guestForm.response_notes" :rows="2" />
                </a-form-item>
            </a-form>
        </a-modal>

        <!-- Import CSV Modal -->
        <a-modal
            v-model:open="importModal.visible"
            title="Import Guests from CSV"
            width="600px"
            @ok="uploadImport"
            @cancel="importModal.visible = false"
            :confirmLoading="importing"
        >
            <a-upload-dragger
                v-model:file-list="importFileList"
                name="file"
                accept=".csv"
                :before-upload="beforeImportUpload"
                :custom-request="handleCustomUpload"
                :max-count="1"
            >
                <p class="ant-upload-drag-icon"><UploadOutlined /></p>
                <p class="ant-upload-text">Click or drag CSV file to upload</p>
                <p class="ant-upload-hint">Columns required: name, email. Optional: organization, phone.</p>
            </a-upload-dragger>
            <div class="mt-2 text-sm text-gray-500">
                <a @click="downloadSampleCsv">Download sample CSV template</a>
            </div>
        </a-modal>


        <a-modal
            v-model:open="resultModal.visible"
            :title="resultModal.title"
            width="560px"
            :footer="null"
            @cancel="resultModal.visible = false"
        >
            <div v-html="resultModal.summary"></div>
            <div v-html="resultModal.failedRowsHtml"></div>
            <div class="mt-4 text-right">
                <a-button type="primary" @click="resultModal.visible = false">Close</a-button>
            </div>
        </a-modal>

    </div>
</template>

<script>
import { message, Modal } from "ant-design-vue";
import { UploadOutlined, PlusOutlined, DownloadOutlined } from "@ant-design/icons-vue";
import dayjs from "dayjs";
import axios from "axios";
import { router } from "@inertiajs/vue3";
import { h } from 'vue';

export default {
    name: "InvitationGuests",
    components: { UploadOutlined, PlusOutlined, DownloadOutlined },
    props: {
        event: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            pdfLoading: false,
            loading: false,
            pagination: {
                current: 1,
                pageSize: 10,
                total: 0,
                showSizeChanger: true,
                sortField: null,
                sortOrder: null,
            },
            guestColumns: [
                { title: "Name", dataIndex: "name", sorter: true },
                { title: "Email", dataIndex: "email" },
                { title: "Organization", dataIndex: "organization" },
                { title: "RSVP", dataIndex: "rsvp_status", align: "center" },
                { title: "Guests", dataIndex: "guests_count", align: "center" },
                { title: "Responded At", dataIndex: "responded_at" },
                { title: "Operation", dataIndex: "operation", width: 150 },
            ],
            guestModal: {
                visible: false,
                title: "",
                editingGuest: null,
            },
            guestForm: {
                id: null,
                name: "",
                email: "",
                organization: "",
                phone: "",
                rsvp_status: "pending",
                guests_count: 1,
                dietary_needs: "",
                response_notes: "",
            },
            saving: false,
            importModal: {
                visible: false,
            },
            importFileList: [],
            importing: false,
            selectedRowKeys: [], // store selected guest IDs
            sendingEmails: false,
            resultModal: {
                visible: false,
                title: '',
                summary: '',
                failedRowsHtml: '',
            }            
        };
    },
    mounted() {
    },
    computed: {
        rowSelection() {
            return {
                selectedRowKeys: this.selectedRowKeys,
                onChange: (selectedRowKeys) => {
                    this.selectedRowKeys = selectedRowKeys;
                },
                // optional: preserve selection on pagination
                preserveSelectedRowKeys: true,
            };
        },
    },    
    methods: {
        formatDate(dateStr) {
            return dateStr ? dayjs(dateStr).format("YYYY-MM-DD HH:mm") : "-";
        },
        getRsvpColor(status) {
            const map = { yes: "green", no: "red", maybe: "orange", pending: "default" };
            return map[status] || "default";
        },

        onTableChange(pagination, filters, sorter) {
            this.pagination.current = pagination.current;
            this.pagination.pageSize = pagination.pageSize;
            if (sorter.field) {
                this.pagination.sortField = sorter.field;
                this.pagination.sortOrder = sorter.order === "ascend" ? "asc" : "desc";
            }
        },
        openGuestModal(guest = null) {
            if (guest) {
                this.guestForm = { ...guest };
                this.guestModal.title = "Edit Guest";
                this.guestModal.editingGuest = guest;
            } else {
                this.guestForm = {
                    invitation_event_id:this.event.id,
                    id: null,
                    name: "",
                    email: "",
                    organization: "",
                    phone: "",
                    rsvp_status: "pending",
                    guests_count: 1,
                    dietary_needs: "",
                    response_notes: "",
                };
                this.guestModal.title = "Add Guest";
                this.guestModal.editingGuest = null;
            }
            this.guestModal.visible = true;
        },
        saveGuest() {
            console.log(this.guestForm)
            if(this.guestForm.id){
                this.updateGuest();
            }else{
                console.log('store new guest', this.guestForm)
                this.storeGuest();
            }
        },
        storeGuest(){
            this.saving = true;
            this.$inertia.post(route('grp.invitation-guests.store'), this.guestForm, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    message.success("Guest created successfully");
                    this.guestModal.visible = false;
                    this.fetchGuests(); // refresh the table
                },
                onError: (errors) => {
                    // Inertia errors are an object with field names as keys
                    const firstError = Object.values(errors)[0];
                    message.error(firstError || "Creation failed");
                    console.error('Store error:', errors);
                },
                onFinish: () => {
                    this.saving = false;
                },
            });
        },
        updateGuest(){
            this.saving = true;
            this.$inertia.put(route('grp.invitation-guests.update', this.guestForm.id), this.guestForm, {
                preserveState: true,
                onSuccess: () => {
                    message.success("Guest updated successfully");
                    this.guestModal.visible = false;
                },
                onError: (errors) => {
                    const errorMsg = errors.response?.data?.message || errors.message || "Update failed";
                    message.error(errorMsg);
                },
                onFinish: () => {
                    this.saving = false;
                },
            });
        },
        deleteGuest(guest) {
            Modal.confirm({
                title: "Delete Guest",
                content: `Delete "${guest.name}"? This action cannot be undone.`,
                okType: "danger",
                onOk: () => {
                    router.delete(route("grp.invitation-guests.destroy", guest.id), {
                        onSuccess: () => {
                            message.success("Guest deleted");
                            this.fetchGuests();
                        },
                        onError: () => message.error("Delete failed"),
                    });
                },
            });
        },
        openImportModal() {
            this.importModal.visible = true;
            this.importFileList = [];
        },
        beforeImportUpload(file) {
            const isCSV = file.type === "text/csv" || file.name.endsWith(".csv");
            if (!isCSV) {
                message.error("Only CSV files are allowed");
                return false;
            }
            const isLt2M = file.size / 1024 / 1024 < 2;
            if (!isLt2M) {
                message.error("File must be smaller than 2MB");
                return false;
            }
            return false;
        },
        handleCustomUpload({ file, onSuccess, onError }) {
            this.importFile = file;
        },
uploadImport() {
    if (!this.importFileList.length) {
        message.warning("Please select a CSV file");
        return;
    }

    const fileItem = this.importFileList[0];
    const file = fileItem.originFileObj || fileItem.file;
    if (!file) {
        message.error("Invalid file selection");
        return;
    }

    const formData = new FormData();
    formData.append('csv', file);
    formData.append('event_id', this.event.id);

    this.importing = true;

    axios.post(route('grp.invitation-guests.import'), formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
    })
    .then(response => {
        const data = response.data;
        const title = data.success ? 'Import Successful' : 'Import Completed with Warnings';
        
        let failedHtml = '';
        if (data.failed_rows && Object.keys(data.failed_rows).length > 0) {
            failedHtml = '<ul>';
            for (const [row, err] of Object.entries(data.failed_rows)) {
                failedHtml += `<li>Row ${row}: ${err}</li>`;
            }
            failedHtml += '</ul>';
        }
        
        this.resultModal = {
            visible: true,
            title: title,
            summary: data.summary,
            failedRowsHtml: failedHtml,
        };
        
        this.importModal.visible = false;
        this.importFileList = [];

    })
    .catch(error => {
        
        const msg = error.response?.data?.message || 'Import failed';
        message.error(msg);
    })
    .finally(() => {
        this.importing = false;
    });
},

        downloadSampleCsv() {
            const csvContent = "name,email,organization,phone\nJohn Doe,john@example.com,Acme Inc,+123456789\nJane Smith,jane@example.com,,\n";
            const blob = new Blob([csvContent], { type: "text/csv" });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "guest_template.csv";
            link.click();
            URL.revokeObjectURL(link.href);
        },
        downloadPdf(isTest = true) {
            this.pdfLoading = true;
            let url = route('grp.invitation-event.generate-pdf');
            url += `?event_id=${this.event.id}`;
            if (isTest) {
                url += '&test=1';
            // } else if (this.eventId) {
            //     url += `?event_id=${this.eventId}`;
            //     // optionally add guest_id from selected guest
            //     // url += `&guest_id=${this.selectedGuestId}`;
            // } else {
            //     this.pdfLoading = false;
            //     return;
            }
            window.open(url, '_blank');
            this.pdfLoading = false;
        },
        sendEmailsToSelected() {
            if (this.selectedRowKeys.length === 0) {
                message.warning('No guests selected');
                return;
            }

            Modal.confirm({
                title: 'Send Invitation Emails',
                content: `Send invitation emails to ${this.selectedRowKeys.length} selected guest(s)?`,
                okText: 'Yes, Send',
                cancelText: 'Cancel',
                onOk: async () => {
                    this.sendingEmails = true;
                    try {
                        const response = await axios.post(route('grp.invitation-event.send-bulk'), {
                            guest_ids: this.selectedRowKeys,
                            event_id: this.event.id,
                        });
                        message.success(`Emails sent to ${response.data.sent} guest(s)`);
                        // optionally clear selection after sending
                        this.selectedRowKeys = [];
                    } catch (error) {
                        message.error(error.response?.data?.message || 'Failed to send emails');
                    } finally {
                        this.sendingEmails = false;
                    }
                },
            });
        },        
        exportGuests() {
            window.location.href = route('grp.invitation-guests.export', this.event.id);
        },
    },
};
</script>