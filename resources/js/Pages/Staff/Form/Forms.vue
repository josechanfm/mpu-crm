<template>
    <StaffLayout title="Dashboard">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg ">
                <a-table :dataSource="forms" :columns="columns">
                    <template #bodyCell="{column, text, record, index}">
                        <template v-if="column.dataIndex=='operation'">
                            <a @click="toApply(record)">Apply</a>
                        </template>
                        <template v-else-if="column.dataIndex=='for_staff'">
                            <span v-if="text=='1'">Staff only</span>
                            <span v-else>Public</span>
                        </template>
                        <template v-else>
                            {{record[column.dataIndex]}}
                        </template>
                    </template>
                </a-table>
            </div>
            <p>Includes forms for staffs and public without login.</p>
    </StaffLayout>

</template>

<script>
import StaffLayout from '@/Layouts/StaffLayout.vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    components: {
        StaffLayout,
    },
    props: ['forms'],
    data() {
        return {
            columns:[
                {
                //     title: 'Name',
                //     dataIndex: 'name',
                // },{
                    title: 'Title',
                    dataIndex: 'title',
                // },{
                //     title: 'Login',
                //     dataIndex: 'require_login',
                },{
                    title: 'For staff',
                    dataIndex: 'for_staff',
                // },{
                //     title: 'Published',
                //     dataIndex: 'published',
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
        toApply(record){
            if(record.require_login==0 || this.$page.props.currentUser){
                Inertia.get(route('staff.forms.show',record.id));
            }else{
                alert("login required");
            }
        }
    },
}
</script>