<template>
    <DepartmentLayout title="Dashboard" :department="department">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Management Dashboard department
            </h2>
        </template>
        <a-table :dataSource="filleds" :columns="columns">
            <template #bodyCell="{column, text, record, index}">
                <template v-if="column.dataIndex=='operation'">
                    <a-button @click="editRecord(record)">View</a-button>
                </template>
                <template v-else-if="column.dataIndex=='created_at'">
                    {{ record[column.dataIndex] }}
                </template>
                <template v-else>
                    {{record[column.dataIndex]}}
                </template>
            </template>
        </a-table>

    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';

export default {
    components: {
        DepartmentLayout,
    },
    props: ['department','form','filleds','columns'],
    data() {
        return {
            // columns:[
            //     {
            //         title: 'Number',
            //         dataIndex: 'uid',
            //     },{
            //         title: 'Username',
            //         dataIndex: 'username',
            //     },{
            //         title: 'Education',
            //         dataIndex: 'education',
            //     },{
            //         title: 'For Member',
            //         dataIndex: 'field',
            //     },{
            //         title: 'Published',
            //         dataIndex: 'published',
            //     },{
            //         title: 'Action',
            //         dataIndex: 'operation',
            //         key: 'operation',
            //     },
            // ],

        }
    },
    methods: {

    },
}
</script>