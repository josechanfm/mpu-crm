<template>
    <MemberLayout title="Vacancies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ vacancy.code }}
                {{ vacancy['title_' + $page.props.lang] }}
            </h2>
        </template>
        <div class="p-5">
            <a-steps  progress-dot :current="this.page.current-1" @change="onChangeStep">
                <a-step v-for="item in lang.steps" :description="item.title"/>
            </a-steps>
        </div>
        <template v-if="$page.props.env == 'local'">
            <a-button @click="sampleData">Sample Data</a-button>
        </template>
        <div class="container bg-white rounded mx-auto p-5">
            <CardBox :title="lang.part_C" :subtitle="lang.part_c">
                <template #content>
                    <table width="100%">
                        <tr>
                            <th colspan="2">{{ lang.exp_company }}</th>
                            <th rowspan="2">{{ lang.exp_position }}</th>
                            <th rowspan="2">{{ lang.exp_salary }}</th>
                            <th rowspan="2">{{ lang.exp_employment }}</th>
                            <th colspan="2">{{ lang.exp_date }}</th>
                            <th rowspan="2">{{ lang.operation }}</th>
                        </tr>
                        <tr>
                            <th>{{ lang.exp_company_name }}</th>
                            <th>{{ lang.exp_region }}</th>
                            <th>{{ lang.exp_date_join }}</th>
                            <th>{{ lang.exp_date_leave }}</th>
                        </tr>
                        <template v-for="(exp, i) in application.experiences">
                            <tr>
                                <td>{{ exp.company_name }}</td>
                                <td>{{ exp.region }}</td>
                                <td>{{ exp.position }}</td>
                                <td>{{ exp.salary }}</td>
                                <td>{{ exp.employment }}</td>
                                <td>{{ exp.date_join }}</td>
                                <td>{{ exp.date_leave }}</td>
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
                        :model="experience" 
                        layout="vertical" 
                        :rules="rules" 
                        :validate-messages="validateMessages"
                        @finish="onFinish" 
                        @finishFailed="onFinishFailed"
                    >
                        <a-row :gutter="10">
                            <a-col :span="16">
                                <a-form-item :label="lang.exp_company_name" name="company_name">
                                    <a-input v-model:value="experience.company_name" />
                                </a-form-item>
                            </a-col>
                            <a-col :span="8">
                                <a-form-item :label="lang.exp_region" name="region">
                                    <a-input v-model:value="experience.region" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="10">
                            <a-col :span="8">
                                <a-form-item :label="lang.exp_position" name="position">
                                    <a-input v-model:value="experience.position" />
                                </a-form-item>
                            </a-col>
                            <a-col :span="8">
                                <a-form-item :label="lang.exp_salary">
                                    <a-input v-model:value="experience.salary" />
                                </a-form-item>
                            </a-col>
                            <a-col :span="8">
                                <a-form-item :label="lang.exp_employment" name="employment">
                                    <a-radio-group v-model:value="experience.employment" :options="lang.exp_employmentOptions" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="10">
                            <a-col :span="12">
                                <a-form-item :label="lang.exp_date_join" name="date_join">
                                    <a-date-picker v-model:value="experience.date_join" :format="dateFormat"
                                        :valueFormat="dateFormat" />
                                </a-form-item>
                            </a-col>
                            <a-col :span="12">
                                <a-form-item :label="lang.exp_date_leave">
                                    <a-date-picker v-model:value="experience.date_leave" :format="dateFormat"
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
            experience: {},
            employmentOptions: [],
            dateFormat: 'YYYY-MM-DD',
            rules: {
                company_name: { required: true },
                position: { required: true },
                region: { required: true },
                employment: { required: true },
                date_join: { required: true },
            },
            activeTag: '1',
            activeCollapse: null,
            customStyle: 'background: #509f7f;border-radius: 4px;margin-bottom: 24px;border: 1;overflow: hidden',
        }
    },
    created() {
        this.lang = recLang[this.$page.props.lang]
        // axios.get(route('api.config.item', { key: 'rec_employment_types' }))
        //     .then(res => {
        //         this.employmentOptions = res.data[this.$page.props.lang].value
        //         this.experience.employment = this.employmentOptions[0].value
        //     })
        //     .then(err => {
        //         console.log(err)
        //     })

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
            this.experience.company_name = 'Company A'
            this.experience.region = 'Macao'
            this.experience.position = 'Manager'
            this.experience.salary = '123456'
            this.experience.employment = 'FULL'
            this.experience.date_join = '2020-01-01'
            this.experience.date_leave = '2022-01-01'
        },
        onChangeStep(stepId){
            if((stepId+1)<this.page.current){
                this.page.next=stepId+1
                this.saveToNext();
            }
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
            this.application.experiences.push({ ...this.experience })
            this.experience = {};
            console.log(this.application);
        },
        onFinishFailed(){
            message.error(this.lang.error_required_fields);
        },
        deleteItem(i){
            this.$inertia.delete(route('recruitment.academic.delete',{
                model:'experience',
                recordId:this.application.experiences[i].id
            }),{
                onSuccess: (page) => {
                    console.log(page.data)
                },
                onError: (err) => {
                    console.log(err)
                }
            });
        },
        editItem(i){
            this.experience=this.application.experiences[i]
            this.application.experiences.splice(i,1)
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

table,
table tr th,
table tr td {
    border: 1px solid gray;
    padding: 5px
}
</style>
