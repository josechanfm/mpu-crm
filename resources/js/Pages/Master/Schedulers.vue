<template>
    <MasterLayout title="Scheduler">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Scheduler
            </h2>
        </template>
        
        <a-table :dataSource="schedulers.data" :columns="columns" :pagination="pagination" @change="onPaginationChange">
            <template #bodyCell="{ column, text, record, index }">
                <template v-if="column.dataIndex == 'operation'">
                    <a-button @click="previewRecord(record)">Preview</a-button>
                </template>
                <template v-else-if="column.dataIndex == 'created_at'">
                    {{ formatDate(modal.data.created_at) }}
                </template>
                <template v-else>
                    {{ record[column.dataIndex] }}
                </template>
            </template>
        </a-table>

        <!-- Preview Modal Start -->
        <a-modal v-model:open="modal.isOpen" :title="modal.title" width="60%">
            <div v-if="modal.data">
                <p><strong>Action:</strong> {{ modal.data.action }}</p>
                <p><strong>Result:</strong> {{ modal.data.result }}</p>
                <p><strong>Created At:</strong> {{ formatDate(modal.data.created_at) }}</p>
                <p><strong>Remark:</strong> {{ modal.data.remark }}</p>
            </div>
        </a-modal>
        <!-- Preview Modal End-->
    </MasterLayout>
</template>

<script>
import MasterLayout from '@/Layouts/MasterLayout.vue';
import dayjs from 'dayjs';

export default {
    components: {
        MasterLayout,
    },
    props: ['schedulers'],
    data() {
        return {
            pagination: {
                total: this.schedulers.total,
                current: this.schedulers.current_page,
                pageSize: this.schedulers.per_page,
                defaultPageSize: 40,
                showSizeChanger: true,
                pageSizeOptions: ['10', '20', '30', '40', '50']
            },
            modal: {
                isOpen: false,
                title: 'Manual Preview',
                data: {}
            },
            columns: [
                {
                    title: 'Action',
                    dataIndex: 'action',
                },
                {
                    title: 'Result',
                    dataIndex: 'result',
                },
                {
                    title: 'Created At',
                    dataIndex: 'created_at',
                },
                {
                    title: '操作',
                    dataIndex: 'operation',
                }
            ],
        };
    },

    methods: {
        formatDate(date) {
            return dayjs(date).format('YYYY-MM-DD HH:mm'); // Format the date
        },

        onPaginationChange(page, filters, sorter) {
            this.$inertia.get(
                route("master.schedulers.index"),
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
        previewRecord(record) {
            this.modal.data = { ...record }; // Assign record data to modal
            this.modal.isOpen = true; // Open modal for preview
        },
    },
};
</script>

<style>
/* Optional: Add any needed styles */
</style>