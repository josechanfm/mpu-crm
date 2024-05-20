<template>
    <RecruitmentLayout title="Vacancies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $t('rec.vacancies')}}
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    Test
                    <a-row>
                        <a-col :span="12">
                            <h1>Notify</h1>
                            <form method="post" action="/api/boc/notify">
                                <div>
                                    <label>Merchant Order No: </label>
                                    <input type="text" name="merchantOrderNo" value="MPUCRM-1-1122334455-000-01"/>
                                </div>
                                <div>
                                    <label>Log No: </label>
                                    <input type="text" name="LogNo" value="log-1236456"/>
                                </div>
                                <div>
                                    <label>Pay Type: </label>
                                    <input type="text" name="payType" value="BOC"/>
                                </div>
                                <div>
                                    <label>Payment Date: </label>
                                    <input type="text" name="paymentDate" value="2024-12-30"/>
                                </div>
                                <div>
                                    <label>Payment Time: </label>
                                    <input type="text" name="paymentTime" value="13:30:59"/>
                                </div>
                                <div>
                                    <label>Status: </label>
                                    <input type="text" name="status" value="SUCCESS"/>
                                </div>
                                <div>
                                    <input type="submit" value="Submit"/>
                                </div>
                            </form>
                            <hr>
                            <h1>Result: SUCCESS | FAIL</h1>
                            <form method="post" action="/api/boc/result">
                                <div>
                                    <label>Merchant Order No: </label>
                                    <input type="text" name="merchantOrderNo" value="MPUCRM1-1122334455-00001"/>
                                </div>
                                <div>
                                    <label>Status: </label> &nbsp;
                                    Success <input type="radio" name="responseStatus" value="SUCESS" checked/>
                                    Fail <input type="radio" name="responseStatus" value="FAIL"/>
                                </div>
                                <div>
                                    <input type="submit" value="Submit"/>
                                </div>
                            </form>
                        </a-col>
                        <a-col :span="12">
                            <ol>
                                <li v-for="pay in payments">{{ 'MPUCRM'+pay.merc_order_no+'99' }}</li>
                            </ol>
                        </a-col>
                    </a-row>

                </div>
            </div>
        </div>
    </RecruitmentLayout>
</template>

<script>
import RecruitmentLayout from '@/Layouts/RecruitmentLayout.vue';

export default {
    components: {
        RecruitmentLayout,
    },
    props: ["applications","payments"],
    data() {
        return {
        }
    },
    created() {

    },
    mounted() {
    },
    methods: {
    },
};

</script>
