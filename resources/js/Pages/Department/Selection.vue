<template>
        <DepartmentLayout title="Dashboard" :department="departments[0]">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Management Dashboard department
            </h2>
        </template>
        <p>You belongs to more than one department Please select which one you wanna to get into?</p>
        <ol class="list-decimal">
            <li v-for="department in departments">
                <inertia-link :href="route('manage.departments.show',department.id)">
                    {{ department.abbr }} - {{ department.name_zh }}
                </inertia-link>
            </li>
        </ol>

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