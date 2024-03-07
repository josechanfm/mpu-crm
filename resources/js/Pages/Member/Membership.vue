<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard..
            </h2>
        </template>
    </AppLayout>
</template>


<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import MembershipCard from '@/Components/Member/MembershipCard.vue';

export default {
    components: {
        AppLayout,
        MembershipCard,
    },
    props: ['member'],
    data() {
        return {
        }
    },
    created() {
    },
    methods: {

    },
}
</script>