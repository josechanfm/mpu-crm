<script setup>
//import { defineProps } from "vue";

defineProps({
    title: String,
    card: Object,
});
</script>

<template>
    <div class="md:grid md:grid-cols-3 py-5 md:gap-6">
        <div class="md:col-span-1 flex justify-between pl-5">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium text-gray-900">{{ title }}</h3>
                <p class="mt-1 text-sm text-gray-600">
                    <slot name="description" />
                </p>
            </div>
            <div class="px-4 sm:px-0"></div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="w-96 h-56 m-auto bg-red-100 rounded-lg relative text-white shadow-2xl transition-transform transform hover:scale-110">
                <img class="relative object-cover w-full h-full rounded-lg" :src="card.background"
                    width="10px">
                <div class="absolute w-full text-center text-lg" style="top:1em">{{ card.cert_title }}</div>
                <div class="w-full px-8 absolute top-8">
                    <div class="flex justify-between">
                        <div class="">
                            <p class="font-light">
                                Name
                            </p>
                            <p p class="font-medium tracking-widest">
                                {{ card.pivot.display_name }}
                            </p>
                        </div>
                        <img class="w-14 h-14" :src="card.avata" />
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="pt-1">
                            <p class="font-light">
                                編號
                            </p>
                            <p class="font-medium tracking-more-wider">
                                {{ card.pivot.number }}
                            </p>
                        </div>
                        <div class="pt-1">
                            <p class="font-light">
                                {{ card.rank_caption }}
                            </p>
                            <p class="font-medium tracking-more-wider">
                                {{ card.pivot.rank }}
                            </p>
                        </div>

                    </div>

                    
                    <div class="pt-6 pr-6">
                        <div class="flex justify-between">
                            <div class="">
                                <p class="font-light text-xs">
                                    Valid
                                </p>
                                <p class="font-medium tracking-wider text-sm">
                                    {{card.pivot.valid_from}}
                                </p>
                            </div>
                            <div class="">
                                <p class="font-light text-xs text-xs">
                                    Expiry
                                </p>
                                <p class="font-medium tracking-wider text-sm">
                                    {{card.pivot.valid_until}}
                                </p>
                            </div>

                            <div class="">
                                <p class="font-light text-xs">
                                    CVV
                                </p>
                                <p class="font-bold tracking-more-wider text-sm">
                                    ···
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>            

        </div>
    </div>


</template>


<style>
p{
    margin-bottom:0
}
</style>