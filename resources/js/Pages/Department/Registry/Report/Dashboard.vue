<template>
    <DepartmentLayout title="統計及報告" :breadcrumb="breadcrumb">
        <table class="border-collapse border border-slate-400 bg-white w-full max-w-96">
            <tr>
                <th class="border border-slate-300">月份</th>
                <th class="border border-slate-300"><3日</th>
                <th class="border border-slate-300">3-5日</th>
                <th class="border border-slate-300">>5日</th>
            </tr>
            <template v-for="(stat,month) in enquiryQuestionStat">
                <tr>
                    <td class="border border-slate-300 text-center">{{ month }}月 </td>
                    <td class="border border-slate-300 text-center">
                        {{ stat['<3'].group_count??0 }}
                    </td>
                    <td class="border border-slate-300 text-center">
                        {{ stat['3-5'].group_count??0 }}
                    </td>
                    <td class="border border-slate-300 text-center">
                        {{ stat['>5'].group_count??0 }}
                    </td>
                </tr>
            </template>
        </table>
    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from '@/Layouts/DepartmentLayout.vue';

export default {
    components: {
        DepartmentLayout,
    },
    props: ['enquiryQuestionStat'],
    data() {
        return {
            breadcrumb:[
                {label:"招生注冊處" ,url:route('registry.dashboard')},
                {label:"統計及報告" ,url:null},
            ],

        }
    },
    methods: {
        getStatNumber(group, month){

        }
    },
}
</script>