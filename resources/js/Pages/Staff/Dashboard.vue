<template>

<StaffLayout title="Dashboard">
        <p>ss</p>
</StaffLayout>

</template>

<script >
import { Inertia } from '@inertiajs/inertia';
import StaffLayout from '@/Layouts/StaffLayout.vue';


export default {
    components: {
        StaffLayout,

    },
    props: ['offers'],
    data() {
        return {
            
        }
    },
    methods: {

    },
}

</script>