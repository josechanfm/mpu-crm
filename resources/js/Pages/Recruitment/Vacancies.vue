<template>
    <RecruitmentLayout title="Vacancies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $t('rec.vacancies')}}
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <a-tabs v-model:activeKey="activeTag">
                        <a-tab-pane key="1" :tab="$t('rec.academic')">
                            <a-collapse v-model:activeKey="activeCollapse" :bordered="true" style="background: rgb(255, 255, 255);  padding:10px">
                                <template #expandIcon="{ isActive }">
                                    <caret-right-outlined :rotate="isActive ? 90 : 0" />
                                </template>
                                <template v-for="vacancy in vacancies">
                                    <a-collapse-panel :key="vacancy.code" :style="customStyle" v-if="vacancy.type == 'ACD'">
                                        <template #header style="color:#fff">
                                            {{ vacancy['title_'+$t('lang')] }}
                                        </template>
                                        <ol>
                                            <li v-for="notice in vacancy.notices">
                                                {{ notice.date_start }} {{ notice['title_'+$page.props.lang] }}
                                                <inertia-link :href="route('recruitment.academic.form',{code:vacancy.code})">{{ $t('rec.apply') }}</inertia-link>
                                            </li>
                                        </ol>
                                    </a-collapse-panel>
                                </template>
                            </a-collapse>
                        </a-tab-pane>
                        <a-tab-pane key="2" :tab="$t('rec.admin')">
                            <a-collapse v-model:activeKey="activeCollapse" :bordered="true" style="background: rgb(255, 255, 255);  padding:10px">
                                <template #expandIcon="{ isActive }">
                                    <caret-right-outlined :rotate="isActive ? 90 : 0" />
                                </template>
                                <template v-for="vacancy in vacancies">
                                    <a-collapse-panel :key="vacancy.code" :style="customStyle" v-if="vacancy.type == 'ADM'">
                                        <template #header style="color:#fff">
                                            {{ vacancy['title_'+$page.props.lang] }}
                                        </template>
                                        <ol>
                                            <li v-for="notice in vacancy.notices">
                                                {{ notice.date_start }} {{ notice['title_'+$page.props.lang] }}
                                                <inertia-link :href="route('recruitment.acadmic.form',{code:vacancy.code})">{{ $t('rec.apply') }}</inertia-link>
                                            </li>
                                        </ol>
                                    </a-collapse-panel>
                                </template>
                            </a-collapse>

                        </a-tab-pane>
                    </a-tabs>
                </div>
            </div>
        </div>
    </RecruitmentLayout>
</template>

<script>
import RecruitmentLayout from '@/Layouts/RecruitmentLayout.vue';
import Vacancies from '../Department/Personnel/Recruitment/Vacancies.vue';
import { CaretRightOutlined } from '@ant-design/icons-vue';

export default {
    components: {
        RecruitmentLayout,
        Vacancies,
        CaretRightOutlined,
    },
    props: ["vacancies"],
    data() {
        return {
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
    },
};

</script>
<style>
.ant-collapse-header{
    color:#fff!important;
    font-size: 16px
}
</style>
