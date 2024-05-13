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
      <a-col :xs="{span:24}" :lg="{span:8}">
        <a-card title="邀請出席預防詐騙講座" :bordered="false" style="width: 100%">
          <p>日期：2024年3月8日</p>
          <p>時間：下午3時至4時</p>
          <p>地點：匯智樓一號演講廳</p>
          <p>語言：廣州話</p>
          <div>
            <inertia-link :href="route('staff.forms.show', { form: 2 })"
              class="ant-btn ant-btn-primary">報名Apply</inertia-link>
          </div>
        </a-card>
      </a-col>
      <a-col :span="8">
      </a-col>
      <a-col :span="8">
      </a-col>
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