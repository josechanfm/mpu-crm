<template>
    <DepartmentLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Department
            </h2>
        </template>
        <ul>
            <li v-for="department in departments">
                <template v-if="department.default_route">
                    <a :href="route('manage.masqueradeAdminUser',department.id)">{{ department.name_zh }}</a>
                </template>
                <template v-else>
                    {{ department.name_zh }}                   
                </template>
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
    props: ['departments'],
    data() {
        return {

        }
    },
    methods: {

    },
}
</script>