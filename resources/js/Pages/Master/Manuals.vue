<template>
    <MasterLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manuals
            </h2>
        </template>
        <a-button @click="createRecord" type="primary">Create</a-button>
        <a-table :dataSource="manuals.data" :columns="columns" :pagination="pagination" @change="onPaginationChange">
            <template #bodyCell="{column, text, record, index}">
                <template v-if="column.dataIndex == 'operation'">
                    <a-button @click="editRecord(record)">Edit</a-button>
                </template>
                <template v-else>
                    {{record[column.dataIndex]}}
                </template>
            </template>
        </a-table>

        <!-- Modal Start-->
        <a-modal :model="modal.data" v-model:open="modal.isOpen" :title="modal.title" width="60%" okText="Save" @ok="onFinish">
            <a-form ref="modalRef" :model="modal.data" layout="vertical" @finish="onFinish" id="modalForm">
                <a-form-item label="Route" name="route" :rules="[{required:true}]" >
                    <a-input type="inpuut" v-model:value="modal.data.route"/>
                </a-form-item>
                <a-form-item label="Re-Route" name="reroute" >
                    <a-input type="inpuut" v-model:value="modal.data.reroute"/>
                </a-form-item>
                <a-form-item label="Title" name="title" :rules="[{required:true}]" >
                    <a-input type="inpuut" v-model:value="modal.data.title"/>
                </a-form-item>
                <a-form-item label="Content" name="content" :rules="[{required:true}]">
                    <quill-editor
                        v-model="modal.data.content"
                        style="min-height: 200px"
                    />
                </a-form-item>
            </a-form>
            <!-- <template #footer>
                <a-button key="back" @click="handleCancel">Return</a-button>
                <a-button v-if="modal.mode == 'EDIT'" key="Update" type="primary" @click="updateRecord()">Update</a-button>
                <a-button v-if="modal.mode == 'CREATE'" key="Store" type="primary" @click="storeRecord()">Add</a-button>
            </template> -->
        </a-modal>
        <!-- Modal End-->

    </MasterLayout>

</template>

<script>
import MasterLayout from '@/Layouts/MasterLayout.vue';
import QuillEditor from "@/Components/QuillEditor.vue";
import dayjs from 'dayjs';

export default {
    components: {
        MasterLayout,
        QuillEditor,
        dayjs,
    },
    props: ['manuals'],
    data() {
        return {
            dateFormat:'YYYY-MM-DD',
            pagination: {
                total: this.manuals.total,
                current: this.manuals.current_page,
                pageSize: this.manuals.per_page,
                defaultPageSize:40,
                showSizeChanger:true,
                pageSizeOptions:['10','20','30','40','50']
            },
            modal: {
                mode: null,
                isOpen: false,
                title: 'Menual Item',
                data: {}
            },
            columns:[
                {
                    title: 'Parent',
                    dataIndex: 'parent_id',
                },{
                    title: 'Route',
                    dataIndex: 'route',
                },{
                    title: '操作',
                    dataIndex: 'operation',
                }
            ],

        }
    },
    created(){
        dayjs.locale('en');
    },

    methods: {
        onPaginationChange(page, filters, sorter) {
            console.log(page)
            this.$inertia.get(
                route("master.manuals.index"),
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
        closeModal() {
            this.isOpen = false;
            this.reset();
            this.editMode = false;
        },
        createRecord() {
            this.modal.data = {};
            this.modal.mode = 'CREATE';
            this.modal.title = "Create";
            this.modal.isOpen = true;
        },
        editRecord(record) {
            this.modal.data = { ...record };
            this.modal.mode = 'EDIT';
            this.modal.title = "Edit";
            this.modal.isOpen = true;
        },
        onFinish(values){
            this.$refs.modalRef.validateFields().then(()=>{
                if(this.modal.mode=='EDIT'){
                    this.$inertia.put(route('master.manuals.update',this.modal.data.id),this.modal.data, {
                        onSuccess: (page) => {
                            this.modal.isOpen = false;
                        },
                        onError: (error) => {
                            console.log(error);
                        }
                    });
                }
                if(this.modal.mode=='CREATE'){
                    this.$inertia.post(route('master.manuals.store'),this.modal.data, {
                        onSuccess: (page) => {
                            this.modal.isOpen = false;
                        },
                        onError: (error) => {
                            console.log(error);
                        }
                    });
                }

            }).catch(err => {
                console.log(err);
            });

        },
    },
}
</script>
