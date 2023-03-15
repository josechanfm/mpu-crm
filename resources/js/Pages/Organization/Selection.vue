<template>
        <p>Club Management</p>
        <OrganizationList :organizations="organizations"/>

        <div v-for="organization in organizations">
            <inertia-link :href="route('organizations.show',organization.id)">All clubs</inertia-link>
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