<template>
    <MemberLayout title="Vacancies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ lang.vacancies }}
            </h2>
        </template>
        <div class="p-5">
            <a-steps progress-dot :current="page.current">
                <a-step v-for="item in lang.steps" :description="item.title" />
            </a-steps>
        </div>

        <div class="container bg-white rounded mx-auto p-5">
            <div class=" flex justify-center">
                <div>
                    <div><span class="font-bold text-lg pr-3">{{ lang.vacancies }}:</span>{{ vacancy.code }}{{
                        vacancy['title_' + $page.props.lang] }}</div>
                    <div><span class="font-bold text-lg pr-3">{{ lang.name_full_zh }}:</span>{{ application.name_full_zh }}
                    </div>
                    <div><span class="font-bold text-lg pr-3">{{ lang.name_family_fn }}:</span>{{ application.name_family_fn
                        }}
                    </div>
                    <div><span class="font-bold text-lg pr-3">{{ lang.name_given_fn }}:</span>{{ application.name_given_fn
                        }}</div>
                    <div><span class="font-bold text-lg pr-3">{{ lang.payment_amount }}:</span>{{ payment.amount }}</div>
                    <div><span class="font-bold text-lg pr-3">{{ lang.email }}:</span>{{ payment.email }}</div>
                </div>
            </div>
            <form method="post" action="https://epay.mpu.edu.mo/bocpaytest/ipm/cashier">
                <div v-for="(value, field) in payment" hidden>
                    <input :name="field" :value="payment[field]" /><br>
                </div>
                <div class="text-center">
                    <a :href="route('recruitment.admin.apply', { code: vacancy.code, page: 6 })"
                        class="bg-amber-500 text-white p-2 rounded-sm m-5">{{ lang.back_no_save }}</a>
                    <a-button type="primary" html-type="submit" class="mt-5">{{ lang.pay_confirm }}</a-button>
                </div>
            </form>
        </div>

    </MemberLayout>
</template>

<script>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import CardBox from '@/Components/CardBox.vue';
import { CaretRightOutlined } from '@ant-design/icons-vue';
import recLang from '/lang/recruitment_admin.json';

export default {
    components: {
        MemberLayout,
        CaretRightOutlined,
        CardBox
    },
    props: ['vacancy', 'application', 'payment'],
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
        let urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('page')) {
            this.page.current = parseInt(urlParams.get('page'))
            this.page.previours = this.page.current - 1
            this.page.next = this.page.current + 1
        }else{
            this.page.current=this.lang.steps.length
            this.page.previours=this.page.current
            this.page.next=this.page.current
        }
    },
    methods: {
        confirmPayment() {
            let formData = new FormData();
            Object.entries(this.payment).forEach(([key, value]) => {
                formData.append(key, value)
            })
            axios.post('https://epay.mpu.edu.mo/bocpaytest/ipm/cashier', formData)

        }
    },
};

</script>
<style>
label.ant-checkbox-wrapper {
    margin-left: 8px;
}
</style>
