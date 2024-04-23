<template>
    <RecruitmentLayout title="Vacancies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Vacancies333
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <template v-if="$page.props.env=='local'">
                    <a-button @click="sampleData">Sample Data</a-button>
                </template>
                <CardBox :title="$t('rec.professional')">
                    <template #content>
                        <table class="myTable" width="100%">
                            <tr>
                                <th colspan="2"> {{ $t('rec.prof_organization') }}</th>
                                <th rowspan="2">{{ $t('rec.prof_qualification') }}</th>
                                <th rowspan="2">{{ $t('rec.prof_area') }}</th>
                                <th colspan="2">{{ $t('rec.prof_date') }}</th>
                                <th rowspan="2">{{ $t('rec.operation') }}</th>
                            </tr>
                            <tr>
                                <th>{{ $t('rec.prof_organization_name') }}</th>
                                <th>{{ $t('rec.prof_region') }}</th>
                                <th>{{ $t('rec.prof_date_valid') }}</th>
                                <th>{{ $t('rec.prof_date_expired') }}</th>
                            </tr>
                            <template v-for="professional in application.professionals">
                                <tr>
                                    <td>{{ professional.organization_name }}</td>
                                    <td>{{ professional.region }}</td>
                                    <td>{{ professional.qualification }}</td>
                                    <td>{{ professional.area }}</td>
                                    <td>{{ professional.date_valid }}</td>
                                    <td>{{ professional.date_expired }}</td>
                                    <td>a</td>
                                </tr>
                            </template>

                        </table>
                        <a-divider/>
                        <a-form :model="professional" layout="vertical" :rules="rules" @finish="onFinish">
                            <a-row :gutter="10">
                                <a-col :span="16">
                                    <a-form-item :label="$t('rec.prof_organization_name')" name="organization_name">
                                        <a-input v-model:value="professional.organization_name"/>
                                    </a-form-item>
                                </a-col>
                                <a-col :span="8">
                                    <a-form-item :label="$t('rec.prof_region')" name="region">
                                        <a-input v-model:value="professional.region"/>
                                    </a-form-item>
                                </a-col>
                            </a-row>
                            <a-row :gutter="10">
                                <a-col :span="12">
                                    <a-form-item :label="$t('rec.prof_qualification')" name="qualification">
                                        <a-input v-model:value="professional.qualification"/>
                                    </a-form-item>
                                </a-col>
                                <a-col :span="12">
                                    <a-form-item :label="$t('rec.prof_area')">
                                        <a-input v-model:value="professional.area"/>
                                    </a-form-item>
                                </a-col>
                            </a-row>
                            <a-row :gutter="10">
                                <a-col :span="12">
                                    <a-form-item :label="$t('rec.prof_date_valid')" name="date_valid">
                                        <a-date-picker v-model:value="professional.date_valid" :format="dateFormat" :valueFormat="dateFormat"/>
                                    </a-form-item>
                                </a-col>
                                <a-col :span="12">
                                    <a-form-item :label="$t('rec.prof_date_expired')">
                                        <a-date-picker v-model:value="professional.date_expired" :format="dateFormat" :valueFormat="dateFormat"/>
                                    </a-form-item>
                                </a-col>
                            </a-row>
                            <a-form-item :wrapper-col="{ span: 24, offset: 11,}">
                                <a-button type="primary" html-type="submit">{{ $t('rec.add_item') }}</a-button>
                            </a-form-item>
                        </a-form>
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
                <div class="text-center pt-5">
                    <a-button :href="route('application.apply',{code:vacancy.code,page:page.previours})" class="bg-amber-500 text-white p-3 rounded-lg">{{ $t('rec.back_no_save') }}</a-button>
                    <a-button type="primary" @click="saveToNext">{{ $t('rec.save_next') }}</a-button>
                </div>
                
                


            </div>
        </div>
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
    props: ['vacancy','application'],
    data() {
        return {
            page:{},
            professional:{},
            dateFormat:'YYYY-MM-DD',
            rules:{
                organization_name:{required:true},
                region:{required:true},
                qualification:{required:true},
                date_valid:{required:true},
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
        if(urlParams.has('page')){
            this.page.current=parseInt(urlParams.get('page'))
            this.page.previours=this.page.current-1
            this.page.next=this.page.current + 1
        }
    },
    methods: {
        sampleData(){
            this.professional.organization_name='Organization'
            this.professional.region='Macao'
            this.professional.qualification='MPM'
            this.professional.area='PM'
            this.professional.date_valid='2020-01-01'
            this.professional.date_expired='2022-01-01'
        },
        saveToNext(){
            this.onFinish();
        },
        saveToNext(){
            console.log(this.currentPage);
            this.$inertia.post(route('application.save'), {to_page:this.page.next,application:this.application},{
                    onSuccess:(page)=>{
                        console.log(page.data)
                    },
                    onError:(err)=>{
                        console.log(err)
                    }
                });
        },
        onFinish(){
            this.application.professionals.push({...this.professional})
            this.professional={};
            console.log(this.application);
        }
    },
};

</script>
<style>
label.ant-checkbox-wrapper{
    margin-left:8px;
}
.myTable, .myTable tr th, .myTable tr td{
    border: 1px solid gray;
    padding:5px
}
</style>
