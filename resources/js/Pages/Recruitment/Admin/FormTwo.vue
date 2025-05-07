<template>
    <MemberLayout title="Vacancies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ vacancy.code }}
                {{ vacancy['title_' + $page.props.lang] }}
            </h2>
        </template>
        <div class="p-5">
            <a-steps  progress-dot :current="page.current-1">
                <a-step v-for="item in lang.steps" :description="item.title"/>
            </a-steps>
        </div>
        <template v-if="$page.props.env == 'local'">
            <a-button @click="sampleData">Sample Data</a-button>
        </template>
        <div class="container bg-white rounded mx-auto p-5">
            {{ lang.part_A }}...
            <CardBox :title="lang.part_A">
                <template #content>
                    <div style="overflow:auto">
                        <table class="myTable w-full">
                            <thead>
                            <tr><th colspan="9">{{ lang.edu_info }}</th></tr>
                            <tr><th colspan="9">{{ lang.listed_in_sequence }}</th></tr>
                            <tr>
                                <th rowspan="2">{{ lang.edu_degree }}</th>
                                <th rowspan="2">{{ lang.edu_program }}</th>
                                <th rowspan="2">{{ lang.edu_subject }}</th>
                                <th rowspan="2">{{ lang.edu_lang }}</th>
                                <th colspan="2">{{ lang.edu_institute }}</th>
                                <th rowspan="2">{{ lang.edu_institute }}</th>
                                <th rowspan="2">{{ lang.edu_start }}</th>
                                <th rowspan="2">{{ lang.edu_finish }}</th>
                            </tr>
                            <tr>
                                <th>{{ lang.edu_school_name }}</th>
                                <th>{{ lang.edu_school_location }}</th>
                            </tr>
                            </thead>
                            <tbody>
                                <template v-for="(edu, i) in application.educations">
                                    <tr>
                                        <td>{{ edu.degree }}</td>
                                        <td>{{ edu.qualification }}</td>
                                        <td>{{ edu.subject }}</td>
                                        <td>{{ edu.language }}</td>
                                        <td>{{ edu.school_name }}</td>
                                        <td>{{ edu.region }}</td>
                                        <td>{{ edu.date_start }}</td>
                                        <td>{{ edu.date_finish }}</td>
                                        <td>
                                            <a-popconfirm
                                                :title="lang.delete_confirm"
                                                :ok-text="lang.yes"
                                                :cancel-text="lang.no"
                                                @confirm="deleteItem(i)"
                                            >
                                                <span class="text-red-500">
                                                <CloseSquareOutlined />
                                                </span>
                                            </a-popconfirm>
                                            <span class="text-green-500 pl-2" @click="editItem(i)">
                                                <FormOutlined />
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                </div>
                    <a-divider />
                    <a-form 
                        :model="education" 
                        layout="vertical" 
                        :rules="rules" 
                        :validate-messages="validateMessages"
                        @finish="onFinish" 
                        @finishFailed="onFinishFailed"
                    >
                        <a-row :gutter="10">
                            <a-col :span="12">
                                <a-form-item :label="lang.edu_degree" name="degree">
                                    <a-input v-model:value="education.degree" />
                                </a-form-item>
                                <a-form-item :label="lang.edu_program" name="qualification">
                                    <a-input v-model:value="education.qualification" />
                                    {{ lang.edu_program_note }}
                                </a-form-item>
                            </a-col>
                            <a-col :span="12">
                                <a-row :gutter="8">
                                    <a-col :span="12">
                                        <a-form-item :label="lang.edu_lang" name="language">
                                            <a-input v-model:value="education.language" />
                                        </a-form-item>
                                    </a-col>
                                    <a-col :span="12">
                                        <a-form-item :label="lang.edu_school_location" name="region">
                                            <a-input v-model:value="education.region" />
                                        </a-form-item>
                                    </a-col>
                                </a-row>
                                <a-form-item :label="lang.edu_start" name="date_start">
                                    <a-input v-model:value="education.date_start" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="10">
                            <a-col :span="12">
                                <a-form-item :label="lang.edu_subject" name="subject">
                                    <a-input v-model:value="education.subject" />
                                    <div v-html="lang.edu_subject_note"/>
                                </a-form-item>
                            </a-col>
                            <a-col :span="12">
                                <a-form-item :label="lang.edu_finish" name="date_finish">
                                    <a-input v-model:value="education.date_finish" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-form-item :label="lang.edu_institute" name="school_name">
                                    <a-input v-model:value="education.school_name" />
                                </a-form-item>

                        <a-form-item :wrapper-col="{ span: 24, offset: 11, }">
                            <a-button type="primary" html-type="submit">{{ lang.add_item }}</a-button>
                        </a-form-item>
                    </a-form>
                    <div class="text-center text-md font-bold ">{{ lang.lang_knowledge }}</div>
                    <div class="font-bold">{{ lang.lang_mother }}</div>
                    <div><a-select v-model:value="application.language.MONTHER" :options="lang.lang_mother_options" style="width:200px"/></div>
                    <table class="myTable" width="100%">
                        <tr>
                            <th>{{ lang.language }}</th>
                            <th>{{ lang.lang_write }}</th>
                            <th>{{ lang.lang_speak }}</th>
                            <th>{{ lang.lang_level }}<br>{{ lang.if_applicable }}</th>
                        </tr>
                        <tr>
                            <td>{{ lang.lang_can }}</td>
                            <td rowspan="2"><a-select v-model:value="application.language.MAN.WRITE" :options="lang.lang_level_field_options" style="width:100%"/></td>
                            <td><a-select v-model:value="application.language.CAN.SPEAK" :options="lang.lang_level_field_options" style="width:100%"/></td>
                            <td><a-input v-model:value="application.language.CAN.LEVEL" style="width:100%"/></td>
                        </tr>
                        <tr>
                            <td>{{ lang.lang_man }}</td>
                            <td><a-select v-model:value="application.language.MAN.SPEAK" :options="lang.lang_level_field_options" style="width:100%"/></td>
                            <td><a-input v-model:value="application.language.MAN.LEVEL" style="width:100%"/></td>
                        </tr>
                        <tr>
                            <td>{{ lang.lang_por }}</td>
                            <td><a-select v-model:value="application.language.POR.WRITE" :options="lang.lang_level_field_options" style="width:100%"/></td>
                            <td><a-select v-model:value="application.language.POR.SPEAK" :options="lang.lang_level_field_options" style="width:100%"/></td>
                            <td><a-input v-model:value="application.language.POR.LEVEL" style="width:100%"/></td>
                        </tr>
                        <tr>
                            <td>{{ lang.lang_eng }}</td>
                            <td><a-select v-model:value="application.language.ENG.WRITE" :options="lang.lang_level_field_options" style="width:100%"/></td>
                            <td><a-select v-model:value="application.language.ENG.SPEAK" :options="lang.lang_level_field_options" style="width:100%"/></td>
                            <td><a-input v-model:value="application.language.ENG.LEVEL" style="width:100%"/></td>
                        </tr>
                        <tr>
                            <td>{{ lang.lang_oth }}</td>
                            <td><a-select v-model:value="application.language.OTH.WRITE" :options="lang.lang_level_field_options" style="width:100%"/></td>
                            <td><a-select v-model:value="application.language.OTH.SPEAK" :options="lang.lang_level_field_options" style="width:100%"/></td>
                            <td><a-input v-model:value="application.language.OTH.LEVEL" style="width:100%"/></td>
                        </tr>
                    </table>
                </template>
            </CardBox>
            <div class="text-center pt-5">
                <a :href="route('recruitment.admin.apply', { code: vacancy.code, page: this.page.previours })" 
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
import recLang  from '/lang/recruitment_admin.json';
import { message } from 'ant-design-vue';
import { CloseSquareOutlined,FormOutlined } from '@ant-design/icons-vue';

