<template>
    <RecruitmentLayout title="Vacancies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Payment
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form method="post" action="https://epay.mpu.edu.mo/bocpaytest/ipm/cashier">
                    <div v-for="(value,field) in payment" hidden>
                        <input :name="field" :value="payment[field]"/><br>
                    </div>
                    <input type="submit"/>
                </form>
                <a-button :href="route('recruitment.application.form', { code: vacancy.code, page:6 })" class="bg-amber-500 text-white p-3 rounded-lg m-5">{{ lang.back_no_save }}</a-button>
                <a-button @click="confirmPayment">Confirm Payment</a-button>
            </div>
        </div>
    </RecruitmentLayout>
</template>

<script>
import RecruitmentLayout from '@/Layouts/RecruitmentLayout.vue';
import CardBox from '@/Components/CardBox.vue';
import { CaretRightOutlined } from '@ant-design/icons-vue';
import recLang  from '/lang/recruitment.json';

export default {
    components: {
        RecruitmentLayout,
        CaretRightOutlined,
        CardBox
    },
    props: ['vacancy','application','payment'],
    data() {
        return {
            page: {},
            rules:{
                name_zh:{required:true},
                first_name_fn:{required:true},
                last_name_fn:{required:true},
                gender:{required:true},
                pob:{required:true},
                pob_oth:{required:true},
                dob:{required:true},
                id_type:{required:true},
                id_type_name:{required:true},
                id_num:{required:true},
                nationality:{required:true},
                phone:{required:true},
                email:{required:true},
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
        confirmPayment(){
            console.log(this.payment);
            let formData=new FormData();
            Object.entries(this.payment).forEach(([key,value])=>{
                formData.append(key,value)
            })
            axios.post('https://epay.mpu.edu.mo/bocpaytest/ipm/cashier',formData)
            console.log(formData)

        }
    },
};

</script>
<style>
label.ant-checkbox-wrapper{
    margin-left:8px;
}
</style>
