<template>
    <RecruitmentLayout title="Vacancies">
        <!-- Page Header Section -->
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $t('rec.profile') }}
                </h2>
                <inertia-link :href="route('recruitment')" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                    <ArrowLeftOutlined class="mr-1" />
                    {{ $t('rec.back_to_recuitment') }}
                </inertia-link>
            </div>
        </template>

        <!-- Main Content Area -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- User Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- User Profile Card -->
                <div class="bg-white border border-solid rounded-xl shadow-md overflow-hidden dark:bg-slate-850">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    {{ $t('rec.personal_info') }}
                                </h3>
                                <div class="mt-2">
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ user.name }}
                                    </h2>
                                    <p class="mt-1 text-gray-600 dark:text-gray-300">
                                        <MailOutlined class="mr-2" />
                                        {{ user.email }}
                                    </p>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        <CalendarOutlined class="mr-2" />
                                        {{ $t('rec.register_since') }} {{ formatDate(user.created_at) }}
                                    </p>
                                </div>
                            </div>
                            <div class="ml-4">
                                <UserOutlined :style="{ fontSize: '48px', color: '#3b82f6' }" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Applications Summary Card -->
                <div class="bg-white border border-solid rounded-xl shadow-md overflow-hidden dark:bg-slate-850">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    {{ $t('rec.applications_summary') }}
                                </h3>
                                <div class="mt-2">
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ applications.length }} application{{ applications.length !== 1 ? 's' : '' }}
                                    </h2>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400" v-if="applications.length > 0">
                                        <ClockCircleOutlined class="mr-2" />
                                        {{ $t('rec.last_submitted') }}: {{ formatDate(applications[applications.length - 1].created_at) }}
                                    </p>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400" v-else>
                                        No applications submitted yet
                                    </p>
                                </div>
                            </div>
                            <div class="ml-4">
                                <FormOutlined :style="{ fontSize: '48px', color: '#3b82f6' }" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Applications List Section -->
            <div class="mb-10">
                <h3 class="text-lg font-medium text-gray-900 mb-4 dark:text-white">
                    {{ $t('rec.your_applications') }}
                </h3>

                <div v-if="applications.length === 0" class="text-center py-12 bg-white rounded-lg shadow dark:bg-slate-850">
                    <FileSearchOutlined :style="{ fontSize: '48px', color: '#9ca3af' }" />
                    <p class="mt-4 text-gray-500 dark:text-gray-400">
                        You haven't submitted any applications yet.
                    </p>
                    <inertia-link 
                        :href="route('recruitment')" 
                        class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Browse Open Positions
                    </inertia-link>
                </div>

                <!-- Application Cards -->
                <div v-for="(app, i) in applications" :key="app.id" class="mb-6">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 dark:bg-slate-850 dark:border-gray-700">
                        <!-- Card Header -->
                        <div class="px-6 py-4 bg-blue-50 border-b border-gray-200 dark:bg-slate-800 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white">
                                        {{ app.vacancy.code }} - {{ app.vacancy['title_'+$t('lang')] }}
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                        Applied on: {{ formatDate(app.created_at) }}
                                    </p>
                                </div>
                                <div>
                                    <template v-if="!app.submitted">
                                        <inertia-link 
                                            v-if="app.vacancy.type=='ACA'" 
                                            :href="route('recruitment.academic.apply') + '?code='+app.vacancy.code"
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        >
                                            <EditOutlined class="mr-1" />
                                            {{ $t('rec.edit') }}
                                        </inertia-link>
                                        <inertia-link 
                                            v-if="app.vacancy.type=='ADM'" 
                                            :href="route('recruitment.academic.apply') + '?code='+app.vacancy.code"
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        >
                                            <EditOutlined class="mr-1" />
                                            {{ $t('rec.edit') }}
                                        </inertia-link>
                                    </template>
                                    <template v-else>
                                        <a 
                                            v-if="app.vacancy.type=='ACA'" 
                                            :href="route('recruitment.academic.receipt',{application_id:app.id,uuid:app.uuid})" 
                                            target="_blank"
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        >
                                            <FilePdfOutlined class="mr-1" />
                                            {{ $t('rec.receipt')}}
                                        </a>
                                        <a 
                                            v-if="app.vacancy.type=='ADM'" 
                                            :href="route('recruitment.admin.receipt',{application_id:app.id,uuid:app.uuid})" 
                                            target="_blank"
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        >
                                            <FilePdfOutlined class="mr-1" />
                                            {{ $t('rec.receipt')}}
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="px-6 py-4">
                            <!-- Important Dates Section -->
                            <div class="mb-6">
                                <h5 class="text-md font-medium text-gray-700 mb-2 dark:text-gray-300">
                                    <CalendarOutlined class="mr-2" />
                                    Important Dates
                                </h5>
                                <div class="bg-gray-50 rounded-lg p-4 dark:bg-slate-800">
                                    <ul class="space-y-2">
                                        <li class="flex items-start">
                                            <CheckCircleOutlined class="text-green-500 mt-1 mr-2" />
                                            <span class="text-gray-700 dark:text-gray-300">
                                                Application deadline: <strong>{{ app.vacancy.deadline }}</strong>
                                            </span>
                                        </li>
                                        <li class="flex items-start">
                                            <InfoCircleOutlined class="text-blue-500 mt-1 mr-2" />
                                            <span class="text-gray-700 dark:text-gray-300">
                                                Interview period: <strong>{{ app.vacancy.interview_period }}</strong>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Supplementary Files Section -->
                            <div class="mb-6">
                                <h5 class="text-md font-medium text-gray-700 mb-2 dark:text-gray-300">
                                    <PaperClipOutlined class="mr-2" />
                                    {{ $t('rec.supplementary_files') }}
                                </h5>
                                <div class="bg-gray-50 rounded-lg p-4 dark:bg-slate-800">
                                    <ol class="list-decimal list-inside space-y-1 text-gray-700 dark:text-gray-300">
                                        <li>Curriculum Vitae (CV)</li>
                                        <li>Academic Transcripts</li>
                                        <li>Cover Letter</li>
                                        <li>Reference Letters</li>
                                    </ol>
                                </div>
                            </div>

                            <!-- Notices Section -->
                            <div>
                                <h5 class="text-md font-medium text-gray-700 mb-2 dark:text-gray-300">
                                    <NotificationOutlined class="mr-2" />
                                    {{ $t('rec.notices') }}
                                </h5>
                                <div class="space-y-3">
                                    <div v-for="notice in app.vacancy.notices" :key="notice.id" class="border-l-4 border-blue-500 pl-4 py-1">
                                        <p class="font-medium text-gray-800 dark:text-white">{{ notice.title_zh }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Posted on: {{ formatDate(notice.date_start) }}
                                        </p>
                                        <p class="mt-1 text-gray-700 dark:text-gray-300">
                                            {{ notice.description_zh }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 text-right dark:bg-slate-800 dark:border-gray-700">
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                Application ID: {{ app.uuid }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </RecruitmentLayout>
</template>

<script>
import RecruitmentLayout from '@/Layouts/RecruitmentLayout.vue';
import { 
    UserOutlined, 
    FormOutlined,
    ArrowLeftOutlined,
    MailOutlined,
    CalendarOutlined,
    ClockCircleOutlined,
    FileSearchOutlined,
    EditOutlined,
    FilePdfOutlined,
    CheckCircleOutlined,
    InfoCircleOutlined,
    PaperClipOutlined,
    NotificationOutlined
} from '@ant-design/icons-vue';
import dayjs from 'dayjs';

export default {
    components: {
        RecruitmentLayout,
        UserOutlined, 
        FormOutlined,
        ArrowLeftOutlined,
        MailOutlined,
        CalendarOutlined,
        ClockCircleOutlined,
        FileSearchOutlined,
        EditOutlined,
        FilePdfOutlined,
        CheckCircleOutlined,
        InfoCircleOutlined,
        PaperClipOutlined,
        NotificationOutlined
    },
    props: {
        // Current authenticated user data
        user: {
            type: Object,
            required: true
        },
        // List of applications submitted by the user
        applications: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            dateFormat: 'YYYY-MM-DD', // Date format for display
        }
    },
    methods: {
        /**
         * Format date using dayjs
         * @param {String} date - Date string to format
         * @returns {String} Formatted date
         */
        formatDate(date) {
            return dayjs(date).format(this.dateFormat);
        }
    },
};
</script>

<style scoped>
/* Custom transitions for interactive elements */
a, button {
    transition: all 0.2s ease-in-out;
}

/* Dark mode compatible shadows */
.shadow-md {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.dark .shadow-md {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.25), 0 2px 4px -1px rgba(0, 0, 0, 0.15);
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .grid-cols-1 {
        grid-template-columns: 1fr;
    }
}
</style>