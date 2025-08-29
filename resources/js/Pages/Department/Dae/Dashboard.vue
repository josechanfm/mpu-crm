<template>
    <DepartmentLayout title="學生事務處" :department="department" :breadcrumb="breadcrumb">
        <a-typography-title :level="3" class="text-center mb-6">{{ department.name_zh }}</a-typography-title>
        
        <div class="container mx-auto pt-5">
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                <h4 class="font-semibold text-xl mb-4">Personnel Department Dashboard</h4>
                <p class="text-gray-600">Welcome to the DAE department! Here you can manage your souvenirs, orders, and pickups.</p>
            </div>

            <div v-html="department.landing_page" class="mb-6"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <inertia-link :href="route('dae.souvenirs.index')" as="button">
                    <a-button class="w-full hover:bg-gray-200 transition duration-300 text-xl h-16">Souvenirs</a-button>
                </inertia-link>
                <inertia-link :href="route('dae.souvenir.orders.index')">
                    <a-button class="w-full hover:bg-gray-200 transition duration-300 text-xl h-16">Order</a-button>
                </inertia-link>
                <inertia-link :href="route('dae.souvenir.pickup')">
                    <a-button class="w-full hover:bg-gray-200 transition duration-300 text-xl h-16">Pickup</a-button>
                </inertia-link>
            </div>
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
                { label: "學生事務處", url: null },
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