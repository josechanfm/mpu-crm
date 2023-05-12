<template>
        <p>Club Management</p>
        <DepartmenList :departmens="departmens"/>

        <div v-for="organization in departmens">
            <inertia-link :href="route('departmens.show',organization.id)">All clubs</inertia-link>
        </div>
</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';
import DepartmenList from '@/Components/Department/DepartmenList.vue';


export default {
    components: {
        DepartmentLayout,
        DepartmenList,
    },
    props: ['departmens'],
    data() {
        return {

        }
    },
    methods: {

    },
}
</script>