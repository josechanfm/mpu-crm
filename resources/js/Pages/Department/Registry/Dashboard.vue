<template>
    <DepartmentLayout :title="department.name_zh" :department="department" :breadcrumb="breadcrumb">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Department
            </h2>
        </template>
        <a-typography-title :level="3">{{ department.name_zh }}</a-typography-title>
        <div v-html="department.landing_page"></div>
    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';

export default {
    components: {
        DepartmentLayout,
    },
    props: ['department'],
    data() {
        return {
            breadcrumb:[
                {label:"招生注冊處" ,url:null},
            ],
        }
    },
    methods: {

    },
}
</script>