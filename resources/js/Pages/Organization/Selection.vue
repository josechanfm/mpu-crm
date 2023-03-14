<template>
        <p>Club Management</p>
        <OrganizationList :organizations="organizations"/>
        <inertia-link href="/manage/organizations">All clubs</inertia-link>

        <div>
            <inertia-link :href="route('organizations')">All clubs</inertia-link>
        </div>
</template>

<script>
import OrganizationLayout from '@/Layouts/OrganizationLayout.vue';
import OrganizationList from '@/Components/Organization/OrganizationList.vue';


export default {
    components: {
        OrganizationLayout,
        OrganizationList,
    },
    props: ['organizations'],
    data() {
        return {

        }
    },
    methods: {

    },
}
</script>