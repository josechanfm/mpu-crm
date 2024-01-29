<template>
    <DepartmentLayout title="Dashboard" :department="department">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Department
            </h2>
        </template>
        <a-typography-title :level="3">{{ department.name_zh }}</a-typography-title>
        <p></p>
        <span v-if="is('DAMIA | admin | master')">
            DAMIA
        </span>
        <span v-if="is('PES | admin | master')">
            PES
        </span>
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
        }
    },
    created(){

    },
    mounted(){
    },
    methods: {

    },
}
</script>