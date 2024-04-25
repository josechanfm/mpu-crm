<template>
    <RecruitmentLayout title="Vacancies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ vacancy.code }}
                {{ vacancy['title_' + $page.props.lang] }}
            </h2>
        </template>
        <CardBox :title="$t('rec.application_form')">
            <template #content>
                <table width="100%">
                    <tr>
                        <td class="p-2">
                            <div>{{ $t('rec.unit') }}: {{ vacancy.apply_in }}</div>
                            <div>{{ $t('rec.code') }}: {{ vacancy.code }}</div>
                            <div>{{ $t('rec.title') }}: {{ vacancy['title_' + $page.props.lang] }}</div>
                        </td>
                        <td class="p-2">
                            <div>{{ $t('rec.obtain_info') }}</div>
                            <div>{{ $t('rec.obtain_info_web') }}</div>
                            <div>{{ $t('rec.obtain_info_new') }}</div>
                            <div>{{ $t('rec.obtain_info_oth') }}</div>
                        </td>
                    </tr>
                </table>
                <table class="mt-5" width="100%">
                    <tr>
                        <th colspan="4" style="text-align: left;">
                            {{ $t('rec.personal_info') }}
                            <a-button v-if="!application.submitted"
                                :href="route('application.apply', { 'code': vacancy.code, 'page': 1 })"
                                class="ant-btn ant-btn-primary float-right ml-5">Edit</a-button>
                            <div class="float-right">{{ $t('rec.part_a') }}</div>
                        </th>
                    </tr>
                    <tr>
                        <th width="200px">{{ $t('rec.name_zh') }}</th>
                        <td width="40%">{{ application.name_zh }}</td>
                        <th width="150px">{{ $t('rec.gender') }}</th>
                        <td>{{ application.gender }}</td>
                    </tr>
                    <tr>
                        <th>{{ $t('rec.last_name_fn') }}</th>
                        <td colspan="3">{{ application.last_name_fn }}</td>
                    </tr>
                    <tr>
                        <th>{{ $t('rec.first_name_fn') }}</th>
                        <td colspan="3">{{ application.first_name_fn }}</td>
                    </tr>
                    <tr>
                        <th>{{ $t('rec.pob') }}</th>
                        <td>{{ application.pob }}</td>
                        <th>{{ $t('rec.dob') }}</th>
                        <td>{{ application.dob }}</td>
                    </tr>
                    <tr>
                        <th>{{ $t('rec.id_type') }}</th>
                        <td>
                            <template v-if="application.id_type == 'OTH'">
                                {{ application.id_type_name }}
                            </template>
                            <template v-else>
                                {{ application.id_type_type }}
                            </template>
                        </td>
                        <th>{{ $t('rec.id_num') }}</th>
                        <td>{{ application.id_num }}</td>
                    </tr>
                    <tr>
                        <th>{{ $t('rec.nationality') }}</th>
                        <td>{{ application.nationality }}</td>
                        <th></th>
                        <td>4</td>
                    </tr>
                    <tr>
                        <th>{{ $t('rec.address') }}</th>
                        <td colspan="3">{{ application.address }}</td>
                    </tr>
                    <tr>
                        <th>{{ $t('rec.phone') }}</th>
                        <td>{{ application.phone }}</td>
                        <th>{{ $t('rec.email') }}</th>
                        <td>{{ application.email }}</td>
                    </tr>
                    <tr>
                        <th>{{ $t('rec.name_zh') }}</th>
                        <td>b</td>
                        <th>{{ $t('rec.gender') }}</th>
                        <td>4</td>
                    </tr>
                </table>

                <table class="mt-5" width="100%">
                    <tr>
                        <th colspan="8" style="text-align: left;">
                            {{ $t('rec.educations') }}
                            <a-button v-if="!application.submitted"
                                :href="route('application.apply', { 'code': vacancy.code, 'page': 2 })"
                                class="ant-btn ant-btn-primary float-right ml-5">Edit</a-button>
                            <div class="float-right">{{ $t('rec.part_b') }}</div>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="2"> {{ $t('rec.edu_institute') }}</th>
                        <th rowspan="2">{{ $t('rec.edu_degree') }}</th>
                        <th rowspan="2">{{ $t('rec.edu_subject') }}</th>
                        <th rowspan="2">{{ $t('rec.edu_language') }}</th>
                        <th colspan="2">{{ $t('rec.edu_date') }}</th>
                    </tr>
                    <tr>
                        <th>{{ $t('rec.edu_school_name') }}</th>
                        <th>{{ $t('rec.edu_region') }}</th>
                        <th>{{ $t('rec.edu_date_start') }}</th>
                        <th>{{ $t('rec.edu_date_finish') }}</th>
                    </tr>
                    <template v-for="education in application.educations">
                        <tr>
                            <td>{{ education.school_name }}</td>
                            <td>{{ education.region }}</td>
                            <td>{{ optionItem(educationOptions,education.degree)}}</td>
                            <td>{{ education.subject }}</td>
                            <td>{{ optionItem(languageOptions,education.language)}}</td>
                            <td>{{ education.date_start }}</td>
                            <td>{{ education.date_finish }}</td>
                        </tr>
                    </template>
                </table>
                <table class="mt-5" width="100%">
                    <tr>
                        <th colspan="6" style="text-align: left;">
                            {{ $t('rec.professional') }}
                            <a-button v-if="!application.submitted"
                                :href="route('application.apply', { 'code': vacancy.code, 'page': 3 })"
                                class="ant-btn ant-btn-primary float-right ml-5">Edit</a-button>
                            <div class="float-right">{{ $t('rec.part_c') }}</div>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="2"> {{ $t('rec.prof_organization') }}</th>
                        <th rowspan="2">{{ $t('rec.prof_qualification') }}</th>
                        <th rowspan="2">{{ $t('rec.prof_area') }}</th>
                        <th colspan="2">{{ $t('rec.prof_date') }}</th>
                    </tr>
                    <tr>
                        <th>{{ $t('rec.prof_organization_name') }}</th>
                        <th>{{ $t('rec.prof_region') }}</th>
                        <th>{{ $t('rec.prof_date_valid') }}</th>
                        <th>{{ $t('rec.prof_date_expired') }}</th>
                    </tr>
                    <template v-for="professional in application.professionals">
                        <tr>
                            <td>{{ professional.organization_name }}</td>
                            <td>{{ professional.region }}</td>
                            <td>{{ professional.qualification }}</td>
                            <td>{{ professional.area }}</td>
                            <td>{{ professional.date_valid }}</td>
                            <td>{{ professional.date_expired }}</td>
                        </tr>
                    </template>
                </table>

                <table class="mt-5" width="100%">
                    <tr>
                        <th colspan="7" style="text-align: left;">
                            {{ $t('rec.experience') }}
                            <a-button v-if="!application.submitted"
                                :href="route('application.apply', { 'code': vacancy.code, 'page': 4 })"
                                class="ant-btn ant-btn-primary float-right ml-5">Edit</a-button>
                            <div class="float-right">{{ $t('rec.part_d') }}</div>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="2"> {{ $t('rec.exp_company') }}</th>
                        <th rowspan="2">{{ $t('rec.exp_position') }}</th>
                        <th rowspan="2">{{ $t('rec.exp_salary') }}</th>
                        <th rowspan="2">{{ $t('rec.exp_employment') }}</th>
                        <th colspan="2">{{ $t('rec.exp_date') }}</th>
                    </tr>
                    <tr>
                        <th>{{ $t('rec.exp_company_name') }}</th>
                        <th>{{ $t('rec.exp_region') }}</th>
                        <th>{{ $t('rec.exp_date_join') }}</th>
                        <th>{{ $t('rec.exp_date_leave') }}</th>
                    </tr>
                    <template v-for="experience in application.experiences">
                        <tr>
                            <td>{{ experience.company_name }}</td>
                            <td>{{ experience.region }}</td>
                            <td>{{ experience.position }}</td>
                            <td>{{ experience.salary }}</td>
                            <td>
                                {{ optionItem(employmentOptions,experience.employment)}}
                            </td>
                            <td>{{ experience.date_join }}</td>
                            <td>{{ experience.date_leave }}</td>
                        </tr>
                    </template>
                </table>
                {{ application.uploads }}
                <table class="mt-5" width="100%">
                    <tr>
                        <th colspan="2" style="text-align: left;">
                            {{ $t('rec.upload') }}
                            <a-button v-if="!application.submitted"
                                :href="route('application.apply', { 'code': vacancy.code, 'page': 5 })"
                                class="ant-btn ant-btn-primary float-right ml-5">Edit</a-button>
                        </th>
                    </tr>
                    <tr>
                        <th style="text-align: left;">a</th>
                        <td>b</td>
                    </tr>
                </table>
                <div class="text-center">
                    <a-form :model="application" @finish="onFinish">
                        <a-form-item>
                            <template v-if="application.submitted">
                                <a-button type="primary" danger class="mt-5">Pay</a-button>
                            </template>
                            <template v-else>
                                <a-button :href="route('application.apply', { code: vacancy.code, page: this.page.previours })"
                                    class="bg-amber-500 text-white p-3 rounded-lg m-5">{{ $t('rec.back_no_save') }}</a-button>
                                <a-button type="primary" html-type="submit" class="mt-5">{{ $t('rec.submit') }}</a-button>
                            </template>
                        </a-form-item>
                    </a-form>
                </div>
            </template>
        </CardBox>
    </RecruitmentLayout>
