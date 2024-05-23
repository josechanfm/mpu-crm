<script setup>
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, Link } from '@inertiajs/inertia-vue3';
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { usePage } from "@inertiajs/inertia-vue3";
import { loadLanguageAsync } from "laravel-vue-i18n";
import { getActiveLanguage } from "laravel-vue-i18n";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    title: String,
});
const showingNavigationDropdown = ref(false);
const page = usePage();
loadLanguageAsync(page.props.value.lang);
const logout = () => {
    Inertia.post(route("logout"));
};
const quitMasquerade = () => {
    axios.get(route('personnel.recruitment.application.quitMasquerade'))
    window.close();
}

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
                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{
                        hidden: !showingNavigationDropdown,
                        'inline-flex': showingNavigationDropdown,
                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <a-button v-if="$page.props.masquerade" @click="quitMasquerade">
                        Quit Masquerade
                    </a-button>
                    <div class="flex items-center">
                        <div class="sm:flex items-center hidden space-x-4 ml-8 lg:ml-12">
                            <template v-if="$page.props.user">
                                <inertia-link :href="route('recruitment.userProfile')">{{ $t('rec.profile') }}</inertia-link>
                                <a :href="route('recruitment.logout')">{{ $t('logout')  }}</a>
                            </template>
                            <template v-else>
                                <inertia-link :href="route('recruitment.userProfile')">{{ $t('login')}}</inertia-link>
                            </template>
                            <Dropdown align="right" width="20">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="ml-2 -mr-0.5 h-4 w-4" viewBox="0 0 16 16">
                                                <path
                                                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <div class="w-30">
                                        <DropdownLink :href="route('language', 'zh')">
                                            {{ $t("chinese") }}
                                        </DropdownLink>
                                        <DropdownLink :href="route('language', 'en')">
                                            {{ $t("english") }}
                                        </DropdownLink>
                                        <DropdownLink :href="route('language', 'pt')">
                                            {{ $t("portuguese") }}
                                        </DropdownLink>
                                    </div>
                                </template>
                            </Dropdown>
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
            <!-- Page Content -->
            <main>
                <div class="py-12">
                    <div class="mx-auto sm:px-6 lg:px-8">
                        <slot />
                    </div>
                </div>
            </main>
        </section>

    </main>
</template>
