<template>
    <StaffLayout title="Dashboard">
    <div class="h-64 bg-gradient-to-tr from-green-800 to-green-500 rounded-md flex items-center">
      <div class="ml-20">
        <h2 class="text-white text-4xl">General Staff</h2>
        <p class="text-white mt-4 capitalize tracking-wider leading-7">Staff Landing page, which shows general information
          for both Administration and Teaching staff.</p>
        <a href="#"
          class="uppercase inline-block mt-8 text-sm bg-white py-2 px-4 rounded font-semibold hover:bg-indigo-100">Comming
          soon..!.</a>
      </div>
    </div>
    <a-row class="pt-5">
      <template v-for="form in forms" :key="form.id">
        <a-col :xs="{span: 24}" :lg="{span: 8}">
          <a-card :title="form.title" :bordered="false" style="width: 100%">
            <template #extra>
              <inertia-link :href="route('staff.forms.show', { form: form.id })" type="primary">
                Apply
              </inertia-link>
            </template>
            <div class="flex items-start">
              <img v-if="form.thumbnail" :src="form.thumbnail" alt="Thumbnail" class="w-1/3 mr-4" />
              <div class="flex-1">
                <div v-html="form.description" />
              </div>
            </div>
          </a-card>
        </a-col>
      </template>

    </a-row>
    
    <div v-if="$page.props.currentUser.roles.includes('master')">
      <a :href="route('master')">Master Dashboard</a>
    </div>

    <div v-if="$page.props.currentUser.roles.includes('admin')">
      <ol>
        <li v-for="department in departments">
          <a :href="route(department.default_route)">
            {{ department.abbr }} - {{ department.name_zh }}
          </a>
        </li>
      </ol>
      <p class="text-red-500">This list only show for admin role.</p>
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
  props: ['departments','forms'],
  data() {
    return {

    }
  },
  methods: {

  },
}

</script>