<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    status: { type: Number, default: 500 },
    message: { type: String, default: 'Something went wrong.' },
});

const titles = {
    401: 'Unauthorized',
    403: 'Forbidden',
    404: 'Page Not Found',
    419: 'Page Expired',
    422: 'Action Not Allowed', 
    429: 'Too Many Requests',
    500: 'Server Error',
    503: 'Service Unavailable',
};

const title = computed(() => titles[props.status] || 'An Error Occurred');

// Human-friendly blurbs per status
const descriptions = {
    401: 'You need to sign in to access this page.',
    403: "You don't have permission to view this resource.",
    404: "The page you're looking for doesn't exist or has been moved.",
    419: 'Your session has expired. Please refresh and try again.',
    422: "Your account isn't set up to perform this action. This is usually a role or permission assignment that needs to be corrected.", // 👈
    429: 'Too many requests. Please slow down and try again shortly.',
    500: 'Our server hit a snag. We\'ve been notified and are looking into it.',
    503: 'We\'re down for maintenance. Please check back soon.',
};
const description = computed(() => descriptions[props.status] || '');
</script>

<template>
    <Head :title="title" />

    <div class="min-h-screen flex flex-col bg-gradient-to-br from-slate-50 to-slate-200">
        <!-- Banner -->
        <header class="p-5 bg-gray-200 shadow-sm">
            <Link href="/">
                <img
                    src="/storage/images/mpu_banner.png"
                    width="300"
                    alt="MPU Banner"
                    class="hover:opacity-90 transition-opacity"
                />
            </Link>
        </header>

        <!-- Main -->
        <main class="flex-1 flex items-center justify-center px-6 py-16">
            <div class="w-full max-w-xl text-center">
                <!-- Status code -->
                <p
                    class="text-[8rem] leading-none font-black tracking-tighter bg-gradient-to-b from-slate-700 to-slate-400 bg-clip-text text-transparent select-none"
                >
                    {{ status }}
                </p>

                <!-- Title -->
                <h1 class="mt-2 text-2xl sm:text-3xl font-semibold text-slate-800">
                    {{ title }}
                </h1>

                <!-- Description -->
                <p v-if="description" class="mt-3 text-slate-500">
                    {{ description }}
                </p>

                <!-- Custom message (optional, rendered as HTML) -->
                <div
                    v-if="message"
                    class="mt-6 rounded-lg border border-slate-200 bg-white p-4 text-left text-sm text-slate-600 shadow-sm"
                    v-html="message"
                />

                <!-- Actions -->
                <div class="mt-8 flex items-center justify-center gap-3">
                    <Link
                        href="/"
                        class="inline-flex items-center gap-2 rounded-md bg-slate-800 px-5 py-2.5 text-sm font-medium text-white shadow hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.707 1.293a1 1 0 0 0-1.414 0l-7 7a1 1 0 1 0 1.414 1.414L4 9.414V17a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-3h2v3a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1V9.414l.293.293a1 1 0 0 0 1.414-1.414l-7-7Z" />
                        </svg>
                        Go Home
                    </Link>
                    <button
                        type="button"
                        @click="$inertia.reload()"
                        class="inline-flex items-center gap-2 rounded-md border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 0 1 1 1v2.101a7.002 7.002 0 0 1 11.601 2.566 1 1 0 1 1-1.885.666A5.002 5.002 0 0 0 5.999 7H9a1 1 0 0 1 0 2H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1Zm.008 9.057a1 1 0 0 1 1.276.61A5.002 5.002 0 0 0 14.001 13H11a1 1 0 1 1 0-2h5a1 1 0 0 1 1 1v5a1 1 0 1 1-2 0v-2.101a7.002 7.002 0 0 1-11.601-2.566 1 1 0 0 1 .61-1.276Z" clip-rule="evenodd" />
                        </svg>
                        Try Again
                    </button>
                </div>

                <!-- Support note -->
                <p class="mt-10 text-xs text-slate-400">
                    If the problem persists, please contact the system administrator.
                </p>
            </div>
        </main>
    </div>
</template>