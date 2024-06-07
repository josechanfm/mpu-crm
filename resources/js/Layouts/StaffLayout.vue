<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import StaffMenu from '@/Components/Staff/StaffMenu.vue';

defineProps({
    title: String,
});

</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <StaffMenu />   
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <div
                    class="bg-gray-100 sm:grid grid-cols-5 grid-rows-2 px-4 py-6 min-h-full lg:min-h-screen space-y-6 sm:space-y-0 sm:gap-4">
                    <div class="col-span-4">
                        <slot />
                    </div>

                    <div class="col-span-1 ">
                        <div class="bg-white py-3 px-4 rounded-lg flex justify-around items-center ">
                            <input type="text" placeholder="seach"
                                class=" bg-gray-100 rounded-md  outline-none pl-2 ring-indigo-700 w-full mr-2 p-2">
                            <span><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor ">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg></span>
                        </div>
                        <div class="bg-white  rounded-md">
                            <h1
                                class="text-center text-xl my-4  bg-white py-2 rounded-md border-b-2 cursor-pointer  text-gray-600">
                                Service</h1>
                            <div class="bg-white rounded-md list-none  text-center ">
                                <li class="py-3 border-b-2"><inertia-link
                                        :href="route('staff.forms.index')">Forms</inertia-link></li>
                                <li class="py-3 border-b-2">
                                    <a href="https://teldir.mpu.edu.mo" target="_blank"
                                        class="list-none  hover:text-indigo-600">Phone Book</a>
                                </li>
                                <li class="py-3 border-b-2">
                                    <a href="https://it.mpu.edu.mo" target="_blank"
                                        class="list-none  hover:text-indigo-600">IT Department</a>
                                </li>
                                <li class="py-3 border-b-2">
                                    <a href="https://www.mpu.edu.mo" target="_blank"
                                        class="list-none  hover:text-indigo-600">Official Website</a>
                                </li>
                                <li class="py-3 border-b-2">
                                    <inertia-link :href="route('api.services')">Api Services</inertia-link>
                                </li>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
