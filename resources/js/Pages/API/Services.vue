<script setup>
import BlankLayout from '@/Layouts/BlankLayout.vue';

defineProps({
});

const url = () => {
    return route('calendar');
};
</script>

<template>
    <BlankLayout title="API Serices">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                API Services
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <a-typography-title :level="3">Calendar</a-typography-title>
                <p>
                    <strong>Generate Calendar and sync with official calendar:</strong><br>
                    Source: https://www.gov.mo/zh-hant/public-holidays/ical/ <br>
                    {{ url() }}/sync
                </p>
                <p><strong>Number of Working days:</strong><br>
                    {{ url() }}/working_days?start=YYYY-MM-DD&end=YYYY-MM-DD<br>
                    Return integer number
                </p>
                <p><strong>Is Holiday: </strong><br>
                    {{ url() }}/is_holiday?date=YYYY-MM-DD<br>
                    Return 1 as True, 0 as False
                </p>
                <p><strong>Is Working day:</strong><br>
                    {{ url() }}/is_working_day?date=YYYY-MM-DD<br>
                    Return 1 as True, 0 as False
                </p>
            </div>
        </div>
    </BlankLayout>
</template>