</template>

<script>
import RecruitmentLayout from '@/Layouts/RecruitmentLayout.vue';
import CardBox from '@/Components/CardBox.vue';
import { CaretRightOutlined } from '@ant-design/icons-vue';

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
            languageOptions: [],
            educationOptions: [],
            employmentOptions: [],
            rules: {
                name_zh: { required: true },
                first_name_fn: { required: true },
                last_name_fn: { required: true },
                gender: { required: true },
                pob: { required: true },
                pob_oth: { required: true },
                dob: { required: true },
                id_type: { required: true },
                id_type_name: { required: true },
                id_num: { required: true },
                nationality: { required: true },
                phone: { required: true },
                email: { required: true },
            },
            activeTag: '1',
            activeCollapse: null,
            customStyle: 'background: #509f7f;border-radius: 4px;margin-bottom: 24px;border: 1;overflow: hidden',
        }
    },
    created() {
        axios.get(route('api.config.item', { key: 'rec_employment_types' }))
            .then(res => {
                this.employmentOptions = res.data[this.$page.props.lang].value
            })
            .then(err => {
                console.log(err)
            })
        axios.get(route('api.config.item', { key: 'rec_languages' }))
            .then(res => {
                this.languageOptions = res.data[this.$page.props.lang].value
            })
            .then(err => {
                console.log(err)
            })
        axios.get(route('api.config.item', { key: 'rec_educations' }))
            .then(res => {
                this.educationOptions = res.data[this.$page.props.lang].value
            })
            .then(err => {
                console.log(err)
            })

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
            this.application.obtain_info = ['WEB', 'NEW'],
                this.application.obtain_info_new = 'Macao Daily',
                this.application.obtain_info_oth = 'Inernet',
                this.application.name_zh = '陳大文',
                this.application.first_name_fn = 'Tai Man',
                this.application.last_name_fn = 'Chan',
                this.application.gender = 'M',
                this.application.pob = 'OTH',
                this.application.pob_oth = 'Germany',
                this.application.dob = '1970-07-18',
                this.application.id_type = 'OTH',
                this.application.id_type_name = 'Germany',
                this.application.id_num = '123456789',
                this.application.nationality = 'German',
                this.application.phone = '66778899',
                this.application.email = 'chantaiman@example.com',
                this.application.address = 'Somewhere near by..'
        },
        onFinish() {
            console.log('onfinish')
            this.$inertia.post(route('application.submit'), { to_page: 7, application: this.application }, {
                onSuccess: (page) => {
                    console.log(page.data)
                },
                onError: (err) => {
                    console.log(err)
                }
            });
        },
        optionItem(options, value){
            console.log(options);
                let option=options.find(o=>o.value==value)
                if(option){
                    return option.label
                }else{
                    return null;
                }
        }
    },
};

</script>
<style>
label.ant-checkbox-wrapper {
    margin-left: 8px;
}

table,
table tr th,
table tr td {
    border: 1px solid gray;
    padding: 5px
}
</style>
