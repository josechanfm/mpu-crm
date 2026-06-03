<template>
    <DepartmentLayout :title="department.name_zh" :department="department" :breadcrumb="breadcrumb">
        
        <div class="pt-5">
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                <h4 class="font-semibold text-xl mb-4">Personnel Department Dashboard</h4>
                <p class="text-gray-600">Welcome to the DAE department! Here you can manage your souvenirs, orders, and pickups.</p>
            </div>

            <div v-html="department.landing_page" class="mb-6"></div>

        </div>

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
            breadcrumb: [
                { label: "公共關係辦公室", url: null },
            ],
        };
    },
    created() {
    },
    mounted() {
    },
    methods: {
    },
}
</script>