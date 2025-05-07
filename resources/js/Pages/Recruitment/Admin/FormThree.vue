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
            <CardBox :title="lang.part_B">
                <template #content>
                    {{ lang.listed_in_sequence }}
                    <table class="myTable" width="100%">
                        <tr>
                            <th> {{ lang.prof_qualification }}</th>
                            <th>{{ lang.prof_date_start }}</th>
                            <th>{{ lang.prof_date_end }}</th>
                            <th>{{ lang.prof_hours }}</th>
                            <th>{{ lang.prof_organization }}</th>
                            <th>{{ lang.operation }}</th>
                        </tr>
                        <template v-for="(professional,i) in application.professionals">
                            <tr>
                                <td>{{ professional.qualification }}</td>
                                <td>{{ formattedDate(professional.date_valid) }}</td>
                                <td>{{ formattedDate(professional.date_expire) }}</td>
                                <td>{{ professional.hours }}</td>
                                <td>{{ professional.organization_name }}</td>
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
                    <a-divider />
                    <a-form 
                        :model="professional" 
                        layout="vertical" 
                        :rules="rules" 
                        :validate-messages="validateMessages"
                        @finish="onFinish" 
                        @finishFailed="onFinishFailed"
                    >
                    <a-form-item :label="lang.prof_qualification" name="qualification">
                        <a-input v-model:value="professional.qualification" />
                    </a-form-item>
                    <a-form-item :label="lang.prof_organization" name="organization_name">
                        <a-input v-model:value="professional.organization_name" />
                    </a-form-item>

                        <a-row :gutter="10">
                            <a-col :span="8">
                                <a-form-item :label="lang.prof_date_start" name="date_valid">
                                    <a-date-picker v-model:value="professional.date_valid" picker="month" :format="dateFormat" :valueFormat="dateFormat"/>
                                </a-form-item>
                            </a-col>
                            <a-col :span="8">
                                <a-form-item :label="lang.prof_date_end" name="data_expire">
                                    <a-date-picker v-model:value="professional.date_expire" picker="month" :format="dateFormat" :valueFormat="dateFormat"/>
                                </a-form-item>
                            </a-col>
                            <a-col :span="8">
                                <a-form-item :label="lang.prof_hours" name="hours">
                                    <a-input v-model:value="professional.hours" />
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
import dayjs from 'dayjs';

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
            professional: {},
            dateFormat: 'YYYY-MM',
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
            this.$inertia.post(route('recruitment.admin.save'), { to_page: this.page.next, application: this.application }, {
                onSuccess: (page) => {
                    console.log('save & update success')
                },
                onError: (err) => {
                    console.log(err)
                }
            });
        },
        onFinish() {
            this.application.professionals.push({ ...this.professional })
            this.professional = {};
        },
        onFinishFailed(){
            message.error(this.lang.error_required_fields);
        },
        deleteItem(i){
            this.application.professionals.splice(i,1)
        },
        editItem(i){
            this.professional=this.application.professionals[i]
            this.application.professionals.splice(i,1)
        },
        formattedDate(date) {
            return dayjs(date).format('YYYY-MM');
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
