<template>
    <MasterLayout title="Roles & Permissions">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Roles & Permissions
            </h2>
        </template>

        <div class="bg-white shadow rounded-lg p-6">
            <a-tabs v-model:activeKey="activeTab">
                
                <!-- ==================== ROLES TAB ==================== -->
                <a-tab-pane key="roles" tab="Roles">
                    <div class="mb-4 flex justify-end">
                        <a-button type="primary" @click="openRoleModal()">Add Role</a-button>
                    </div>
                    
                    <a-table :dataSource="roles" :columns="roleColumns" :row-key="record => record.id">
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.dataIndex === 'permissions'">
                                <div class="flex flex-wrap gap-1">
                                    <a-tag v-for="perm in record.permissions" :key="perm.id" color="blue">
                                        {{ perm.name }}
                                    </a-tag>
                                    <span v-if="record.permissions.length === 0" class="text-gray-400 text-sm italic">No permissions</span>
                                </div>
                            </template>
                            <template v-if="column.dataIndex === 'operation'">
                                <a-space>
                                    <a-button size="small" @click="openRoleModal(record)">Edit</a-button>
                                    <a-popconfirm title="Are you sure?" @confirm="deleteRole(record.id)">
                                        <a-button size="small" danger :disabled="record.name === 'Super Admin'">Delete</a-button>
                                    </a-popconfirm>
                                </a-space>
                            </template>
                        </template>
                    </a-table>
                </a-tab-pane>

                <!-- ==================== PERMISSIONS TAB ==================== -->
                <a-tab-pane key="permissions" tab="Permissions">
                    <div class="mb-4 flex justify-end">
                        <a-button type="primary" @click="openPermissionModal()">Add Permission</a-button>
                    </div>

                    <a-table :dataSource="permissions" :columns="permissionColumns" :row-key="record => record.id">
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.dataIndex === 'operation'">
                                <a-space>
                                    <a-button size="small" @click="openPermissionModal(record)">Edit</a-button>
                                    <a-popconfirm title="Are you sure? This will remove it from all roles." @confirm="deletePermission(record.id)">
                                        <a-button size="small" danger>Delete</a-button>
                                    </a-popconfirm>
                                </a-space>
                            </template>
                        </template>
                    </a-table>
                </a-tab-pane>

            </a-tabs>
        </div>

        <!-- ==================== ROLE MODAL ==================== -->
        <a-modal v-model:open="roleModal.isOpen" :title="roleModal.isEdit ? 'Edit Role' : 'Create Role'" @ok="submitRole" :confirmLoading="roleModal.loading">
            <a-form :model="roleModal.form" layout="vertical">
                <a-form-item label="Role Name" required>
                    <a-input v-model:value="roleModal.form.name" />
                </a-form-item>
                <a-form-item label="Permissions">
                    <a-checkbox-group v-model:value="roleModal.form.permissions" class="w-full">
                        <a-row :gutter="[16, 16]">
                            <a-col :span="12" v-for="perm in permissions" :key="perm.id">
                                <a-checkbox :value="perm.id">{{ perm.name }}</a-checkbox>
                            </a-col>
                        </a-row>
                    </a-checkbox-group>
                </a-form-item>
            </a-form>
        </a-modal>

        <!-- ==================== PERMISSION MODAL ==================== -->
        <a-modal v-model:open="permModal.isOpen" :title="permModal.isEdit ? 'Edit Permission' : 'Create Permission'" @ok="submitPermission" :confirmLoading="permModal.loading">
            <a-form :model="permModal.form" layout="vertical">
                <a-form-item label="Permission Name (e.g. edit-users)" required>
                    <a-input v-model:value="permModal.form.name" />
                </a-form-item>
            </a-form>
        </a-modal>

    </MasterLayout>
</template>

<script>
import MasterLayout from '@/Layouts/MasterLayout.vue';

export default {
    components: { MasterLayout },
    props: {
        roles: Array,
        permissions: Array
    },
    data() {
        return {
            activeTab: 'roles',
            roleColumns: [
                { title: 'Role Name', dataIndex: 'name' },
                { title: 'Permissions', dataIndex: 'permissions' },
                { title: 'Actions', dataIndex: 'operation', width: 200 },
            ],
            permissionColumns: [
                { title: 'ID', dataIndex: 'id', width: 80 },
                { title: 'Permission Name', dataIndex: 'name' },
                { title: 'Actions', dataIndex: 'operation', width: 200 },
            ],
            
            // Role Modal State
            roleModal: {
                isOpen: false,
                isEdit: false,
                loading: false,
                form: { id: null, name: '', permissions: [] }
            },
            
            // Permission Modal State
            permModal: {
                isOpen: false,
                isEdit: false,
                loading: false,
                form: { id: null, name: '' }
            }
        }
    },
    methods: {
        // ----- ROLE METHODS -----
        openRoleModal(record = null) {
            if (record) {
                this.roleModal.isEdit = true;
                this.roleModal.form = {
                    id: record.id,
                    name: record.name,
                    permissions: record.permissions.map(p => p.id)
                };
            } else {
                this.roleModal.isEdit = false;
                this.roleModal.form = { id: null, name: '', permissions: [] };
            }
            this.roleModal.isOpen = true;
        },
        submitRole() {
            this.roleModal.loading = true;
            const payload = { 
                name: this.roleModal.form.name, 
                permissions: this.roleModal.form.permissions 
            };
            
            // Set up the Inertia callbacks
            const options = {
                onSuccess: () => {
                    // Close the modal only if the request was successful
                    this.roleModal.isOpen = false;
                },
                onFinish: () => {
                    // Always turn off loading, whether it succeeded or failed
                    this.roleModal.loading = false;
                }
            };
            
            if (this.roleModal.isEdit) {
                this.$inertia.put(route('master.roles.update', this.roleModal.form.id), payload, options);
            } else {
                this.$inertia.post(route('master.roles.store'), payload, options);
            }
        },
        deleteRole(id) {
            this.$inertia.delete(route('master.roles.destroy', id));
        },

        // ----- PERMISSION METHODS -----
        openPermissionModal(record = null) {
            if (record) {
                this.permModal.isEdit = true;
                this.permModal.form = { id: record.id, name: record.name };
            } else {
                this.permModal.isEdit = false;
                this.permModal.form = { id: null, name: '' };
            }
            this.permModal.isOpen = true;
        },
        submitPermission() {
            this.permModal.loading = true;
            const payload = { name: this.permModal.form.name };

            const options = {
                onSuccess: () => {
                    this.permModal.isOpen = false;
                },
                onFinish: () => {
                    this.permModal.loading = false;
                }
            };

            if (this.permModal.isEdit) {
                this.$inertia.put(route('master.permissions.update', this.permModal.form.id), payload, options);
            } else {
                this.$inertia.post(route('master.permissions.store'), payload, options);
            }
        },
        deletePermission(id) {
            this.$inertia.delete(route('master.permissions.destroy', id));
        }
    }
}
</script>