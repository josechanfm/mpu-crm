<template>
    <StaffLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                表格例表
            </h2>
        </template>
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-8 p-4 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg ">
                <div id="pure-html" v-if="form.thanks">
                        <div v-html="form.thanks" />
                </div>
                <div v-else>
                    <p>Thanks your very much!</p>
                    <p>Your application reference is: {{ filled.uid }}</p>
                </div>
                <inertia-link :href="route('staff')">Back To Forms</inertia-link>
            </div>
        </div>
    </StaffLayout>

</template>

<script>
import StaffLayout from '@/Layouts/StaffLayout.vue';

export default {
    components: {
        StaffLayout,
    },
    props: ['form','filled'],
    data() {
        return {
        }
    },
    created(){
    },
    methods: {
    },
}
</script>

<style scope>
#pure-html {
  all: initial
}

#pure-html * {
  all: revert;
}
</style>