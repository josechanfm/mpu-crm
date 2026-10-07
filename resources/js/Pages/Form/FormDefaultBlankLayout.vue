<template>
    <BlankLayout>
        <FormDefaultCore :form="form" />
    </BlankLayout>
</template>

<script>
import BlankLayout from '@/Layouts/BlankLayout.vue';
import FormDefaultCore from './FormDefaultCore.vue';

export default {
    components: { BlankLayout, FormDefaultCore },   
    props: {
        form: { type: Object, required: true },
    },
};
</script>