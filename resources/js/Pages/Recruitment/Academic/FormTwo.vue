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
                <a-step v-for="item in lang.steps" :description="item.title" :key="item.title"/>
            </a-steps>
        </div>
        <template v-if="$page.props.env == 'local'">
            <a-button @click="sampleData">Sample Data</a-button>
        </template>
        <div class="container bg-white rounded mx-auto p-5">
            <CardBox :title="lang.part_A" :subtitle="lang.part_a">
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
                        <template v-for="(edu, i) in application.educations">
                            <tr>
                                <td>{{ edu.school_name }}</td>
                                <td>{{ edu.region }}</td>
                                <td>{{ edu.degree }}</td>
                                <td>{{ edu.subject }}</td>
                                <td>{{ edu.language }}</td>
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
                                <a-form-item :label="lang.edu_qualification" name="qualification">
                                    <a-select v-model:value="education.qualification" :options="lang.education_qualifications"/>
                                </a-form-item>
                            </a-col>
                            <a-col :span="8">
                                <a-form-item :label="lang.edu_degree" name="degree">
                                    <a-input v-model:value="education.degree" />
                                </a-form-item>
                            </a-col>
                            <a-col :span="8">
                                <a-form-item :label="lang.edu_subject" name="subject">
                                    <a-input v-model:value="education.subject" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="10">
                            <a-col :span="8">
                                <a-form-item :label="lang.edu_language" name="language">
                                    <a-input v-model:value="education.language"/>
                                </a-form-item>
                            </a-col>
                            <a-col :span="8">
                                <a-form-item :label="lang.edu_date_start" name="date_start">
                                    <a-date-picker 
                                        v-model:value="education.date_start" 
                                        :format="dateFormat"
                                        :valueFormat="dateFormat" 
                                         picker="month"
                                    />
                                </a-form-item>
                            </a-col>
                            <a-col :span="8">
                                <a-form-item :label="lang.edu_date_finish" name="date_finsih">
                                    <a-date-picker 
                                        v-model:value="education.date_finish" 
                                        :format="dateFormat"
                                        :valueFormat="dateFormat" 
                                         picker="month"
                                    />
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
            education: {},
            dateFormat: 'YYYY-MM',
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
            this.$inertia.post(route('recruitment.academic.save'), { to_page: this.page.next, application: this.application }, {
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
            this.$inertia.delete(route('recruitment.academic.delete',{
                model:'education',
                recordId:this.application.educations[i].id
            }),{
                onSuccess: (page) => {
                    console.log('save & update success')
                },
                onError: (err) => {
                    console.log(err)
                }
            });
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
