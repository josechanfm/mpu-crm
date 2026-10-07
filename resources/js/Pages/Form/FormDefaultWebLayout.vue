<template>
    <WebLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                表格管理
            </h2>
        </template>

        <FormDefaultCore :form="form" />
    </WebLayout>
</template>

<script>
import WebLayout from '@/Layouts/WebLayout.vue';
import FormDefaultCore from './FormDefaultCore.vue';

export default {
    components: { WebLayout, FormDefaultCore },
    props: {
        form: { type: Object, required: true },
    },
};
</script>