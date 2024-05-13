<template>
    <RecruitmentLayout title="Vacancies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Vacancies 00000
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <CardBox title="title">
                    <template #content>
                        <table width="100%">
                            <tr>
                                <td class="p-2">
                                    <div>{{$t('rec.unit')}}: {{ vacancy.apply_in }}</div>
                                    <div>{{$t('rec.code')}}: {{vacancy.code}}</div>
                                    <div>{{$t('rec.title')}}: {{ vacancy['title_'+$page.props.lang] }}</div>
                                </td>
                                <td class="p-2">
                                    <div>{{$t('rec.obtain_info')}}</div>
                                    <div>{{$t('rec.obtain_info_web')}}</div>
                                    <div>{{$t('rec.obtain_info_new')}}</div>
                                    <div>{{$t('rec.obtain_info_oth')}}</div>
                                </td>
                            </tr>
                        </table>
                        <table class="mt-5" width="100%">
                            <tr>
                                <th colspan="4" style="text-align: left;">
                                    {{ $t('rec.personal') }}
                                    <a-button v-if="!application.submitted" :href="route('recruitment.academic.form',{'code':vacancy.code,'page':1})" class="ant-btn ant-btn-primary float-right ml-5">Edit</a-button>
                                    <div class="float-right">{{ $t('rec.part_a') }}</div>
                                </th>
                            </tr>
                            <tr>
                                <th width="100px">{{ $t('rec.name_zh') }}</th>
                                <td width="50%">{{ application.name_zh }}</td>
                                <th width="100px">{{ $t('rec.gender') }}</th>
                                <td width="50%">{{ application.gender }}</td>
                            </tr>
                            <tr>
                                <th width="100px">{{ $t('rec.last_name_fn') }}</th>
                                <td colspan="3">{{ application.last_name_fn }}</td>
                            </tr>
                            <tr>
                                <th width="100px">{{ $t('rec.first_name_fn') }}</th>
                                <td colspan="3">{{ application.first_name_fn }}</td>
                            </tr>
                            <tr>
                                <th width="100px">{{ $t('rec.pob') }}</th>
                                <td>{{ application.pob }}</td>
                                <th width="100px">{{ $t('rec.dob') }}</th>
                                <td>{{ application.dob}}</td>
                            </tr>
                            <tr>
                                <th width="100px">{{ $t('rec.id_type') }}</th>
                                <td>
                                    <template v-if="application.id_type=='OTH'">
                                        {{ application.id_type_name }}
                                    </template>
                                    <template v-else>
                                        {{ application.id_type_type }}
                                    </template>
                                </td>
                                <th width="100px">{{ $t('rec.id_num') }}</th>
                                <td>{{ application.id_num }}</td>
                            </tr>
                            <tr>
                                <th width="100px">{{ $t('rec.nationality') }}</th>
                                <td>{{ application.nationality }}</td>
                                <th width="100px"></th>
                                <td>4</td>
                            </tr>
                            <tr>
                                <th width="100px">{{ $t('rec.address') }}</th>
                                <td colspan="3">{{ application.address }}</td>
                            </tr>
                            <tr>
                                <th width="100px">{{ $t('rec.phone') }}</th>
                                <td>{{ application.phone }}</td>
                                <th width="100px">{{ $t('rec.email') }}</th>
                                <td>{{ application.email }}</td>
                            </tr>
                            <tr>
                                <th width="100px">{{ $t('rec.name_zh') }}</th>
                                <td>b</td>
                                <th width="100px">{{ $t('rec.gender') }}</th>
                                <td>4</td>
                            </tr>
                        </table>

                        <table class="mt-5" width="100%">
                            <tr>
                                <th colspan="8" style="text-align: left;">
                                    {{ $t('rec.education') }}
                                    <a-button v-if="!application.submitted" :href="route('recruitment.academic.form',{'code':vacancy.code,'page':2})" class="ant-btn ant-btn-primary float-right ml-5">Edit</a-button>
                                    <div class="float-right">{{ $t('rec.part_b') }}</div>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="2">1</th>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                            </tr>
                            <tr>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                                <td>1</td>
                            </tr>
                        </table>
                        <table class="mt-5" width="100%">
                            <tr>
                                <th colspan="6" style="text-align: left;">
                                    {{ $t('rec.professional') }}
                                    <a-button v-if="!application.submitted" :href="route('recruitment.academic.form',{'code':vacancy.code,'page':3})" class="ant-btn ant-btn-primary float-right ml-5">Edit</a-button>
                                    <div class="float-right">{{ $t('rec.part_c') }}</div>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="2">1</th>
                                <th>1</th>
                                <th>1</th>
                                <th colspan="2">1</th>
                            </tr>
                            <tr>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>2</td>
                                <td>2</td>
                                <td>2</td>
                                <td>2</td>
                                <td>2</td>                                
                            </tr>
                        </table>

                        <table class="mt-5" width="100%">
                            <tr>
                                <th colspan="7" style="text-align: left;">
                                    {{ $t('rec.experience') }}
                                    <a-button v-if="!application.submitted" :href="route('recruitment.academic.form',{'code':vacancy.code,'page':4})" class="ant-btn ant-btn-primary float-right ml-5">Edit</a-button>
                                    <div class="float-right">{{ $t('rec.part_d') }}</div>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="2">1</th>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                                <th colspan="2">1</th>
                            </tr>
                            <tr>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                                <th>1</th>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>2</td>
                                <td>2</td>
                                <td>2</td>
                                <td>2</td>
                                <td>2</td>
                                <td>2</td>                                
                            </tr>
                        </table>

                        <table class="mt-5" width="100%">
                            <tr>
                                <th colspan="2" style="text-align: left;">
                                    {{ $t('rec.upload') }}
                                    <a-button v-if="!application.submitted" :href="route('recruitment.academic.form',{'code':vacancy.code,'page':5})" class="ant-btn ant-btn-primary float-right ml-5">Edit</a-button>
                                </th>
                            </tr>
                            <tr>
                                <th style="text-align: left;">a</th>
                                <td>b</td>
                            </tr>
                        </table>
                        <a-form :model="application" @finish="onFinish">
                            <a-form-item :wrapper-col="{ span: 24, offset: 11,}">
                                <template  v-if="application.submitted">
                                    <a-button  type="primary" danger class="mt-5">Pay</a-button>
                                </template>
                                <template v-else>
                                    <a-button type="primary" html-type="submit" class="mt-5">Submit</a-button>
                                </template>
                            </a-form-item>
                        </a-form>
                    </template>
                </CardBox>
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

    },
    mounted() {

    },
    methods: {
        sampleData(){
            this.application.obtain_info=['WEB','NEW'],
            this.application.obtain_info_new='Macao Daily',
            this.application.obtain_info_oth='Inernet',
            this.application.name_zh='陳大文',
            this.application.first_name_fn='Tai Man',
            this.application.last_name_fn='Chan',
            this.application.gender='M',
            this.application.pob='OTH',
            this.application.pob_oth='Germany',
            this.application.dob='1970-07-18',
            this.application.id_type='OTH',
            this.application.id_type_name='Germany',
            this.application.id_num='123456789',
            this.application.nationality='German',
            this.application.phone='66778899',
            this.application.email='chantaiman@example.com',
            this.application.address='Somewhere near by..'
        },
        onFinish(){
            console.log('onfinish')
            this.$inertia.post(route('recruitment.academic.submit'), this.application,{
                    onSuccess:(page)=>{
                        console.log(page.data)
                    },
                    onError:(err)=>{
                        console.log(err)
                    }
                });
        }
    },
};

</script>
<style>
label.ant-checkbox-wrapper{
    margin-left:8px;
}
table, table tr th, table tr td{
    border: 1px solid gray;
    padding:5px
}

</style>
