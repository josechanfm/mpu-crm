<template>
  <StaffLayout title="Dashboard">
    <div class="h-96 bg-gradient-to-tr from-green-800 to-green-500 rounded-md flex items-center">
      <div class="ml-20">
        <h2 class="text-white text-4xl">General Staff</h2>
        <p class="text-indigo-100 mt-4 capitalize font-thin tracking-wider leading-7">This page is for general MPU staff
          who DO NOT belongs to any administration department or faculty.</p>

        <a href="#"
          class="uppercase inline-block mt-8 text-sm bg-white py-2 px-4 rounded font-semibold hover:bg-indigo-100">get
          start</a>
      </div>
    </div>
  </StaffLayout>
</template>

<script >
import { Inertia } from '@inertiajs/inertia';
import StaffLayout from '@/Layouts/StaffLayout.vue';


export default {
  components: {
    StaffLayout,

  },
  props: ['offers'],
  data() {
    return {

    }
  },
  methods: {

  },
}

</script>