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
                <div class="">
                    <div class="sm:px-6 lg:px-4">
                        <slot />
                    </div>
                </div>
            </main>
        </section>

    </main>
</template>