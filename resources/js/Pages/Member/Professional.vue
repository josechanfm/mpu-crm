<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard..
            </h2>
        </template>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <a-row>
                        <a-col :span="8">
                            <div class="max-w-sm rounded overflow-hidden shadow-lg"><img class="w-full"
                                    alt="Use any sample image here..." src="https://dummyimage.com/300x5:3">
                                <div class="px-6 py-4">
                                    <div class="font-bold text-xl mb-2">{{ member.display_name }}</div>
                                    <p class="text-gray-700 text-base"> Lorem ipsum dolor sit amet consectetur adipisicing
                                        elit. Sed recusandae libero possimus culpa quod </p>
                                </div>
                                <div class="px-6 py-4"><span
                                        class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">#hashtag</span><span
                                        class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">#hashtag</span><span
                                        class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700">#hashtag</span>
                                </div>
                            </div>

                        </a-col>    
                        <a-col :span="8">col-6</a-col>
                        <a-col :span="8">col-6</a-col>
                    </a-row>
                </div>
            </div>
        </div>
    </AppLayout>
</template>


<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    components: {
        AppLayout
    },
    props: ['member'],
    data() {
        return {
            title: '',
            card: {},
        }
    },
    created() {
    },
    methods: {

    },
}
</script>