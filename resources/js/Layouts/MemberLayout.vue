<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/inertia-vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import MemberMenu from '@/Components/Member/MemberMenu.vue';
import { usePage } from "@inertiajs/inertia-vue3";
import { loadLanguageAsync } from "laravel-vue-i18n";
import { GlobalOutlined } from '@ant-design/icons-vue';

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
                                                <template v-for="language in languages" :key="language.code">
                                                    <DropdownLink :href="route('language', language.code)">
                                                        {{ $t(language.label.toLowerCase()) }}
                                                    </DropdownLink>
                                                </template>
                                            </template>
                                        </Dropdown>
                                    </div>


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