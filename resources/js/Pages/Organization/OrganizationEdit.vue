<template>
    <OrganizationLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                課程規劃
            </h2>
        </template>

        <a-form :model="organization" v-bind="layout" name="nest-messages" :validate-messages="validateMessages"
            @finish="onFinish">
            <a-form-item :name="abbr" label="Abbr" >
                <a-input v-model:value="organization.abbr" />
            </a-form-item>
            <a-form-item :name="full_name" label="Full Name" >
                <a-input v-model:value="organization.full_name" />
            </a-form-item>
            <a-form-item :name="title" label="Title" >
                <a-input v-model:value="organization.title" />
            </a-form-item>
            <a-form-item :name="email" label="Email" >
                <a-input v-model:value="organization.email" />
            </a-form-item>
            <a-form-item :name="phone" label="Phone" >
                <a-input v-model:value="organization.phone" />
            </a-form-item>
            <a-form-item :name="address" label="Address" >
                <a-input v-model:value="organization.address" />
            </a-form-item>
            <a-form-item :name="country" label="Country" >
                <a-input v-model:value="organization.country" />
            </a-form-item>
            <a-form-item :name="href" label="Official Website" >
                <a-input v-model:value="organization.href" />
            </a-form-item>
            <a-form-item :name="avata" label="Avata" >
                <a-input v-model:value="organization.avata" />
            </a-form-item>
            <a-form-item :name="description" label="Description" >
                <a-input v-model:value="organization.description" />
            </a-form-item>
            <a-form-item :name="content" label="Content" >
                <a-input v-model:value="organization.content" />
            </a-form-item>
            <a-form-item :wrapper-col="{ ...layout.wrapperCol, offset: 8 }">
                <a-button type="primary" html-type="submit">Submit</a-button>
            </a-form-item>            
        </a-form>
    </OrganizationLayout>
</template>

<script>
import OrganizationLayout from '@/Layouts/OrganizationLayout.vue';
import { defineComponent, reactive } from 'vue';

export default {
    components: {
        OrganizationLayout,
    },
    props: ['organization'],
    data() {
        return {
            modal: {
                isOpen: false,
                data: {},
                title: "Modal",
                mode: ""
            },
            teacherStateLabels: {},
            columns: [
                {
                    title: '姓名(中文)',
                    dataIndex: 'first_name',
                }, {
                    title: '姓名(外文)',
                    dataIndex: 'last_name',
                }, {
                    title: '別名',
                    dataIndex: 'gender',
                }, {
                    title: '手機',
                    dataIndex: 'dob',
                }, {
                    title: '狀態',
                    dataIndex: 'state',
                }, {
                    title: '操作',
                    dataIndex: 'operation',
                    key: 'operation',
                },
            ],
            rules: {
                name_zh: { required: true },
                mobile: { required: true },
                state: { required: true },
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
            layout: {
                labelCol: {
                    span: 8,
                },
                wrapperCol: {
                    span: 16,
                },
            },
        }
    },
    created() {
    },
    methods: {
        createRecord() {
            this.modal.data = {};
            this.modal.mode = "CREATE";
            this.modal.title = "新增問卷";
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
                this.$inertia.post('/admin/teachers/', this.modal.data, {
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
            console.log(this.modal.data);
            this.$refs.modalRef.validateFields().then(() => {
                this.$inertia.patch('/admin/teachers/' + this.modal.data.id, this.modal.data, {
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
        deleteRecord(recordId) {
            console.log(recordId);
            if (!confirm('Are you sure want to remove?')) return;
            this.$inertia.delete('/admin/teachers/' + recordId, {
                onSuccess: (page) => {
                    console.log(page);
                },
                onError: (error) => {
                    console.log(error);
                }
            });
        },
        createLogin(recordId) {
            console.log('create login' + recordId);

        }
    },
}
</script>