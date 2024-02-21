<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link } from '@inertiajs/inertia-vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import StaffMenu from '@/Components/Staff/StaffMenu.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

// const switchToTeam = (team) => {
//     Inertia.put(route('current-team.update'), {
//         team_id: team.id,
//     }, {
//         preserveState: false,
//     });
// };

const logout = () => {
    Inertia.post(route('logout'));
};
</script>

<template>
    <div>
<header>
  <!-- navbar and menu -->
  <nav class="shadow">

  <div class="flex justify-between items-center py-6 px-10 container mx-auto">

    <div>
      <h1 class="text-2xl font-bold bg-gradient-to-tr from-indigo-600 to-green-600 bg-clip-text text-transparent hover:cursor-pointer">WIRE</h1>
    </div>

    <div>
      
      <div class="hover:cursor-pointer sm:hidden">
        <span class="h-1 rounded-full block w-8 mb-1 bg-gradient-to-tr from-indigo-600 to-green-600"></span>
        <span class="h-1 rounded-full block w-8 mb-1 bg-gradient-to-tr from-indigo-600 to-green-600"></span>
        <span class="h-1 rounded-full block w-8 mb-1 bg-gradient-to-tr from-indigo-600 to-green-600"></span>
      </div>
      <div class="flex items-center">

        <ul class="sm:flex space-x-4 hidden items-center">
          <li><a href="#" class="text-gray-700 hover:text-indigo-600 text-md ">Home</a></li>
          <li><a href="#" class="text-gray-700 hover:text-indigo-600 text-md ">About</a></li>
          <li><a href="#" class="text-gray-700 hover:text-indigo-600 text-md ">Services</a></li>
          <li><a href="#" class="text-gray-700 hover:text-indigo-600 text-md ">Products</a></li>
          <li><a href="#" class="text-gray-700 hover:text-indigo-600 text-md ">Contact</a></li>
        </ul>

        <div class="md:flex items-center hidden space-x-4 ml-8 lg:ml-12">
          <h1 class="text-text-gray-600  py-2 hover:cursor-pointer hover:text-indigo-600">
            <a @click="logout">登出</a>
        </h1>
        </div>
      </div>
    </div>
  </div>
  </nav>
</header>

        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('member')">
                                    <ApplicationMark class="block h-9 w-auto" />
                                </Link>
                            </div>
                            <StaffMenu />
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <div class="ml-3 relative">
                                <!-- Teams Dropdown -->
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                            </div>
                        </div>
                        
                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition" @click="showingNavigationDropdown = ! showingNavigationDropdown">
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
