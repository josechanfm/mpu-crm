<template>
    <WebLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{form.title}}
            </h2>
        </template>
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-8 p-4 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg ">
                <div v-if="form.thanks">
                    <div id="pure-html" v-html="form.thanks"></div>
                </div>
                <div v-else>
                    <p>Thanks your very much!</p>
                    <p>Your application reference is: {{ filled.uid }}</p>
                </div>
            </div>
        </div>
    </WebLayout>

</template>

<script>
import WebLayout from '@/Layouts/WebLayout.vue';

export default {
    components: {
        WebLayout,
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

<style>
#pure-html {
  all: initial
}

#pure-html * {
  all: revert;
}
</style>