<script setup>
import WebLayout from '@/Layouts/WebLayout.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
});
</script>

<template>
  <WebLayout title="Dashboard">
    <div class="bg-gradient-to-tr from-indigo-800 to-indigo-500 rounded-md flex items-center">
      <div class="pt-5 pb-5 ml-5 mr-5 md:ml-20 md:w-3/5">
        <h2 class="text-white text-4xl">General Services...</h2>
        <p class="text-indigo-100 mt-4 capitalize font-thin tracking-wider leading-7">The establishment of comprehensive services, in addition to providing convenience, can also reduce the consumption of human resources and increase work efficiency. At the same time, it can also provide more comprehensive services and improve the quality of campus management.</p>
        <a href="#"
          class="uppercase inline-block mt-8 text-sm bg-white py-2 px-4 rounded font-semibold hover:bg-indigo-100"> Learn More</a>
      </div>

    </div>
  </WebLayout>
</template>
