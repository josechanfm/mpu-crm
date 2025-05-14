<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/inertia-vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import MemberMenu from '@/Components/Member/MemberMenu.vue';
import { usePage } from "@inertiajs/inertia-vue3";
import { loadLanguageAsync } from "laravel-vue-i18n";

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);
const page = usePage();
loadLanguageAsync(page.props.value.lang);

// Language options array
const languages = [
    { code: 'zh', label: 'Chinese' },
    { code: 'en', label: 'English' },
    { code: 'pt', label: 'Portuguese' },
];

const quitMasquerade = () => {
    axios.get(route('personnel.recruitment.application.quitMasquerade'));
    window.close();
}

const logout = () => {
    this.$inertia.post(route('logout'));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <div class="mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="shrink-0 flex items-center">
                                <inertia-link :href="route('member')">
                                    <ApplicationMark class="block h-9 w-auto" />
                                </inertia-link>
                            </div>
                            <MemberMenu />
                            
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <Dropdown align="right" width="20">
                                <template #trigger>
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="ml-2 -mr-0.5 h-4 w-4" viewBox="0 0 16 16">
                                            <path
                                                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z" />
                                        </svg>
                                    </button>
                                </template>

                                <template #content>
                                    <div class="w-30">
                                        <template v-for="language in languages" :key="language.code">
                                            <DropdownLink :href="route('language', language.code)">
                                                {{ $t(language.label.toLowerCase()) }}
                                            </DropdownLink>
                                        </template>
                                    </div>
                                </template>
                            </Dropdown>

                            <div class="ml-3 relative" v-if="$page.props.auth">
                                <Dropdown align="right" width="48">
                                    <template #content>
                                        <div class="block px-4 py-2 text-xs text-gray-400">Manage Account</div>
                                        <DropdownLink :href="route('member.profile')">Profile</DropdownLink>
                                        <div class="border-t border-gray-100" />
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button">Log Out</DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <div class="-mr-2 flex items-center sm:hidden">
                            <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none" @click="showingNavigationDropdown = !showingNavigationDropdown">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>

            <header v-if="$slots.header" class="bg-white shadow">
                <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <a-button v-if="$page.props.masquerade" @click="quitMasquerade" class="float-right" danger>Quit Masquerade</a-button>
                    <slot name="header" />
                </div>
            </header>

            <main>
                <slot />
            </main>
        </div>
    </div>
</template>