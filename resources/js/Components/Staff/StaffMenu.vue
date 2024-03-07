<template>
  <div class="mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <div class="flex">
        <!-- Logo -->
        <div class="shrink-0 flex items-center">
          <Link :href="route('home')">
            <ApplicationMark class="block h-14 w-auto" />
          </Link>
        </div>
      </div>
      <!-- Hamburger -->
      <div class="-mr-2 flex items-center sm:hidden">
        <button
          class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition"
          @click="showingNavigationDropdown = !showingNavigationDropdown">
          <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{ 'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
              stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{ 'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
              stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      
      <div class="hidden sm:flex sm:items-center sm:ml-6">
        <a-menu v-model:selectedKeys="current" mode="horizontal">
          <template v-for="item in menuItems" :key="item.key">
            <a-menu-item>
              <template #icon>
                <component :is="item.icon" />
              </template>

              <template v-if="item.route">
                <inertia-link :href="route(item.route)" :target="item.target">{{ item.title }}</inertia-link>
              </template>

              <template v-else>
                <a :href="item.url" :target="item.target">{{ item.title }}</a>
              </template>
            </a-menu-item>
          </template>

          <template v-if="$page.props.currentUser.roles.length > 0">
            <template v-for="role in $page.props.currentUser.roles">
              <a-menu-item v-if="role==role.toUpperCase()">
                <inertia-link :href="route('manage.department.redirect', { roleName: role })">{{ role }}</inertia-link>
              </a-menu-item>
            </template>
          </template>
          <a-menu-item>
            <a @click="logout">登出</a>
          </a-menu-item>

        </a-menu>
      </div>
    </div>
            <!-- Responsive Navigation Menu -->
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
          class="sm:hidden bg-white">
          <div class="pt-2 pb-3 space-y-1">
            <ResponsiveNavLink :href="route('staff')">
              主頁
            </ResponsiveNavLink>
          </div>

          <!-- Responsive Settings Options -->
          <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="mt-3 space-y-1">
              <ResponsiveNavLink :href="route('staff')">
                Staff
              </ResponsiveNavLink>
              <form @submit.prevent="logout">
                <DropdownLink as="button">
                  登出
                </DropdownLink>
              </form>

            </div>
          </div>
        </div>

  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import { defineComponent, ref } from 'vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import ApplicationMark from '@/Components/ApplicationMark.vue';

import { HomeOutlined, VerifiedOutlined, AppstoreOutlined, IdcardOutlined, BankOutlined, EditOutlined, SettingOutlined } from '@ant-design/icons-vue';
export default defineComponent({
  components: {
    HomeOutlined,
    VerifiedOutlined,
    AppstoreOutlined,
    IdcardOutlined,
    BankOutlined,
    EditOutlined,
    SettingOutlined,
    DropdownLink,
    ResponsiveNavLink,
    ApplicationMark,
    Link
  },
  setup() {
    const showingNavigationDropdown = ref(false);
    const current = ref(['staff']);
    const menuItems = ref([
      {
        key: 'staff',
        icon: 'home-outlined',
        title: '主頁',
        route: 'staff',
        // },{
        //     key:'official-web',
        //     icon:'verified-outlined',
        //     title:'MPU',
        //     url:'https://www.mpu.edu.mo',
        //},{
        //     key:'courses',
        //     icon:'bank-outlined',
        //     title:'課程',
        //     route:'courses',
        // },{
        //     key:'forms.index',
        //     icon:'edit-outlined',
        //     title:"報名",
        //     route:'forms.index',
        // },{
        //     key:'admin.index',
        //     icon:'appstore-outlined',
        //     title:'行政管理',
        //     route:'admin.index',
        //     target:'_blank'
      },
    ]);
    return {
      current,
      menuItems,
      showingNavigationDropdown
    };
  },
  created() {
  },
  methods: {
    logout() {
      console.log("logout");
      Inertia.post(route('staff.logout'));
    }
  }

});
</script>

<style>
.ant-menu-root {
  padding-top: 10px;
}

.ant-menu-title-content {
  padding-top: 5px !important
}
</style>
