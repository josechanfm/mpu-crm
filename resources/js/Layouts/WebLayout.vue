<script setup>
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, Link } from '@inertiajs/inertia-vue3';
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { GlobalOutlined } from '@ant-design/icons-vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    title: String,
});
const showingNavigationDropdown = ref(false);
</script>

<template>
    <!-- component -->
    <!-- Header -->
    <header>
        <!-- navbar and menu -->
        <nav class="shadow">
            <div class="flex justify-between items-center py-6 px-10 container mx-auto">
                <div class="shrink-0 flex items-center">
                        <Link :href="route('home')">
                            <ApplicationMark class="block h-14 w-auto" />
                        </Link>
                    </div>
                    
                <div>
                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 bg-white hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition"
                            @click="showingNavigationDropdown = !showingNavigationDropdown">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{
                                    hidden: showingNavigationDropdown,
                                    'inline-flex': !showingNavigationDropdown,
                                }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{
                                    hidden: !showingNavigationDropdown,
                                    'inline-flex': showingNavigationDropdown,
                                }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center">
                        <div class="sm:flex items-center hidden space-x-4 ml-8 lg:ml-12">
                            <a href="https://www.mpu.edu.mo" class="text-gray-700 hover:text-indigo-600 text-md ">MPU</a>
                            <a href="https://www.mpu.edu.mo/en/services.php" target="_blank"
                                class="text-gray-700 hover:text-indigo-600 text-md ">Services</a>
                            <inertia-link :href="route('login')">登入</inertia-link>
                            <inertia-link :href="route('staff.login')">教職員</inertia-link>
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                <GlobalOutlined />
                                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                    </template>

                                    <template #content>
                                        <DropdownLink as="a" href="/language/zh">
                                            中文
                                        </DropdownLink>
                                        <DropdownLink as="a" href="/language/en">
                                            English
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                class="sm:hidden bg-white">
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink href="#">
                        Main
                    </ResponsiveNavLink>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="mt-3 space-y-1">
                        <ul class="pl-5">
                            <li>
                                <a href="https://www.mpu.edu.mo" target="_blank"
                                    class="text-gray-700 hover:text-indigo-600 text-md ">
                                    MPU
                                </a>
                            </li>
                            <li>
                                <a href="https://www.mpu.edu.mo" target="_blank"
                                    class="text-gray-700 hover:text-indigo-600 text-md ">
                                    Services
                                </a>
                            </li>
                            <li>
                                <inertia-link :href="route('login')">登入</inertia-link>
                            </li>
                            <li>
                                <inertia-link :href="route('staff.login')">教職員</inertia-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <!-- Page Heading -->
    <header v-if="$slots.header" class="bg-gray-100 shadow">
        <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <slot name="header" />
        </div>
    </header>

    <main>
        <!-- section hero -->
        <section>
            <div
                class="bg-gray-100 sm:grid grid-cols-5 grid-rows-2 px-4 py-6 min-h-full lg:min-h-screen space-y-6 sm:space-y-0 sm:gap-4">

                <div class="col-span-4">
                    <!-- Page Content -->
                    <main>
                        <slot />
                    </main>

                </div>
                <div class="h-96 col-span-1 ">
                    <div class="bg-white py-3 px-4 rounded-lg flex justify-around items-center ">
                        <input type="text" placeholder="seach"
                            class=" bg-gray-100 rounded-md  outline-none pl-2 ring-indigo-700 w-full mr-2 p-2">
                        <span><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor ">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg></span>
                    </div>
                    <div class="bg-white  rounded-md">

                        <h1
                            class="text-center text-xl my-4  bg-white py-2 rounded-md border-b-2 cursor-pointer  text-gray-600">
                            Service</h1>
                        <div class="bg-white rounded-md list-none  text-center ">
                            <li class="py-3 border-b-2"><inertia-link :href="route('enquiry.index')">Enquiry</inertia-link>
                            </li>
                            <li class="py-3 border-b-2"><inertia-link :href="route('forms.index')">Forms</inertia-link></li>
                            <li class="py-3 border-b-2"><a href="https://www.mpu.edu.mo" target="_blank"
                                    class="list-none  hover:text-indigo-600">MPU Web</a>
                            </li>
                            <li class="py-3 border-b-2"><a href="https://it.mpu.edu.mo/" class="list-none hover:text-indigo-600">IT Department</a></li>
                            <li class="py-3"><a href="https://teldir.mpu.edu.mo" class="list-none  hover:text-indigo-600">Phone Book (Intranet)</a></li>
                        </div>
                    </div>
                </div>
            </div>

        </section>

</main></template>
