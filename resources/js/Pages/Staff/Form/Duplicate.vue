<template>
    <WebLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                重複報名
            </h2>
        </template>
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-8 p-4 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg ">
                <p>你的報名資料已成功遞交.</p>
                <p>Your registration information has been submitted successfully.</p>
                <p> <a-typography-title :level="4">{{ form.title }}</a-typography-title></p>
                <p>遞交日期 Submission Date: {{ formatDate(entry.created_at) }}</p>

                <inertia-link :href="route('staff')">Back To Forms</inertia-link>
            </div>
        </div>
    </WebLayout>

</template>

<script>
import WebLayout from '@/Layouts/WebLayout.vue';
import dayjs from 'dayjs';

export default {
    components: {
        WebLayout,
    },
    props: ['form','entry'],
    data() {
        return {
        }
    },
    created(){
    },
    methods: {
        formatDate(date, format = 'YYYY-MM-DD HH:mm') {
            return dayjs(date).format(format);
        },
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