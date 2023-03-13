<template>
    <OrganizationLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Members
            </h2>
        </template>
            <a-table :dataSource="members" :columns="columns">
                <template #bodyCell="{column, text, record, index}">
                    <template v-if="column.dataIndex=='operation'">
                        <a-button @click="viewRecord(record)">View</a-button>
                    </template>
                    <template v-else-if="column.pivot">
                        {{record.pivot[column.dataIndex]}}
                    </template>
                    <template v-else>
                        {{record[column.dataIndex]}}
                    </template>
                </template>
            </a-table>

        <!-- Modal Start-->
        <a-modal v-model:visible="modal.isOpen" :title="modal.title" width="60%" >
        <a-form
            ref="modalRef"
            :model="modal.data"
            name="Teacher"
            :label-col="{ span: 8 }"
            :wrapper-col="{ span: 16 }"
            autocomplete="off"
            :rules="rules"
            :validate-messages="validateMessages"
        >
            <a-input type="hidden" v-model:value="modal.data.id"/>
            <a-form-item label="姓名(中文)" name="name_zh">
                <a-input v-model:value="modal.data.name_zh" />
            </a-form-item>
            <a-form-item label="姓名(外文)" name="name_zh">
                <a-input v-model:value="modal.data.name_fn" />
            </a-form-item>
            <a-form-item label="別名" name="nickname">
                <a-input v-model:value="modal.data.nickname" />
            </a-form-item>
            <a-form-item label="手機" name="mobile">
                <a-input v-model:value="modal.data.mobile" />
            </a-form-item>
        </a-form>
        <template #footer>
            <a-button v-if="modal.mode=='EDIT'" key="Update" type="primary"  @click="updateRecord()">Update</a-button>
            <a-button v-if="modal.mode=='CREATE'"  key="Store" type="primary" @click="storeRecord()">Add</a-button>
        </template>
    </a-modal>    
    <!-- Modal End-->
    </OrganizationLayout>

</template>

<script>
import OrganizationLayout from '@/Layouts/OrganizationLayout.vue';
import { defineComponent, reactive } from 'vue';

export default {
    components: {
        OrganizationLayout,
    },
    props: ['organization','certificate','members'],
    data() {
        return {
            modal:{
                isOpen:false,
                data:{},
                title:"Modal",
                mode:""
            },
            columns:[
                {
                    title: 'Display_name',
                    dataIndex: 'display_name',
                    pivot:true
                },{
                    title: 'Number',
                    dataIndex: 'number_display',
                    pivot:true
                },{
                    title: 'Issue Date',
                    dataIndex: 'issue_date',
                    pivot:true
                },{
                    title: 'Valid From',
                    dataIndex: 'valid_from',
                    pivot:true
                },{
                    title: 'Valid Until',
                    dataIndex: 'valid_until',
                    pivot:true
                },{
                    title: 'Action',
                    dataIndex: 'operation',
                    key: 'operation',
                },
            ],
            rules:{
                name_zh:{required:true},
                mobile:{required:true},
                state:{required:true},
            },
            validateMessages:{
                required: '${label} is required!',
                types: {
                    email: '${label} is not a valid email!',
                    number: '${label} is not a valid number!',
                },
                number: {
                    range: '${label} must be between ${min} and ${max}',
                },
            },
            labelCol: {
                style: {
                width: '150px',
                },
            },
        }
    },
    created(){
    },
    methods: {
        viewRecord(record){
            this.modal.data={...record};
            this.modal.mode="EDIT";
            this.modal.title="修改";
            this.modal.isOpen=true;
        },

    },
}
</script>