<template>
    <DepartmentLayout title="Dashboard" :department="departments[0]">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Management Dashboard department
            </h2>
        </template>
        <p>Department Management</p>
        {{ departments }}
    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';


export default {
    components: {
        DepartmentLayout,
    },
    props: ['departments'],
    data() {
        return {

        }
    },
    methods: {

    },
}
</script>