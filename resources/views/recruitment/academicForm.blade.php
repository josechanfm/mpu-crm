<!doctype html>
<html lang="en">
<head>
</head>

<body>
    <div class="wrapper" style="font-family: SimHei">
        <div class="wrapper">
            <div class="error-spacer"></div>
            <div role="main" class="main">
                <div style="text-align: center;font-size: 24px;">Applicaton form in pdf format.</div>

                                <table width="100%">
                                    <tr>
                                        <td class="p-2">
                                            <div>{{ $lang->unit }}: {{ $application->vacancy->apply_in }}</div>
                                            <div>{{ $lang->code }}: {{ $application->vacancy->code }}</div>
                                            <div>{{ $lang->title }}: {{ $application->vacancy['title_zh'] }}</div>
                                        </td>
                                        <td class="p-2">
                                            <div>{{ $lang->obtain_info }}</div>
                                            <div>{{ $lang->obtain_info_web }}</div>
                                            <div>{{ $lang->obtain_info_new }}</div>
                                            <div>{{ $lang->obtain_info_oth }}</div>
                                        </td>
                                    </tr>
                                </table>

                                <table class="mt-5" width="100%">
                                    <tr>
                                        <th colspan="4" style="text-align: left;">
                                            {{ $lang->personal_info }}
                                            <a-button v-if="!application.submitted"
                                                :href="route('recruitment.academic.apply', { 'code': vacancy.code, 'page': 1 })"
                                                class="ant-btn ant-btn-primary float-right ml-5">Edit</a-button>
                                            <div class="float-right">{{ $lang->part_a }}</div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th width="200px">{{ $lang->name_full_zh }}</th>
                                        <td width="40%">{{ $application->name_full_zh }}</td>
                                        <th width="150px">{{ $lang->gender }}</th>
                                        <td>{{ $application->gender }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ $lang->name_family_fn }}</th>
                                        <td colspan="3">{{ $application->name_family_fn }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ $lang->name_given_fn }}</th>
                                        <td colspan="3">{{ $application->name_given_fn }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ $lang->pob }}</th>
                                        <td>{{ $application->pob }}</td>
                                        <th>{{ $lang->dob }}</th>
                                        <td>{{ $application->dob }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ $lang->id_type }}</th>
                                        <td>
                                            <template v-if="application.id_type == 'OTH'">
                                                {{ $application->id_type_name }}
                                            </template>
                                            <template v-else>
                                                {{ $application->id_type_type }}
                                            </template>
                                        </td>
                                        <th>{{ $lang->id_num }}</th>
                                        <td>{{ $application->id_num }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ $lang->nationality }}</th>
                                        <td colspan="3">
                                            {{ $application->nationality }}
                                            {{ $application->nationality_oth }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ $lang->address }}</th>
                                        <td colspan="3">{{ $application->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ $lang->phone }}</th>
                                        <td>{{ $application->phone }}</td>
                                        <th>{{ $lang->email }}</th>
                                        <td>{{ $application->email }}</td>
                                    </tr>
                                </table>
                
                                <table class="mt-5" width="100%">
                                    <tr>
                                        <th colspan="8" style="text-align: left;">
                                            {{ $lang->educations }}
                                            <a-button v-if="!application.submitted"
                                                :href="route('recruitment.academic.apply', { 'code': vacancy.code, 'page': 2 })"
                                                class="ant-btn ant-btn-primary float-right ml-5">Edit</a-button>
                                            <div class="float-right">{{ $lang->part_b }}</div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="2"> {{ $lang->edu_institute }}</th>
                                        <th rowspan="2">{{ $lang->edu_degree }}</th>
                                        <th rowspan="2">{{ $lang->edu_subject }}</th>
                                        <th rowspan="2">{{ $lang->edu_language }}</th>
                                        <th colspan="2">{{ $lang->edu_date }}</th>
                                    </tr>
                                    <tr>
                                        <th>{{ $lang->edu_school_name }}</th>
                                        <th>{{ $lang->edu_region }}</th>
                                        <th>{{ $lang->edu_date_start }}</th>
                                        <th>{{ $lang->edu_date_finish }}</th>
                                    </tr>
                                    <template v-for="education in application.educations">
                                        @if($application->educatoin)
                                        <tr>
                                            <td>{{ $application->education->school_name }}</td>
                                            <td>{{ $application->education->region }}</td>
                                            <td>{{ optionItem(educationOptions,education.degree)}}</td>
                                            <td>{{ $application->education->subject }}</td>
                                            <td>{{ optionItem(languageOptions,education.language)}}</td>
                                            <td>{{ $application->education->date_start }}</td>
                                            <td>{{ $application->education->date_finish }}</td>
                                        </tr>
                                        @endif
                                    </template>
                                </table>
                                <table class="mt-5" width="100%">
                                    <tr>
                                        <th colspan="6" style="text-align: left;">
                                            {{ $lang->professional }}
                                            <a-button v-if="!application.submitted"
                                                :href="route('recruitment.academic.apply', { 'code': vacancy.code, 'page': 3 })"
                                                class="ant-btn ant-btn-primary float-right ml-5">Edit</a-button>
                                            <div class="float-right">{{ $lang->part_c }}</div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="2"> {{ $lang->prof_organization }}</th>
                                        <th rowspan="2">{{ $lang->prof_qualification }}</th>
                                        <th rowspan="2">{{ $lang->prof_area }}</th>
                                        <th colspan="2">{{ $lang->prof_date }}</th>
                                    </tr>
                                    <tr>
                                        <th>{{ $lang->prof_organization_name }}</th>
                                        <th>{{ $lang->prof_region }}</th>
                                        <th>{{ $lang->prof_date_valid }}</th>
                                        <th>{{ $lang->prof_date_expire }}</th>
                                    </tr>
                                    <template v-for="professional in application.professionals">
                                        @if($application->professional)
                                        <tr>
                                            <td>{{ $application->professional->organization_name }}</td>
                                            <td>{{ $application->professional->region }}</td>
                                            <td>{{ $application->professional->qualification }}</td>
                                            <td>{{ $application->professional->area }}</td>
                                            <td>{{ $application->professional->date_valid }}</td>
                                            <td>{{ $application->professional->date_expired }}</td>
                                        </tr>
                                        @endif
                                    </template>
                                </table>
                                <table class="mt-5" width="100%">
                                    <tr>
                                        <th colspan="7" style="text-align: left;">
                                            {{ $lang->experiences }}
                                            <a-button v-if="!application.submitted"
                                                :href="route('recruitment.academic.apply', { 'code': vacancy.code, 'page': 4 })"
                                                class="ant-btn ant-btn-primary float-right ml-5">Edit</a-button>
                                            <div class="float-right">{{ $lang->part_d }}</div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="2"> {{ $lang->exp_company }}</th>
                                        <th rowspan="2">{{ $lang->exp_position }}</th>
                                        <th rowspan="2">{{ $lang->exp_salary }}</th>
                                        <th rowspan="2">{{ $lang->exp_employment }}</th>
                                        <th colspan="2">{{ $lang->exp_date }}</th>
                                    </tr>
                                    <tr>
                                        <th>{{ $lang->exp_company_name }}</th>
                                        <th>{{ $lang->exp_region }}</th>
                                        <th>{{ $lang->exp_date_join }}</th>
                                        <th>{{ $lang->exp_date_leave }}</th>
                                    </tr>
                                    <template v-for="experience in application.experiences">
                                        @if($application->experience)
                                        <tr>
                                            <td>{{ $application->experience->company_name }}</td>
                                            <td>{{ $application->experience->region }}</td>
                                            <td>{{ $application->experience->position }}</td>
                                            <td>{{ $application->experience->salary }}</td>
                                            <td>
                                                {{ optionItem(employmentOptions,experience.employment)}}
                                            </td>
                                            <td>{{ $application->experience->date_join }}</td>
                                            <td>{{ $application->experience->date_leave }}</td>
                                        </tr>
                                        @endif
                                    </template>
                                </table>
                                <table class="mt-5" width="100%">
                                    <tr>
                                        <th colspan="2" style="text-align: left;">
                                            {{ $lang->file_uploaded }}
                                            <a-button v-if="!application.submitted"
                                                :href="route('recruitment.academic.apply', { 'code': vacancy.code, 'page': 5 })"
                                                class="ant-btn ant-btn-primary float-right ml-5">{{ $lang->edit }}</a-button>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left;" width="250px">{{ $lang->doc_id}}</th>
                                        <td>
                                            <ol>
                                                @foreach ($application->uploads as $file)
                                                <li >
                                                    {{ $file->original_name}}
                                                </li>
                                                @endforeach
                                            </ol>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left;">{{ $lang->doc_education }}</th>
                                        <td>
                                            <ol>
                                                <li v-for="file in getFileList('doc_education')">
                                                    {{ $file->original_name}}
                                                </li>
                                            </ol>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left;">{{ $lang->doc_resume }}</th>
                                        <td>
                                            <ol>
                                                <li v-for="file in getFileList('doc_resume')">
                                                    {{ $file->original_name}}
                                                </li>
                                            </ol>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left;">{{ $lang->doc_employment }}</th>
                                        <td>
                                            <ol>
                                                <li v-for="file in getFileList('doc_employment')">
                                                    {{ $file->original_name}}
                                                </li>
                                            </ol>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left;">{{ $lang->doc_training }}</th>
                                        <td>
                                            <ol>
                                                <li v-for="file in getFileList('doc_training')">
                                                    {{ $file->original_name}}
                                                </li>
                                            </ol>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left;">{{ $lang->doc_academic }}</th>
                                        <td>
                                            <ol>
                                                <li v-for="file in getFileList('doc_academic')">
                                                    {{ $file->original_name}}
                                                </li>
                                            </ol>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left;">{{ $lang->doc_other }}</th>
                                        <td>
                                            <ol>
                                                <li v-for="file in getFileList('doc_other')">
                                                    {{ $file->original_name}}
                                                </li>
                                            </ol>
                                        </td>
                                    </tr>
                                </table>                
            </div>
        </div>
</body>

</html>

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