export default {
    components: {
        MemberLayout,
        CaretRightOutlined,
        CardBox,
        CloseSquareOutlined,FormOutlined
    },
    props: ['vacancy', 'application'],
    data() {
        return {
            page: {},
            userLanguages:{
                "CAN":{"WRITE":"non", "SPEAK":"non", "LEVEL":null},
                "MAN":{"WRITE":"non", "SPEAK":"non", "LEVEL":null},
                "POR":{"WRITE":"non", "SPEAK":"non", "LEVEL":null},
                "ENG":{"WRITE":"non", "SPEAK":"non", "LEVEL":null},
                "OTH":{"WRITE":"non", "SPEAK":"non", "LEVEL":null}
            },
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
            if(this.application.educations.length==0){
                message.error(this.lang.at_least_one_education);
                return false;
            }
            this.$inertia.post(route('recruitment.admin.save'), { to_page: 3, application: this.application }, {
                onSuccess: (page) => {
                    console.log('save & update success')
                },
                onError: (err) => {
                    console.log(err)
                }
            });
        },
        onFinish() {
            this.application.educations.push({ ...this.education })
            this.education = {};
            
        },
        onFinishFailed(){
            message.error(this.lang.error_required_fields);
        },
        deleteItem(i){
            this.application.educations.splice(i,1)
        },
        editItem(i){
            this.education=this.application.educations[i]
            this.application.educations.splice(i,1)
        }
    },
    computed:{
        validateMessages() {
            return {
                required: '${label}' + this.$t('is_required'),
                types: {
                    email: this.$t('is_not_email'),
                    number: '${label} ' + this.$t('is_no_number'),
                },
                number: {
                    //range: '${label} must be between ${min} and ${max}',
                    range: '${label} ' + this.$t('must_between') + ' ${min} - ${max}',
                },
            }
        },
    }
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
