<template>
        <div class="mb-5">
            <a-form ref="editFormRef" :model="staff" name="editForm" :label-col="{ span: 6 }" :wrapper-col="{ span: 18 }" autocomplete="off" :rules="rules" :validate-messages="validateMessages">
                <a-form-item label="姓名(中文)" name="name_zh">
                    <a-input type="input" v-model:value="staff.name_zh" />
                </a-form-item>

                <a-form-item label="員工編號" name="staff_num">
                    <a-input type="input" v-model:value="staff.staff_num" />
                </a-form-item>

                <a-form-item label="姓名(外文)" name="name_pt">
                    <a-input type="input" v-model:value="staff.name_pt" />
                </a-form-item>

                <a-form-item label="電郵地址" name="email">
                    <a-input type="input" v-model:value="staff.email" />
                </a-form-item>

                <div class="flex justify-end">
                    <a-button type="default" @click="cancelEdit">取消</a-button>
                    <a-button type="primary" class="ml-3" @click="updateRecord">更新</a-button>
                </div>
            </a-form>
        </div>
</template>

<script>

export default {
    props: {
        staff: Object,  // Assuming this is passed as a prop from Laravel
        families: Object,  // Assuming this is passed as a prop from Laravel
    },
    data() {
        return {
            breadcrumb: [
                { label: "人事處首頁", url: route('personnel.dashboard') },
                { label: "編輯員工記錄", url: null },
            ],

            rules: {
                name_zh: { required: true, message: '請輸入姓名(中文)' },
                staff_num: { required: true, message: '請輸入員工編號' },
                email: { required: true, type: 'email', message: '請輸入有效的電郵地址' },
            },
            validateMessages: {
                required: '${label} 是必填項！',
                types: {
                    email: '請輸入有效的 ${label}！',
                },
            },
        };
    },
    methods: {
        updateRecord() {
            this.$refs.editFormRef.validateFields().then(() => {
                this.$inertia.patch(route('personnel.staffs.update', this.staff.id), this.staff, {
                    onSuccess: (page) => {
                        console.log('Record updated successfully:', page);
                    },
                    onError: (err) => {
                        console.error('Error updating record:', err);
                    },
                });
            }).catch(err => {
                console.log('Validation error:', err);
            });
        },
        cancelEdit() {
            // Redirect back to the previous page or a specific route
            this.$inertia.visit(route('personnel.staffs.index'));
        },
    },
};
</script>

<style scoped>
/* You can add your custom styles here */
</style>