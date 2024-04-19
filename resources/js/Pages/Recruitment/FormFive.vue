<template>
    <RecruitmentLayout title="Vacancies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Vacancies5 ....
            </h2>
        </template>
        {{ application }}
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="text-lg text-white bg-emerald-500 rounded p-3">{{ $t('login_success') }}</div>
                </div>
                <template v-if="$page.props.env=='local'">
                    <a-button @click="sampleData">Sample Data</a-button>
                </template>
                <a-form :model="application" layout="vertical" :rules="rules" @finish="onFinish">
                <CardBox :title="$t('rec.position_info')">
                    <template #content>
                        form 2
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
                <a-form-item :wrapper-col="{ span: 24, offset: 11,}">
                    <a-button type="primary" html-type="submit">Submit</a-button>
                </a-form-item>
                </a-form>
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
            form:{
                
            },
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
            this.$inertia.post(route('application.save'), this.application,{
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
</style>
