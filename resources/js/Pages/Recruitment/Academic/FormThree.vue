<template>
    <MemberLayout title="Vacancies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ vacancy.code }}
                {{ vacancy['title_' + $page.props.lang] }}
            </h2>
        </template>
        <div class="p-5">
            <a-steps  progress-dot :current="this.page.current-1">
                <a-step v-for="item in lang.steps" :description="item.title"/>
            </a-steps>
        </div>
        <template v-if="$page.props.env == 'local'">
            <a-button @click="sampleData">Sample Data</a-button>
        </template>
        <div class="container bg-white rounded mx-auto p-5">
            <CardBox :title="lang.part_b">
                <template #content>
                    <table class="myTable" width="100%">
                        <tr>
                            <th colspan="2"> {{ lang.prof_organization }}</th>
                            <th rowspan="2">{{ lang.prof_qualification }}</th>
                            <th rowspan="2">{{ lang.prof_area }}</th>
                            <th colspan="2">{{ lang.prof_date }}</th>
                            <th rowspan="2">{{ lang.operation }}</th>
                        </tr>
                        <tr>
                            <th>{{ lang.prof_organization_name }}</th>
                            <th>{{ lang.prof_region }}</th>
                            <th>{{ lang.prof_date_valid }}</th>
                            <th>{{ lang.prof_date_expire }}</th>
                        </tr>
                        <template v-for="professional in application.professionals">
                            <tr>
                                <td>{{ professional.organization_name }}</td>
                                <td>{{ professional.region }}</td>
                                <td>{{ professional.qualification }}</td>
                                <td>{{ professional.area }}</td>
                                <td>{{ professional.date_valid }}</td>
                                <td>{{ professional.date_expire }}</td>
                                <td>a</td>
                            </tr>
                        </template>

                    </table>
                    <a-divider />
                    <a-form :model="professional" layout="vertical" :rules="rules" @finish="onFinish" @finishFailed="onFinishFailed">
                        <a-row :gutter="10">
                            <a-col :span="16">
                                <a-form-item :label="lang.prof_organization_name" name="organization_name">
                                    <a-input v-model:value="professional.organization_name" />
                                </a-form-item>
                            </a-col>
                            <a-col :span="8">
                                <a-form-item :label="lang.prof_region" name="region">
                                    <a-input v-model:value="professional.region" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="10">
                            <a-col :span="12">
                                <a-form-item :label="lang.prof_qualification" name="qualification">
                                    <a-input v-model:value="professional.qualification" />
                                </a-form-item>
                            </a-col>
                            <a-col :span="12">
                                <a-form-item :label="lang.prof_area">
                                    <a-input v-model:value="professional.area" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="10">
                            <a-col :span="12">
                                <a-form-item :label="lang.prof_date_valid" name="date_valid">
                                    <a-date-picker v-model:value="professional.date_valid" :format="dateFormat"
                                        :valueFormat="dateFormat" />
                                </a-form-item>
                            </a-col>
                            <a-col :span="12">
                                <a-form-item :label="lang.prof_date_expire">
                                    <a-date-picker v-model:value="professional.date_expire" :format="dateFormat"
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
            <div class="text-center pt-5">
                <a :href="route('recruitment.academic.apply', { code: vacancy.code, page: this.page.previours })" 
                    class="bg-amber-500 text-white p-2 rounded-sm m-5">{{ lang.back_no_save }}</a>
                <a-button type="primary" @click="saveToNext">{{ lang.save_next }}</a-button>
            </div>
        </div>
    </MemberLayout>
</template>

<script>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import CardBox from '@/Components/CardBox.vue';
import { CaretRightOutlined } from '@ant-design/icons-vue';
import recLang  from '/lang/recruitment_academic.json';
import { message } from 'ant-design-vue';

export default {
    components: {
        MemberLayout,
        CaretRightOutlined,
        CardBox
    },
    props: ['vacancy', 'application'],
    data() {
        return {
            page: {},
            professional: {},
            dateFormat: 'YYYY-MM-DD',
            rules: {
                organization_name: { required: true },
                region: { required: true },
                qualification: { required: true },
                date_valid: { required: true },
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
            this.professional.organization_name = 'Organization'
            this.professional.region = 'Macao'
            this.professional.qualification = 'MPM'
            this.professional.area = 'PM'
            this.professional.date_valid = '2020-01-01'
            this.professional.date_expire = '2022-01-01'
        },
        saveToNext() {
            this.onFinish();
        },
        saveToNext() {
            console.log(this.currentPage);
            this.$inertia.post(route('recruitment.academic.save'), { to_page: this.page.next, application: this.application }, {
                onSuccess: (page) => {
                    console.log(page.data)
                },
                onError: (err) => {
                    console.log(err)
                }
            });
        },
        onFinish() {
            this.application.professionals.push({ ...this.professional })
            this.professional = {};
            console.log(this.application);
        },
        onFinishFailed(){
            message.error(this.lang.error_required_fields);
        }
    },
};

</script>
<style scoped>
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
