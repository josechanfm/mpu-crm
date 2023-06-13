<template>
    <MemberLayout title="Dashboard" v-if="$page.props.user">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                表格例表
            </h2>
        </template>
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-8 p-4 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg ">
                <a-table :dataSource="forms" :columns="columns">
                    <template #bodyCell="{column, text, record, index}">
                        <template v-if="column.dataIndex=='operation'">
                            資料欄位
                            <inertia-link :href="route('forms.show',record.id)" class="float-right text-red-500">Apply</inertia-link>
                        </template>
                        <template v-else>
                            {{record[column.dataIndex]}}
                        </template>
                    </template>
                </a-table>
            </div>
        </div>
    </MemberLayout>
    <WebLayout title="Dashboard" v-else>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                表格例表
            </h2>
        </template>
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-8 p-4 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg ">
                <a-table :dataSource="forms" :columns="columns">
                    <template #bodyCell="{column, text, record, index}">
                        <template v-if="column.dataIndex=='operation'">
                            資料欄位
                            <inertia-link :href="route('forms.show',record.id)" class="float-right text-red-500">Apply</inertia-link>
                        </template>
                        <template v-else>
                            {{record[column.dataIndex]}}
                        </template>
                    </template>
                </a-table>
            </div>
        </div>
    </WebLayout>

</template>

<script>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import WebLayout from '@/Layouts/WebLayout.vue';

export default {
    components: {
        MemberLayout,
        WebLayout,
    },
    props: ['forms'],
    data() {
        return {
            columns:[
                {
                    title: 'Name',
                    dataIndex: 'name',
                },{
                    title: 'Title',
                    dataIndex: 'title',
                },{
                    title: 'For Member',
                    dataIndex: 'for_member',
                },{
                    title: 'Published',
                    dataIndex: 'published',
                },{
                    title: 'Action',
                    dataIndex: 'operation',
                    key: 'operation',
                },
            ],
            rules:{
                field:{required:true},
                label:{required:true},
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
    },
}
</script>