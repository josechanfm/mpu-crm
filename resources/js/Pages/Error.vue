<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    message:String

});
</script>

<template>
    <div class="p-5 bg-gray-200">
        <a href="/">
            <img src="/storage/images/mpu_banner.png" width="300" />
        </a>
    </div>
    <div v-html="message"/>
</template>
