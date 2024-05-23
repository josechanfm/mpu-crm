<template>
    <RecruitmentLayout title="Vacancies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $t('rec.profile') }}

                <inertia-link :href="route('recruitment')" class="float-right">{{ $t('rec.back_to_recruitment')
                    }}</inertia-link>

            </h2>
        </template>
        <div class="">
            <div class="w-full px-6 py-6 mx-auto">
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/2">
                        <div
                            class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                            <div class="flex-auto p-4">
                                <div class="flex flex-row -mx-3">
                                    <div class="flex-none w-2/3 max-w-full px-3">
                                        <div>
                                            <p
                                                class="mb-0 font-sans font-semibold leading-normal uppercase dark:text-white dark:opacity-60 text-sm">
                                                {{ user.name }}
                                            </p>
                                            <h5 class="mb-2 font-bold dark:text-white">{{ user.email }}</h5>
                                            <p class="mb-0 dark:text-white dark:opacity-60">
                                                Register at: {{ formatDate(user.created_at) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="px-3 text-right basis-1/3">
                                        <UserOutlined :style="{ fontSize: '48px', color: '#08c' }" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/2">
                        <div
                            class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                            <div class="flex-auto p-4">
                                <div class="flex flex-row -mx-3">
                                    <div class="flex-none w-2/3 max-w-full px-3">
                                        <div>
                                            <p
                                                class="mb-0 font-sans font-semibold leading-normal uppercase dark:text-white dark:opacity-60 text-sm">
                                                Number of Applications
                                            </p>
                                            <h5 class="mb-2 font-bold dark:text-white">{{ applications.length }}</h5>
                                            <p class="mb-0 dark:text-white dark:opacity-60">
                                                Last application: {{
                    formatDate(applications[applications.length - 1].created_at)
                }}

                                            </p>
                                        </div>
                                    </div>
                                    <div class="px-3 text-right basis-1/3">
                                        <div
                                            class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl">
                                            <FormOutlined :style="{ fontSize: '48px', color: '#08c' }" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
                <div v-for="(app, i) in applications" class="rounded-lg shadow-lg overflow-auto mb-5">
                    <a-card :title="app.vacancy.code" :head-style="{ backgroundColor: '#e0f2fe' }">
                        <template #extra v-if="!app.submitted">
                            <inertia-link v-if="app.vacancy.type=='ACA'" :href="route('recruitment.academic.apply') + '?code='+app.vacancy.code">{{ $t('rec.edit') }}</inertia-link>
                            <inertia-link v-if="app.vacancy.type=='ADM'" :href="route('recruitment.academic.apply') + '?code='+app.vacancy.code">{{ $t('rec.edit') }}</inertia-link>
                        </template>
                        <template #extra v-else>
                            <a v-if="app.vacancy.type=='ACA'" :href="route('recruitment.academic.receipt',{application_id:app.id,uuid:app.uuid})" class="ant-btn ant-btn-primary ant-btn-primary mt-5" target="_blank">{{ $t('rec.receipt')}}</a>
                            <a v-if="app.vacancy.type=='ADM'" :href="route('recruitment.admin.receipt',{application_id:app.id,uuid:app.uuid})" class="ant-btn ant-btn-primary ant-btn-primary mt-5" target="_blank">{{ $t('rec.receipt')}}</a>
                        </template>
                        <p>{{ app.vacancy.title_zh }}</p>
                        <p><strong>{{ $t('rec.supplementary_date') }}</strong></p>
                        <p><strong>{{ $t('rec.supplementary_files') }}</strong></p>
                        <ol>
                            <li>file 1</li>
                            <li>file 2</li>
                        </ol>

                        <p><strong>{{ $t('rec.notices') }}</strong></p>
                        <ol>
                            <li v-for="notice in app.vacancy.notices">
                                {{ notice.date_start }} : {{ notice.title_zh }}
                            </li>
                        </ol>
                    </a-card>
                </div>
<!-- 
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-sky-200">
                            <th class="py-4 px-4 text-left">Recruitment</th>
                            <th class="py-4 px-4 text-left">Notice</th>
                            <th class="py-4 px-4 text-left">sub date</th>
                            <th class="py-4 px-4 text-left">Reciept</th>
                            <th class="py-4 px-4 text-left">sub</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(app, i) in applications">
                            <tr :class="[
                                i % 2 === 0 ? 'bg-white' : 'bg-sky-50',
                                'hover:bg-gray-100 transition-colors duration-300'
                                ]"
                            >
                                <td class="py-2 px-4">
                                    <inertia-link v-if="app.vacancy.type=='ACA'" :href="route('recruitment.academic.apply') + '?code='+app.vacancy.code">
                                        {{ app.vacancy.code }}<br>
                                        {{ app.vacancy.title_zh }}
                                    </inertia-link>
                                    <inertia-link v-if="app.vacancy.type=='ADM'" :href="route('recruitment.admin.apply') + '?code='+app.vacancy.code">
                                        {{ app.vacancy.code }}<br>
                                        {{ app.vacancy.title_zh }}
                                    </inertia-link>
                                </td>
                                <td class="py-2 px-4">
                                    <ol>
                                        <li v-for="notice in app.vacancy.notices">
                                            {{ notice.title_zh }}
                                        </li>
                                    </ol>
                                </td>
                                <td class="py-2 px-4">
                                    date
                                </td>
                                <td>
                                    receipt
                                </td>
                                <td class="py-2 px-4">
                                    docs
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table> -->
            
        </div>
    </RecruitmentLayout>
</template>

<script>
import RecruitmentLayout from '@/Layouts/RecruitmentLayout.vue';
import Vacancies from '../Department/Personnel/Recruitment/Vacancies.vue';
import { UserOutlined, FormOutlined } from '@ant-design/icons-vue'
import dayjs from 'dayjs';

export default {
    components: {
        RecruitmentLayout,
        Vacancies,
        UserOutlined, FormOutlined,
        dayjs
    },
    props: ["user", "applications"],
    data() {
        return {
            dateFormat: 'YYYY-MM-DD',
            responsiveSettingsLanguage: false,
            activeTag: '1',
            activeCollapse: null,
            customStyle: 'background: #509f7f;border-radius: 4px;margin-bottom: 24px;border: 1;overflow: hidden',
        }
    },
    created() {

    },
    mounted() {
    },
    methods: {
        formatDate(date) {
            return dayjs(date).format(this.dateFormat);
        }
    },
};

</script>
<style>
.ant-collapse-header {
    color: #fff !important;
    font-size: 16px
}
</style>
