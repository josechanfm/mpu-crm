<template>
    <RecruitmentLayout title="Vacancies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ vacancy.code }}
                {{ vacancy['title_' + $page.props.lang] }}
            </h2>
        </template>
        <template v-if="$page.props.env == 'local'">
            <a-button @click="sampleData">Sample Data</a-button>
        </template>
        <div class="pb-5">
            <a-steps  progress-dot :current="1">
                <a-step v-for="item in lang.steps" :description="item.title"/>
            </a-steps>
        </div>
        <CardBox :title="lang.part_a">
            <template #content>
                <div style="overflow:auto">
                    <table class="myTable w-full">
                    <tr>
                        <th colspan="2">{{ lang.edu_institute }}</th>
                        <th rowspan="2">{{ lang.edu_degree }}</th>
                        <th rowspan="2">{{ lang.edu_subject }}</th>
                        <th rowspan="2">{{ lang.edu_language }}</th>
                        <th colspan="2">{{ lang.edu_date }}</th>
                        <th rowspan="2">{{ lang.operation }}</th>
                    </tr>
                    <tr>
                        <th>{{ lang.edu_school_name }}</th>
                        <th>{{ lang.edu_region }}</th>
                        <th>{{ lang.edu_date_start }}</th>
                        <th>{{ lang.edu_date_finish }}</th>
                    </tr>
                    <template v-for="education in application.educations">
                        <tr>
                            <td>{{ education.school_name }}</td>
                            <td>{{ education.region }}</td>
                            <td>{{ education.degree }}</td>
                            <td>{{ education.subject }}</td>
                            <td>{{ education.language }}</td>
                            <td>{{ education.date_start }}</td>
                            <td>{{ education.date_finish }}</td>
                        </tr>
                    </template>
                </table>
            </div>
                <a-divider />
                <a-form :model="education" layout="vertical" :rules="rules" @finish="onFinish" @finishFailed="onFinishFailed">
                    <a-row :gutter="10">
                        <a-col :span="16">
                            <a-form-item :label="lang.edu_school_name" name="school_name">
                                <a-input v-model:value="education.school_name" />
                            </a-form-item>
                        </a-col>
                        <a-col :span="8">
                            <a-form-item :label="lang.edu_region" name="region">
                                <a-input v-model:value="education.region" />
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-row :gutter="10">
                        <a-col :span="8">
                            <a-form-item :label="lang.edu_degree" name="degree">
                                <a-select v-model:value="education.degree" :options="educationOptions" />
                            </a-form-item>
                        </a-col>
                        <a-col :span="8">
                            <a-form-item :label="lang.edu_subject" name="subject">
                                <a-input v-model:value="education.subject" />
                            </a-form-item>
                        </a-col>
                        <a-col :span="8">
                            <a-form-item :label="lang.edu_qualification" name="qualification">
                                <a-input v-model:value="education.qualification" />
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-row :gutter="10">
                        <a-col :span="8">
                            <a-form-item :label="lang.edu_language" name="language">
                                <a-select v-model:value="education.language" :options="languageOptions" />
                            </a-form-item>
                        </a-col>
                        <a-col :span="8">
                            <a-form-item :label="lang.edu_date_start" name="date_start">
                                <a-date-picker v-model:value="education.date_start" :format="dateFormat"
                                    :valueFormat="dateFormat" />
                            </a-form-item>
                        </a-col>
                        <a-col :span="8">
                            <a-form-item :label="lang.edu_date_finish" name="date_finsih">
                                <a-date-picker v-model:value="education.date_finish" :format="dateFormat"
                                    :valueFormat="dateFormat" />
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-form-item :wrapper-col="{ span: 24, offset: 11, }">
                        <a-button type="primary" html-type="submit">{{ lang.add_item }}</a-button>
                    </a-form-item>
                </a-form>
            </template>
        </CardBox>

        <!-- <div class="border border-sky-500 rounded-lg mt-5">
                    <h2 class="bg-sky-500 text-white p-4 rounded-t-lg">{{ lang.personal_info }}</h2>
                    <div class="p-4">
                        <p>Card content</p>
                    <p>Card content</p>
                    <p>Card content</p>
                    </div>
                </div> -->
        <div class="text-center pt-5">
            <a-button :href="route('recruitment.academic.form', { code: vacancy.code, page: 1 })"
                class="bg-amber-500 text-white p-3 rounded-lg m-5">{{ lang.back_no_save }}</a-button>
            <a-button type="primary" @click="saveToNext">{{ lang.save_next }}</a-button>
        </div>
    </RecruitmentLayout>
</template>

<script>
import RecruitmentLayout from '@/Layouts/RecruitmentLayout.vue';
import CardBox from '@/Components/CardBox.vue';
import { CaretRightOutlined } from '@ant-design/icons-vue';
import recLang  from '/lang/recruitment.json';
import { message } from 'ant-design-vue';

export default {
    components: {
        RecruitmentLayout,
        CaretRightOutlined,
        CardBox
    },
    props: ['vacancy', 'application'],
    data() {
        return {
            page: {},
            education: {},
            dateFormat: 'YYYY-MM-DD',
            languageOptions: [],
            educationOptions: [],
            rules: {
                school_name: { required: true },
                region: { required: true },
                degree: { required: true },
                subject: { required: true },
                language: { required: true },
                date_start: { required: true },
                data_finish: { required: true },
            },
            activeTag: '1',
            activeCollapse: null,
            customStyle: 'background: #509f7f;border-radius: 4px;margin-bottom: 24px;border: 1;overflow: hidden',
        }
    },
    created() {
        this.lang = recLang[this.$page.props.lang]
    },
    mounted() {
        let urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('page')) {
            this.page.current = parseInt(urlParams.get('page'))
            this.page.previours = this.page.current - 1
            this.page.next = this.page.current + 1
        }
    },
    methods: {
        sampleData() {
            this.education.school_name = "School name 1"
            this.education.region = "MACAO"
            this.education.degree = '4BACH'
            this.education.subject = 'Subject1'
            this.education.qualification = 'Qualification 1'
            this.education.language = 'CAN'
            this.education.date_start = '2000-01-01'
            this.education.date_finish = '2005-01-01'
        },
        saveToNext() {
            this.$inertia.post(route('recruitment.academic.save'), { to_page: 3, application: this.application }, {
                onSuccess: (page) => {
                    console.log(page.data)
                },
                onError: (err) => {
                    console.log(err)
                }
            });
        },
        onFinish() {
            this.application.educations.push({ ...this.education })
            this.education = {};
            console.log(this.application);
        },
        onFinishFailed(){
            message.error(this.lang.error_required_fields);
        }

    },
};

</script>
<style>
label.ant-checkbox-wrapper {
    margin-left: 8px;
}

.myTable,
.myTable tr th,
.myTable tr td {
    border: 1px solid gray;
    padding: 5px
}
</style>
