<template>
    <DepartmentLayout title="人事處首頁" :department="department" :breadcrumb="breadcrumb">
        <a-typography-title :level="3">{{ department.name_zh }}</a-typography-title>
        <div class="container mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                Personnel department Dashboard
            </div>
            <div v-html="department.landing_page"></div>
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
            breadcrumb:[
                {label:"人事處" ,url:null},
            ],

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