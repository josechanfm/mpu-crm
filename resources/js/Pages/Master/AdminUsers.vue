<template>
    <MasterLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Admin users
            </h2>
        </template>

        <!-- Toolbar -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-3">
                <div class="w-full sm:w-80">
                    <a-input-search
                        v-model:value="searchQuery"
                        placeholder="Search username / name / email"
                        allow-clear
                    />
                </div>
                <a-button @click="resetFilters">Reset</a-button>
            </div>
            <a-button type="primary" @click="createRecord">Add Admin User</a-button>
        </div>

        <!-- Table -->
        <div class="container mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <a-table
                    ref="tableRef"
                    :dataSource="displayedUsers"
                    :columns="columns"
                    :row-key="record => record.id"
                    :pagination="{
                        pageSize: 10,
                        showSizeChanger: true,
                        showTotal: (total) => `Total ${total} users`,
                    }"
                >
                    <template #bodyCell="{ column, record }">
                        <!-- Operations -->
                        <template v-if="column.dataIndex === 'operation'">
                            <a-button @click="editRecord(record)">Edit</a-button>
                        </template>
                        
                        <!-- Roles -->
                        <template v-else-if="column.dataIndex === 'manage_departments'">
                            <ol class="list-decimal pl-4">
                                <li v-for="role in record.roles" :key="role.id">
                                    {{ role.name }}
                                </li>
                            </ol>
                        </template>

                        <!-- Direct Permissions -->
                        <template v-else-if="column.dataIndex === 'direct_permissions'">
                            <div class="flex flex-wrap gap-1">
                                <a-tag v-for="permission in record.permissions" :key="permission.id" color="blue">
                                    {{ permission.name }}
                                </a-tag>
                                <span v-if="!record.permissions || record.permissions.length === 0" class="text-gray-400 text-sm italic">
                                    None
                                </span>
                            </div>
                        </template>

                        <!-- Default -->
                        <template v-else>
                            {{ record[column.dataIndex] }}
                        </template>
                    </template>
                </a-table>
            </div>
        </div>

        <!-- Form Component -->
        <AdminUserForm
            v-model:open="formOpen"
            :mode="formMode"
            :record="formRecord"
            :roles="roles"
            :departments="departments"
            :permissions="permissions" 
            @saved="onSaved"
        />
    </MasterLayout>
</template>

<script>
import MasterLayout from '@/Layouts/MasterLayout.vue';
import AdminUserForm from './AdminUserForm.vue';

export default {
    components: { MasterLayout, AdminUserForm },
    // Restored 'departments' back into props array
    props: ['roles', 'departments', 'adminUsers', 'permissions'],
    data() {
        return {
            searchQuery: '',
            formOpen: false,
            formMode: 'CREATE',
            formRecord: null,
        };
    },
    computed: {
        columns() {
            return [
                {
                    title: 'Username',
                    dataIndex: 'username',
                    sorter: (a, b) => (a.username || '').localeCompare(b.username || ''),
                },
                {
                    title: 'Display Name',
                    dataIndex: 'name',
                    sorter: (a, b) => (a.name || '').localeCompare(b.name || ''),
                },
                {
                    title: 'Email',
                    dataIndex: 'email',
                    sorter: (a, b) => (a.email || '').localeCompare(b.email || ''),
                },
                {
                    title: 'Manage Roles',
                    dataIndex: 'manage_departments',
                    filters: this.roles.map(r => ({ text: r.name, value: r.name })),
                    onFilter: (value, record) =>
                        (record.roles || []).some(r => r.name === value),
                    sorter: (a, b) =>
                        (a.roles?.length || 0) - (b.roles?.length || 0),
                },
                {
                    title: 'Direct Permissions',
                    dataIndex: 'direct_permissions',
                    filters: this.permissions.map(p => ({ text: p.name, value: p.name })),
                    onFilter: (value, record) =>
                        (record.permissions || []).some(p => p.name === value),
                    sorter: (a, b) =>
                        (a.permissions?.length || 0) - (b.permissions?.length || 0),
                },
                {
                    title: '操作',
                    dataIndex: 'operation',
                    key: 'operation',
                },
            ];
        },

        displayedUsers() {
            const q = this.searchQuery.trim().toLowerCase();
            if (!q) return this.adminUsers;
            return this.adminUsers.filter(u =>
                [u.username, u.name, u.email]
                    .filter(Boolean)
                    .some(v => v.toLowerCase().includes(q))
            );
        },
    },
    methods: {
        createRecord() {
            this.formRecord = null;
            this.formMode = 'CREATE';
            this.formOpen = true;
        },
        editRecord(record) {
            this.formRecord = record;
            this.formMode = 'EDIT';
            this.formOpen = true;
        },
        onSaved() {
            this.formRecord = null;
        },
        resetFilters() {
            this.searchQuery = '';
            this.$refs.tableRef?.clearFilters?.();
            this.$refs.tableRef?.clearSorters?.();
        },
    },
};
</script>