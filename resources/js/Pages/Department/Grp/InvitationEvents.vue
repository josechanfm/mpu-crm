<template>
    <DepartmentLayout :title="departmentTitle" :department="department" :breadcrumb="breadcrumb">
        <div class="pt-5">
            <!-- Event Management Card -->
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-semibold text-xl">Event Invitation</h4>
                    <a-button type="primary" @click="createEvent">
                        <PlusOutlined /> Create Event
                    </a-button>
                </div>

                <!-- Filters -->
                <div class="mb-4 flex flex-wrap gap-4">
                    <a-input-search
                        v-model:value="filters.search"
                        placeholder="Search by name or location"
                        style="width: 300px"
                        @search="handleSearch"
                        allow-clear
                    />
                </div>

                <!-- Events Table -->
                <a-table
                    :columns="columns"
                    :data-source="eventsData"
                    :loading="loading"
                    :pagination="false"
                    row-key="id"
                    @change="handleTableChange"
                >
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'name'">
                            <a @click="viewEvent(record.id)" class="text-blue-600 hover:underline">
                                {{ record.name }}
                            </a>
                        </template>
                        
                        <template v-else-if="column.key === 'start_date'">
                            {{ formatDate(record.start_date) }}
                        </template>
                        
                        <template v-else-if="column.key === 'end_date'">
                            {{ record.end_date ? formatDate(record.end_date) : '-' }}
                        </template>
                        
                        <template v-else-if="column.key === 'rsvp_deadline'">
                            {{ record.rsvp_deadline ? formatDate(record.rsvp_deadline) : '-' }}
                        </template>
                        
                        <template v-else-if="column.key === 'guests_count'">
                            <a-badge :count="record.guests_count" :number-style="{ backgroundColor: '#52c41a' }" />
                        </template>
                        
                        <template v-else-if="column.key === 'status'">
                            <a-tag :color="record.is_active ? 'green' : 'red'">
                                {{ record.is_active ? 'Active' : 'Inactive' }}
                            </a-tag>
                        </template>
                        
                        <template v-else-if="column.key === 'actions'">
                            <a-space>
                                <a-button size="small" @click="editEvent(record.id)">
                                    <EditOutlined />
                                </a-button>
                                <a-button size="small" danger @click="deleteEvent(record)">
                                    <DeleteOutlined />
                                </a-button>
                                <a-button size="small" @click="viewGuests(record.id)">
                                    <TeamOutlined /> Guests
                                </a-button>
                            </a-space>
                        </template>
                    </template>
                </a-table>

                <!-- Pagination -->
                <div class="mt-4 flex justify-end">
                    <a-pagination
                        v-model:current="pagination.current"
                        v-model:page-size="pagination.pageSize"
                        :total="totalEvents"
                        :show-total="(total) => `Total ${total} events`"
                        :show-size-changer="false"
                        @change="handlePageChange"
                    />
                    <a-select
                        v-model:value="filters.per_page"
                        style="width: 120px"
                        @change="handlePerPageChange"
                    >
                        <a-select-option :value="2">2 / page</a-select-option>
                        <a-select-option :value="10">10 / page</a-select-option>
                        <a-select-option :value="25">25 / page</a-select-option>
                        <a-select-option :value="50">50 / page</a-select-option>
                    </a-select>

                </div>
            </div>
        </div>
    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';
import { router } from '@inertiajs/vue3';
import { message, Modal } from 'ant-design-vue';
import { 
    PlusOutlined, 
    EditOutlined, 
    DeleteOutlined, 
    TeamOutlined 
} from '@ant-design/icons-vue';

export default {
    components: {
        DepartmentLayout,
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        TeamOutlined,
    },
    props: {
        events: {
            type: Object,
            required: true,
        },
        filters: {
            type: Object,
            default: () => ({
                search: '',
                sort_field: 'start_date',
                sort_direction: 'desc',
                per_page: 10,
            }),
        },
        department: {
            type: Object,
            default: () => ({}),
        },
    },
    data() {
        return {
            departmentTitle: 'Public Relationship Office',
            breadcrumb: [
                { label: 'Public Relationship Office', url: null },
                { label: 'Invitation Events', url: null },
            ],
            loading: false,
            currentFilters: { ...this.filters },
            pagination: {
                current: this.events.current_page,
                pageSize: this.events.per_page,
            },
            columns: [
                { title: 'Event Name', dataIndex: 'name', key: 'name', sorter: true, width: '20%' },
                { title: 'Location', dataIndex: 'location', key: 'location', sorter: true },
                { title: 'Start Date', dataIndex: 'start_date', key: 'start_date', sorter: true },
                { title: 'End Date', dataIndex: 'end_date', key: 'end_date', sorter: true },
                { title: 'RSVP Deadline', dataIndex: 'rsvp_deadline', key: 'rsvp_deadline' },
                { title: 'Guests', key: 'guests_count', align: 'center', width: 80 },
                { title: 'Status', key: 'status', align: 'center', width: 100 },
                { title: 'Actions', key: 'actions', align: 'center', width: 150 },
            ],
        };
    },
    computed: {
        eventsData() {
            return this.events.data || [];
        },
        totalEvents() {
            return this.events.total || 0;
        },
    },
    methods: {
        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            });
        },
        fetchEvents() {
            this.loading = true;
            router.get(route('grp.invitation-events.index'), this.currentFilters, {
                preserveState: true,
                preserveScroll: true,
                onFinish: () => { this.loading = false; },
                onError: () => { this.loading = false; },
            });
        },
        handleSearch() {
            this.pagination.current = 1;
            this.currentFilters.page = 1;
            this.currentFilters.search=this.filters.search
            this.fetchEvents();
        },
        handlePerPageChange(value) {
            this.currentFilters.per_page = value;
            this.pagination.current = 1;
            this.currentFilters.page = 1;
            this.fetchEvents();
        },
        handlePageChange(page, pageSize) {
            this.currentFilters.page = page;
            this.currentFilters.per_page = pageSize;
            this.fetchEvents();
        },
        handleTableChange(paginationInfo, filters, sorter) {
            if (sorter.field) {
                this.currentFilters.sort_field = sorter.field;
                this.currentFilters.sort_direction = sorter.order === 'ascend' ? 'asc' : 'desc';
            }
            this.fetchEvents();
        },
        createEvent() {
            this.$inertia.get(route('grp.invitation-events.create'));
        },
        viewEvent(id) {
            router.get(route('invitation-events.show', id));
        },
        editEvent(id) {
            console.log('Edit event with ID:', id);
            this.$inertia.get(route('grp.invitation-events.edit', id));
        },
        viewGuests(eventId) {
            router.get(route('invitation-guests.index', { event_id: eventId }));
        },
        deleteEvent(record) {
            Modal.confirm({
                title: 'Delete Event',
                content: `Are you sure you want to delete "${record.name}"? All guests under this event will also be deleted.`,
                okText: 'Yes, Delete',
                okType: 'danger',
                cancelText: 'Cancel',
                onOk: async () => {
                    try {
                        await router.delete(route('invitation-events.destroy', record.id));
                        message.success('Event deleted successfully');
                        this.fetchEvents();
                    } catch (error) {
                        message.error('Failed to delete event');
                    }
                },
            });
        },
    },
};
</script>

<style scoped>
/* TailwindCSS is already available */
</style>