<template>
        <p>Club Management</p>
        <DepartmentList :departments="departments"/>

        <div v-for="department in departmens">
            <inertia-link :href="route('departmens.show',department.id)">All clubs</inertia-link>
        </div>
</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';
import DepartmentList from '@/Components/Department/DepartmentList.vue';


export default {
    components: {
        DepartmentLayout,
        DepartmentList,
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