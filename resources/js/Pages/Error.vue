<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({
    message:String

});
</script>

<template>
    <div class="p-5 bg-gray-200">
        <a href="/">
            <img src="/images/mpu_banner.png" width="300" />
        </a>
    </div>
    <div v-html="message"/>
</template>
