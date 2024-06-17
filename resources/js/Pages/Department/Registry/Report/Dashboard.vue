<template>
    <DepartmentLayout title="統計及報告" :breadcrumb="breadcrumb">


        <div class="flex-auto pb-3 text-right">
            <div class="mb-5">
                <a-form ref="exportlRef" action="/registry/export_enquiries">
                    <a-range-picker v-model:value="exportCriteria.period" :format="dateFormat" :valueFormat="dateFormat" class="m-5"/>
                    <a-button type="primary" html-type="submit" @click="onExportEnquiries" class="m-5">滙出查詢</a-button>
                    <a-button type="primary" html-type="submit" @click="onExportQuestions">滙出問題</a-button>
                </a-form>
            </div>
        </div>
        <table class="border-collapse border border-slate-400 bg-white w-full max-w-96">
            <tr>
                <th class="border border-slate-300">年/ 月份</th>
                <th class="border border-slate-300">&lt;3日</th>
                <th class="border border-slate-300">3-5日</th>
                <th class="border border-slate-300">>5日</th>
            </tr>
            <template v-for="(stat,yearMonth) in enquiryQuestionStat">
                <tr>
                    <td class="border border-slate-300 text-center">{{ yearMonth }}月 </td>
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
import dayjs from 'dayjs';

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
            dateFormat: "YYYY-MM-DD",
            exportCriteria:{
                period:[dayjs().subtract(1,'month').format(this.dateFormat),dayjs().format(this.dateFormat)],
                is_valid:true
            },

        }
    },
    methods: {
        onExportEnquiries(event){
            this.exportCriteria.period[0]=dayjs(this.exportCriteria.period[0]).format(this.dateFormat)+' 00:00:00'
            this.exportCriteria.period[1]=dayjs(this.exportCriteria.period[1]).format(this.dateFormat)+' 23:59:00'
            if(this.exportCriteria.period && this.exportCriteria.period.length==2){
                const params='period[]='+this.exportCriteria.period[0]+'&period[]='+this.exportCriteria.period[1];
                window.open(route('registry.report.exportEnquiries',params));
                //window.open('./report/export_enquiries?'+params);
            }
        },
        onExportQuestions(event){
            this.exportCriteria.period[0]=dayjs(this.exportCriteria.period[0]).format(this.dateFormat)+' 00:00:00'
            this.exportCriteria.period[1]=dayjs(this.exportCriteria.period[1]).format(this.dateFormat)+' 23:59:00'
            if(this.exportCriteria.period && this.exportCriteria.period.length==2){
                const params='period[]='+this.exportCriteria.period[0]+'&period[]='+this.exportCriteria.period[1];
                window.open(route('registry.report.exportQuestions',params));
                //window.open('./report/export_questions?'+params);
            }
        }

    },
}
</script>