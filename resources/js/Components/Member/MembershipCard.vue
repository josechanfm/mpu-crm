<script setup>


defineProps({
    title: String,
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
        
    </div>


</template>


<style>
p{
    margin-bottom:0
}
</style>