<template>
    <RecruitmentLayout title="Vacancies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ lang.vacancies }}
            </h2>
        </template>
        
        <div class=" flex justify-center">
            <div>
                    <a-typography-title :level="3">success</a-typography-title>
                <div><span class="font-bold text-lg pr-3">{{ lang.vacancies }}:</span>{{ vacancy.code }}{{
                    vacancy['title_' + $page.props.lang] }}</div>
                <div><span class="font-bold text-lg pr-3">{{ lang.name_full_zh }}:</span>{{ application.name_full_zh }}
                </div>
                <div><span class="font-bold text-lg pr-3">{{ lang.name_family_fn }}:</span>{{ application.name_family_fn }}
                </div>
                <div><span class="font-bold text-lg pr-3">{{ lang.name_given_fn }}:</span>{{ application.name_given_fn }}</div>
            </div>
        </div>
        <form method="post" action="https://epay.mpu.edu.mo/bocpaytest/ipm/cashier">
            <div v-for="(value, field) in payment" hidden>
                <input :name="field" :value="payment[field]" /><br>
            </div>
            <div class="text-center">
                <a :href="route('recruitment.academic.apply', { code: vacancy.code, page: 6 })"
                    class="bg-amber-500 text-white p-2 rounded-sm m-5">{{ lang.back_no_save }}</a>
                <a-button type="primary" html-type="submit" class="mt-5">{{ lang.pay_confirm }}</a-button>
            </div>
        </form>
    </RecruitmentLayout>
</template>

<script>
import RecruitmentLayout from '@/Layouts/RecruitmentLayout.vue';
import CardBox from '@/Components/CardBox.vue';
import { CaretRightOutlined } from '@ant-design/icons-vue';
import recLang from '/lang/recruitment_academic.json';

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
        this.lang = recLang[this.$page.props.lang]
    },
    mounted() {
    },
    methods: {
    },
};

</script>
<style>
label.ant-checkbox-wrapper {
    margin-left: 8px;
}
</style>
