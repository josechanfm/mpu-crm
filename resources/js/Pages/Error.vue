<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({
    message:String

});
</script>

<template>
    {{ message }}

</template>
