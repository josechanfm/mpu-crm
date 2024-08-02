<template>
    <DepartmentLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Department
            </h2>
        </template>
        <ul>
            <li v-for="vacancy in vacancies">
                    {{ vacancy }}
            </li>
        </ul>
    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';


export default {
    components: {
        DepartmentLayout,
    },
    props: ['vacancies'],
    data() {
        return {

        }
    },
    methods: {

    },
}
</script>