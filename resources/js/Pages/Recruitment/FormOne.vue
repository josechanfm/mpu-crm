<template>
    <RecruitmentLayout title="Vacancies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ vacancy.code }}
                {{ vacancy['title_' + $page.props.lang] }}
            </h2>
        </template>
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="text-lg text-white bg-emerald-500 rounded p-3">{{ $t('login_success') }}</div>
        </div>

        <template v-if="$page.props.env == 'local'">
            <a-button @click="sampleData">Sample Data</a-button>
        </template>
        <a-form :model="application" layout="vertical" :rules="rules" @finish="onFinish">
            <CardBox :title="$t('rec.position_info')">
                <template #content>
                    <a-row>
                        <a-col :span="16">
                            <p>{{ $t('rec.unit') }}: {{ vacancy.apply_in }}</p>
                            <p>{{ $t('rec.code') }}: {{ vacancy.code }}</p>
                            <p>{{ $t('rec.title') }}: {{ vacancy['title_' + $page.props.lang] }}</p>
                        </a-col>
                        <a-col>
                            <p>{{ $t('rec.obtain_info') }}</p>
                            <a-checkbox-group v-model:value="application.obtain_info"
                                style="display: flex; flex-direction: column;margin-left:8">
                                <a-checkbox value="WEB">{{ $t('rec.obtain_info_web') }}</a-checkbox>
                                <a-row>
                                    <a-col>
                                        <a-checkbox value="NEW">{{ $t('rec.obtain_info_new') }}</a-checkbox>
                                    </a-col>
                                    <a-col>
                                        <a-input v-model:value="application.obtain_info_new" />
                                    </a-col>
                                </a-row>
                                <a-row>
                                    <a-col>
                                        <a-checkbox value="OTH">{{ $t('rec.obtain_info_oth') }}</a-checkbox>
                                    </a-col>
                                    <a-col>
                                        <a-input v-model:value="application.obtain_info_oth" />
                                    </a-col>
                                </a-row>

                            </a-checkbox-group>
                        </a-col>
                    </a-row>
                </template>
            </CardBox>

            <CardBox :title="$t('rec.notice_title')" themeColor="amber-500">
                <template #content>
                    <div class="pl-5 pr-3">
                        <div v-html="$t('rec.notice_content')" />
                    </div>
                </template>
            </CardBox>
            <CardBox :title="$t('rec.declaration_title')" themeColor="amber-500">
                <template #content>
                    <div class="pl-5 pr-3">
                        <div v-html="$t('rec.declaration_content')" />
                    </div>
                </template>
            </CardBox>
            <CardBox :title="$t('rec.personal_info')">
                <template #content>
                    <a-row :gutter="12">
                        <a-col :span="16">
                            <a-form-item :label="$t('rec.name_zh')" name="name_zh">
                                <a-input v-model:value="application.name_zh" />
                            </a-form-item>
                        </a-col>
                        <a-col :span="8">
                            <a-form-item :label="$t('rec.gender')" name="gender">
                                <a-radio-group v-model:value="application.gender">
                                    <a-radio value="M">{{ $t('rec.male') }}</a-radio>
                                    <a-radio value="F">{{ $t('rec.female') }}</a-radio>
                                </a-radio-group>
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-form-item :label="$t('rec.first_name_fn')" name="first_name_fn">
                        <a-input v-model:value="application.gender" />
                    </a-form-item>
                    <a-form-item :label="$t('rec.last_name_fn')" name="last_name_fn">
                        <a-input v-model:value="application.gender" />
                    </a-form-item>
                    <a-row :gutter="12">
                        <a-col :span="16">
                            <a-form-item :label="$t('rec.pob')" name="pob">
                                <a-radio-group v-model:value="application.pob">
                                    <template v-for="(item, key) in $t('rec.pob_options')">
                                        <a-radio :value="key">{{ item }}</a-radio>
                                    </template>
                                </a-radio-group>
                            </a-form-item>
                        </a-col>
                        <a-col :span="8">
                            <a-form-item :label="$t('rec.dob')" name="dob">
                                <a-input v-model:value="application.dob" />
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-row :gutter="12">
                        <a-col :span="12">
                            <a-form-item :label="$t('rec.id_type')" name="id_type">
                                <a-select v-model:value="application.id_type">
                                    <template v-for="(item, key) in $t('rec.id_type_options')">
                                        <a-select-option :value="key">{{ item }}</a-select-option>
                                    </template>
                                </a-select>
                            </a-form-item>
                        </a-col>
                        <a-col :span="12">
                            <a-form-item :label="$t('rec.id_type_name')" name="id_type_name">
                                <a-input v-model:value="application.id_type_name" />
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-row :gutter="12">
                        <a-col :span="12">
                            <a-form-item :label="$t('rec.id_num')" name="id_num">
                                <a-input v-model:value="application.id_num" />
                            </a-form-item>
                        </a-col>
                        <a-col :span="12">
                            <a-form-item :label="$t('rec.nationality')" name="nationality">
                                <a-radio-group v-model:value="application.nationality">
                                    <template v-for="(item, key) in $t('rec.nationality_options')">
                                        <a-radio :value="key">{{ item }}</a-radio>
                                    </template>
                                </a-radio-group>

                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-form-item :label="$t('rec.address')" name="address">
                        <a-input v-model:value="application.address" />
                    </a-form-item>
                    <a-row :gutter="12">
                        <a-col :span="12">
                            <a-form-item :label="$t('rec.phone')" name="phone">
                                <a-input v-model:value="application.phone" />
                            </a-form-item>
                        </a-col>
                        <a-col :span="12">
                            <a-form-item :label="$t('rec.email')" name="email">
                                <a-input v-model:value="application.email" />
                            </a-form-item>
                        </a-col>
                    </a-row>
                </template>
            </CardBox>
            <!-- <div class="border border-sky-500 rounded-lg mt-5">
                    <h2 class="bg-sky-500 text-white p-4 rounded-t-lg">{{ $t('rec.personal_info') }}</h2>
                    <div class="p-4">
                        <p>Card content</p>
                    <p>Card content</p>
                    <p>Card content</p>
                    </div>
                </div> -->
            <a-divider />
            <a-form-item :wrapper-col="{ span: 24, offset: 11, }">
                <a-button type="primary" html-type="submit">{{ $t('rec.save_next') }}</a-button>
            </a-form-item>
        </a-form>
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
            this.$inertia.post(route('application.save'), { to_page: 2, application: this.application }, {
                onSuccess: (page) => {
                    console.log(page.data)
                },
                onError: (err) => {
                    console.log(err)
                }
            });
        }
    },
};

</script>
<style>
label.ant-checkbox-wrapper {
    margin-left: 8px;
}
</style>
