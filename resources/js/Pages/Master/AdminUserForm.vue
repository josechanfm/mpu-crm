<!-- resources/js/Components/AdminUserForm.vue -->
<template>
  <a-modal v-model:open="isOpen" :title="title" width="60%" :confirm-loading="loading">
    <a-form
      ref="formRef"
      :model="formData"
      :rules="rules"
      :validate-messages="validateMessages"
      name="AdminUser"
      layout="vertical"
      autocomplete="off"
    >
      <a-form-item label="Username" name="username">
        <a-input v-model:value="formData.username" />
      </a-form-item>
      
      <a-form-item label="Display Name" name="name">
        <a-input v-model:value="formData.name" />
      </a-form-item>
      
      <a-form-item label="Email" name="email">
        <a-input v-model:value="formData.email" />
      </a-form-item>
      
      <a-form-item label="Password" name="password">
        <a-input-password 
          v-model:value="formData.password" 
          :placeholder="mode === 'EDIT' ? 'Leave blank to keep current password' : 'Enter password'" 
        />
      </a-form-item>
      
      <a-form-item label="Retype Password" name="retype_password">
        <a-input-password 
          v-model:value="formData.retype_password" 
          :placeholder="mode === 'EDIT' ? 'Leave blank to keep current password' : 'Retype password'" 
        />
      </a-form-item>
      
      <!-- Departments Field -->
      <a-form-item label="Departments" name="belong_departments">
        <a-select
          mode="multiple"
          v-model:value="formData.belong_departments"
          :options="departmentList"
          allow-clear
          placeholder="Select departments"
        />
      </a-form-item>

      <a-form-item label="Manage Roles" name="manage_departments">
        <a-select
          mode="multiple"
          v-model:value="formData.manage_departments"
          :options="roles"
          :field-names="{ value: 'name', label: 'name' }"
          allow-clear
        />
      </a-form-item>

      <!-- Direct Permissions -->
      <a-form-item label="Direct Permissions" name="direct_permissions">
        <a-select
          mode="multiple"
          v-model:value="formData.direct_permissions"
          :options="permissions"
          :field-names="{ value: 'name', label: 'name' }"
          placeholder="Assign extra permissions directly to this user"
          allow-clear
        />
      </a-form-item>
      
    </a-form>
    <template #footer>
      <a-button @click="isOpen = false">Cancel</a-button>
      <a-button
        v-if="mode === 'EDIT'"
        type="primary"
        :loading="loading"
        @click="updateRecord"
      >Update</a-button>
      <a-button
        v-if="mode === 'CREATE'"
        type="primary"
        :loading="loading"
        @click="storeRecord"
      >Add</a-button>
    </template>
  </a-modal>
</template>

<script>
export default {
  name: 'AdminUserForm',
  props: {
    open: { type: Boolean, default: false },
    mode: { type: String, default: 'CREATE' }, // 'CREATE' | 'EDIT'
    record: { type: Object, default: null },
    roles: { type: Array, default: () => [] },
    departments: { type: Array, default: () => [] }, // Restored departments prop
    permissions: { type: Array, default: () => [] },
  },
  emits: ['update:open', 'saved'],
  data() {
    return {
      formData: {},
      loading: false,
      validateMessages: {
        required: '${label} is required!',
        types: {
          email: '${label} is not a valid email!',
          number: '${label} is not a valid number!',
        },
      },
    };
  },
  computed: {
    departmentList() {
      return this.departments.map(d => ({ value: d.id, label: d.abbr + "-" +d.name_zh  }));
    },
    isOpen: {
      get() { return this.open; },
      set(val) { this.$emit('update:open', val); },
    },
    title() {
      return this.mode === 'EDIT' ? '修改' : '新增';
    },
    rules() {
      const isCreate = this.mode === 'CREATE';
      return {
        username: { required: true },
        name: { required: true },
        email: { required: true, type: 'email' },
        password: { required: isCreate },
        retype_password: { required: isCreate },
      };
    },
  },
  watch: {
    open(newVal) {
      if (newVal) this.initForm();
    },
  },
  methods: {
    initForm() {
      this.loading = false;
      if (this.mode === 'EDIT' && this.record) {
        this.formData = { 
          ...this.record,
          password: '',
          retype_password: '',
        };
        // Map departments, roles, and permissions correctly for form inputs
        this.formData.belong_departments = this.record.departments.map(d => d.id);
        this.formData.manage_departments = (this.record.roles || []).map(r => r.name);
        this.formData.direct_permissions = (this.record.permissions || []).map(p => p.name);
      } else {
        this.formData = {
          username: '',
          name: '',
          email: '',
          password: '',
          retype_password: '',
          belong_departments: [],
          manage_departments: [],
          direct_permissions: [],
        };
      }
      
      this.$nextTick(() => this.$refs.formRef && this.$refs.formRef.clearValidate());
    },
    storeRecord() {
      this.$refs.formRef.validateFields().then(() => {
        this.loading = true;
        this.$inertia.post(route('master.adminUsers.store'), this.formData, {
          onSuccess: () => {
            this.loading = false;
            this.isOpen = false;
            this.$emit('saved');
          },
          onError: (err) => {
            this.loading = false;
            console.log(err);
          },
        });
      }).catch(err => console.log(err));
    },
    updateRecord() {
      this.$refs.formRef.validateFields().then(() => {
        this.loading = true;
        this.$inertia.patch(
          route('master.adminUsers.update', this.formData.id),
          this.formData,
          {
            onSuccess: () => {
              this.loading = false;
              this.isOpen = false;
              this.$emit('saved');
            },
            onError: (err) => {
              this.loading = false;
              console.log(err);
            },
          }
        );
      }).catch(err => console.log('error', err));
    },
  },
};
</script>