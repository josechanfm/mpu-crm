<script setup>
import { computed } from 'vue';
import BlankLayout from '@/Layouts/BlankLayout.vue';

const props = defineProps({
    ebooks: { type: [Object, Array], default: () => [] },
});

// Normalize: handle Inertia paginator (data key) or plain array
const list = computed(() => props.ebooks?.data ?? props.ebooks ?? []);

const count = computed(() => list.value.length);
</script>

<template>
    <BlankLayout title="Ebooks">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ebooks
            </h2>
        </template>

        <div class="py-10">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Section heading -->
                <div class="mb-8 flex items-end justify-between">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                            Library
                        </h1>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ count }} {{ count === 1 ? 'book' : 'books' }} available
                        </p>
                    </div>
                </div>

                <!-- Empty state -->
                <div
                    v-if="count === 0"
                    class="rounded-xl border-2 border-dashed border-gray-200 bg-white py-24 text-center"
                >
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h3 class="mt-4 text-sm font-semibold text-gray-900">No ebooks yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Upload a PDF to get started.</p>
                </div>

                <!-- Grid -->
                <div
                    v-else
                    class="grid grid-cols-2 gap-x-6 gap-y-10 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5"
                >
                    <a
                        v-for="ebook in list"
                        :key="ebook.uid"
                        :href="`/flipbooks/${ebook.uid}`"
                        target="_blank"
                        rel="noopener"
                        class="group relative flex flex-col"
                    >
                        <!-- Cover -->
                        <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 shadow-sm ring-1 ring-black/5 transition-all duration-300 ease-out group-hover:-translate-y-1 group-hover:shadow-xl group-hover:ring-black/10">
                            <img
                                :src="`/flipbooks/${ebook.uid}/files/thumb/1.jpg`"
                                :alt="ebook.title"
                                loading="lazy"
                                class="h-full w-full object-cover object-top transition-transform duration-500 ease-out group-hover:scale-105"
                                @error="(e) => e.target.style.opacity = '0'"
                            />

                            <!-- Overlay on hover -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/0 to-black/0 opacity-0 transition-opacity duration-300 group-hover:opacity-100" />

                            <!-- "Read" chip -->
                            <div class="absolute inset-x-0 bottom-0 translate-y-2 p-3 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100">
                                <span class="inline-flex items-center gap-1 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-gray-900 shadow">
                                    Open
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <!-- Meta -->
                        <div class="mt-3 flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <h3 class="truncate text-sm font-medium text-gray-900 group-hover:text-indigo-600 transition-colors">
                                    {{ ebook.title || 'Untitled' }}
                                </h3>
                                <p v-if="ebook.uid" class="mt-0.5 truncate text-xs text-gray-500 font-mono">
                                    {{ ebook.uid }}
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </BlankLayout>
</template>