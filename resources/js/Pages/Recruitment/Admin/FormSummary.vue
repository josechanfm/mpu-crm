<template>
    <table width="100%">
        <tr>
            <td class="p-2">
                <div>{{ lang.unit }}: {{ vacancy.apply_in }}</div>
                <div>{{ lang.code }}: {{ vacancy.code }}</div>
                <div>{{ lang.title }}: {{ vacancy['title_' + $page.props.lang] }}</div>
            </td>
            <td class="p-2">
                <div>{{ lang.obtain_info }}</div>
                <div>{{ lang.obtain_info_web }}</div>
                <div>{{ lang.obtain_info_new }}</div>
                <div>{{ lang.obtain_info_oth }}</div>
            </td>
        </tr>
    </table>
    <table class="mt-5" width="100%">
        <tr>
            <th colspan="4" style="text-align: left;">
                {{ lang.personal_info }}
                <a-button v-if="!application.submitted"
                    :href="route('recruitment.admin.apply', { 'code': vacancy.code, 'page': 1 })"
                    class="ant-btn ant-btn-primary float-right ml-5">Edit</a-button>
                <div class="float-right">{{ lang.part_aca_a }}</div>
            </th>
        </tr>
        <tr>
            <th width="200px">{{ lang.name_full_zh }}</th>
            <td width="40%">{{ application.name_full_zh }}</td>
            <th width="150px">{{ lang.gender }}</th>
            <td>{{ application.gender }}</td>
        </tr>
        <tr>
            <th>{{ lang.name_family_fn }}</th>
            <td colspan="3">{{ application.name_family_fn }}</td>
        </tr>
        <tr>
            <th>{{ lang.name_given_fn }}</th>
            <td colspan="3">{{ application.name_given_fn }}</td>
        </tr>
        <tr>
            <th>{{ lang.pob }}</th>
            <td>{{ application.pob }}</td>
            <th>{{ lang.dob }}</th>
            <td>{{ application.dob }}</td>
        </tr>
        <tr>
            <th>{{ lang.id_type }}</th>
            <td>
                <template v-if="application.id_type == 'OTH'">
                    {{ application.id_type_name }}
                </template>
                <template v-else>
                    {{ application.id_type_type }}
                </template>
            </td>
            <th>{{ lang.id_num }}</th>
            <td>{{ application.id_num }}</td>
        </tr>
        <tr>
            <th>{{ lang.nationality }}</th>
            <td colspan="3">
                {{ application.nationality }}
                {{ application.nationality_oth }}
            </td>
        </tr>
        <tr>
            <th>{{ lang.address }}</th>
            <td colspan="3">{{ application.address }}</td>
        </tr>
        <tr>
            <th>{{ lang.phone }}</th>
            <td>{{ application.phone }}</td>
            <th>{{ lang.email }}</th>
            <td>{{ application.email }}</td>
        </tr>
    </table>
    <table class="mt-5" width="100%">
        <tr>
            <th colspan="8" style="text-align: left;">
                {{ lang.educations }}
                <a-button v-if="!application.submitted"
                    :href="route('recruitment.admin.apply', { 'code': vacancy.code, 'page': 2 })"
                    class="ant-btn ant-btn-primary float-right ml-5">Edit</a-button>
                <div class="float-right">{{ lang.part_aca_b }}</div>
            </th>
        </tr>
        <tr>
            <th colspan="2"> {{ lang.edu_institute }}</th>
            <th rowspan="2">{{ lang.edu_degree }}</th>
            <th rowspan="2">{{ lang.edu_subject }}</th>
            <th rowspan="2">{{ lang.edu_language }}</th>
            <th colspan="2">{{ lang.edu_date }}</th>
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
                {{ lang.professional }}
                <a-button v-if="!application.submitted"
                    :href="route('recruitment.admin.apply', { 'code': vacancy.code, 'page': 3 })"
                    class="ant-btn ant-btn-primary float-right ml-5">Edit</a-button>
                <div class="float-right">{{ lang.part_aca_c }}</div>
            </th>
        </tr>
        <tr>
            <th colspan="2"> {{ lang.prof_organization }}</th>
            <th rowspan="2">{{ lang.prof_qualification }}</th>
            <th rowspan="2">{{ lang.prof_area }}</th>
            <th colspan="2">{{ lang.prof_date }}</th>
        </tr>
        <tr>
            <th>{{ lang.prof_organization_name }}</th>
            <th>{{ lang.prof_region }}</th>
            <th>{{ lang.prof_date_valid }}</th>
            <th>{{ lang.prof_date_expired }}</th>
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
                {{ lang.experiences }}
                <a-button v-if="!application.submitted"
                    :href="route('recruitment.admin.apply', { 'code': vacancy.code, 'page': 4 })"
                    class="ant-btn ant-btn-primary float-right ml-5">Edit</a-button>
                <div class="float-right">{{ lang.part_aca_d }}</div>
            </th>
        </tr>
        <tr>
            <th colspan="2"> {{ lang.exp_company }}</th>
            <th rowspan="2">{{ lang.exp_position }}</th>
            <th rowspan="2">{{ lang.exp_salary }}</th>
            <th rowspan="2">{{ lang.exp_employment }}</th>
            <th colspan="2">{{ lang.exp_date }}</th>
        </tr>
        <tr>
            <th>{{ lang.exp_company_name }}</th>
            <th>{{ lang.exp_region }}</th>
            <th>{{ lang.exp_date_join }}</th>
            <th>{{ lang.exp_date_leave }}</th>
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
    <table class="mt-5" width="100%">
        <tr>
            <th colspan="2" style="text-align: left;">
                {{ lang.file_uploaded }}
                <a-button v-if="!application.submitted"
                    :href="route('recruitment.admin.apply', { 'code': vacancy.code, 'page': 5 })"
                    class="ant-btn ant-btn-primary float-right ml-5">{{ lang.edit }}</a-button>
            </th>
        </tr>
        <tr>
            <th style="text-align: left;" width="250px">{{ lang.doc_id}}</th>
            <td>
                <ol>
                    <li v-for="file in getFileList('doc_id')">
                        {{file.original_name}}
                    </li>
                </ol>
            </td>
        </tr>
        <tr>
            <th style="text-align: left;">{{ lang.doc_education }}</th>
            <td>
                <ol>
                    <li v-for="file in getFileList('doc_education')">
                        {{file.original_name}}
                    </li>
                </ol>
            </td>
        </tr>
        <tr>
            <th style="text-align: left;">{{ lang.doc_resume }}</th>
            <td>
                <ol>
                    <li v-for="file in getFileList('doc_resume')">
                        {{file.original_name}}
                    </li>
                </ol>
            </td>
        </tr>
        <tr>
            <th style="text-align: left;">{{ lang.doc_employment }}</th>
            <td>
                <ol>
                    <li v-for="file in getFileList('doc_employment')">
                        {{file.original_name}}
                    </li>
                </ol>
            </td>
        </tr>
        <tr>
            <th style="text-align: left;">{{ lang.doc_training }}</th>
            <td>
                <ol>
                    <li v-for="file in getFileList('doc_training')">
                        {{file.original_name}}
                    </li>
                </ol>
            </td>
        </tr>
        <tr>
            <th style="text-align: left;">{{ lang.doc_academic }}</th>
            <td>
                <ol>
                    <li v-for="file in getFileList('doc_academic')">
                        {{file.original_name}}
                    </li>
                </ol>
            </td>
        </tr>
        <tr>
            <th style="text-align: left;">{{ lang.doc_other }}</th>
            <td>
                <ol>
                    <li v-for="file in getFileList('doc_other')">
                        {{file.original_name}}
                    </li>
                </ol>
            </td>
        </tr>
    </table>
</template>

<script>
export default {
    props: ['vacancy', 'application'],
    data(){
        return {
            lang:null,
        }
    },
    created() {
        this.lang = recLang[this.$page.props.lang]
    },
    mounted() {
        optionItem(options, value){
                let option=options.find(o=>o.value==value)
                if(option){
                    return option.label
                }else{
                    return null;
                }
        },

    }
    
}
</script>

<style scoped>
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
