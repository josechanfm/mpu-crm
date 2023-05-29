<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <a-form :model="inquiry" name="inquiry" layout="vertical">
                    <a-form-item label="擬入讀本校之學生現時所持有之證件 Type of personal Identification document held by the candidate">
                        <a-radio-group v-model:value="inquiry.origin" >
                            <a-radio value="MO" :style="radioStyle">澳門居民身份證 MACAU ID</a-radio>
                            <a-radio value="CN" :style="radioStyle">中華人民共和國居民身份證 CHINA ID</a-radio>
                            <a-radio value="HK" :style="radioStyle">香港居民身份證 HONG KONG ID'</a-radio>
                            <a-radio value="TW" :style="radioStyle">台灣居民身份證 TAIWAN ID</a-radio>
                            <a-radio value="FRO" :style="radioStyle">外國護照 (非持有上述證件的外國居民) PASSPORT</a-radio>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item label="入讀課程類別 Apply programme">
                        <a-radio-group v-model:value="inquiry.degree" >
                            <a-radio value="B" :style="radioStyle">學士學位 (本科) Bachelor</a-radio>
                            <a-radio value="M" :style="radioStyle">碩士學位 Master</a-radio>
                            <a-radio value="D" :style="radioStyle">博士學位 Doctoral</a-radio>
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item label="入學途徑 Admission route" v-if="inquiry.degree=='B'">
                        <a-radio-group v-model:value="inquiry.admission" >
                            <template v-if="inquiry.origin=='MO'">
                                <a-radio value="EXAM" :style="radioStyle">入學考試 Admission exams</a-radio>
                                <a-radio value="RECOMMEND" :style="radioStyle">澳門中學校長推薦新生入學計劃 Principals’ recommendation</a-radio>
                                <a-radio value="TELENT" :style="radioStyle">專才入學計劃 Local talents and professionals</a-radio>
                                <a-radio value="DIRECT" :style="radioStyle">直接入學 Direct admission</a-radio>
                                <a-radio value="OTHER" :style="radioStyle">其他 Others (現就讀於高等院校學士課程之學生適用 Applicable to students who are currently studying bachelor programs in higher education institutions)</a-radio>
                            </template>                                
                            <template v-else="inquiry.origin=='CN'">
                                <a-radio value="GAOKAO" :style="radioStyle">高考生 Mainland China GAOKAO students</a-radio>
                                <a-radio value="DIRECT" :style="radioStyle">直接入學 Direct admission (非高考生持國際課程或公開考試成績的內地籍高中應屆畢業生 Non-GAOKAO student and Fresh graduates from high schools who hold international curriculum or public exam result)</a-radio>
                            </template>
                            <template v-else>
                                <a-radio value="EXAM" :style="radioStyle">入學考試 Admission exams',conditions</a-radio>
                                <a-radio value="DIRECT" :style="radioStyle">直接入學 Direct admission',conditions</a-radio>
                            </template>"
                        </a-radio-group>
                        <br>
                    </a-form-item>
                    <a-form-item label="簡介 Profiles" v-if="inquiry.degree">
                        <a-radio-group v-model:value="inquiry.profile" >
                            <a-radio value="STD" :style="radioStyle">學生 Student</a-radio>
                            <a-radio value="PAR" :style="radioStyle">家長 Parent</a-radio>
                            <a-radio value="TEA" :style="radioStyle">老師 Teacher</a-radio>
                            <a-radio value="EDU" :style="radioStyle">教育機構/顧問 Education centre/counsellor</a-radio>
                            <a-radio value="OTH" :style="radioStyle">其他 Other</a-radio>
                            <a-input v-if="inquiry.profile=== 'OTH'" style="width: 300px; margin-left: 10px" />
                        </a-radio-group>
                    </a-form-item>
                    <a-form-item label="是否已報讀澳理大學位課程? Have you submitted an application for admission in current year?" v-if="inquiry.profile">
                        <a-radio-group v-model:value="inquiry.apply" >
                            <a-radio value="NO" :style="radioStyle">否   No</a-radio>
                            <a-radio value="YES" :style="radioStyle">是 Yes</a-radio>
                            <a-input v-if="inquiry.apply=== 'YES'" style="width: 100px; margin-left: 10px" />
                        </a-radio-group>
                    </a-form-item>
                    <template v-if="inquiry.apply">
                        <a-row :gutter="24">
                            <a-col :span="12">
                                <a-form-item label="姓 Surname">
                                    <a-input v-model:value="inquiry.surname" />
                                </a-form-item>
                            </a-col>
                            <a-col :span="12">
                                <a-form-item label="名 Given Name">
                                            <a-input v-model:value="inquiry.givenname" />
                                        </a-form-item>
                            </a-col>
                        </a-row>     
                        <a-form-item label="電郵 Email">
                            <a-input v-model:value="inquiry.email" :rules="[{ type: 'email' }]"/>
                        </a-form-item>
                        <a-label>聯絡電話 phone number</a-label>
                        <a-row :gutter="24">
                            <a-col :span="12">
                                <a-form-item label="區號 Area code">
                                    <a-input v-model:value="inquiry.surname" />
                                </a-form-item>
                            </a-col>
                            <a-col :span="12">
                                <a-form-item label="電話 Phone number">
                                    <a-input v-model:value="inquiry.givenname" />
                                </a-form-item>
                            </a-col>
                        </a-row>     
                    </template>

                    <a-form-item label="主旨 Subject">
                        <a-radio-group v-model:value="inquiry.degree" >
                            <a-radio value="ADM" :style="radioStyle">入學要求 Admission Requirements</a-radio>
                            <a-radio value="PRO" :style="radioStyle">課程資訊 Programme Information</a-radio>
                            <a-radio value="APP" :style="radioStyle">報名資訊 Application Procedures</a-radio>
                            <a-radio value="FEE" :style="radioStyle">學費及奬學金 Fees and Scholarships</a-radio>
                            <a-radio value="RES" :style="radioStyle">錄取資訊 Admission Result</a-radio>
                            <a-radio value="UPD" :style="radioStyle">更新報名資訊 Updating Information on Application Form</a-radio>
                            <a-radio value="PAY" :style="radioStyle">繳費事宜 Payment Issue</a-radio>
                            <a-radio value="REG" :style="radioStyle">入學登記手續、體檢 Student registration and physical examination</a-radio>
                            <a-radio value="OTH" :style="radioStyle">其他 others</a-radio>
                        </a-radio-group>
                    </a-form-item>

                    











                </a-form>
            </div>
        </div>
    </div>
</template>

<script>


export default {
    components: {
    },
    props: ['faqs'],
    data() {
        return {
            inquiry:{
                q1:{
                    id:1,
                    title:'擬入讀本校之學生現時所持有之證件 Type of personal Identification document held by the candidate',
                    hints:'',
                    options:[
                        {value:'MO',label:'澳門居民身份證 MACAU ID'},
                        {value:'CN',label:'中華人民共和國居民身份證 CHINA ID'},
                        {value:'HK',label:'香港居民身份證 HONG KONG ID'},
                        {value:'TW',label:'台灣居民身份證 TAIWAN ID'},
                        {value:'FR',label:'外國護照 (非持有上述證件的外國居民) PASSPORT'},
                    ]
                },
                q2:{
                    id:1,
                    title:'入讀課程類別 Apply programme',
                    hints:'',
                    options:[
                        {value:'B',label:'學士學位 (本科) Bachelor'},
                        {value:'M',label:'碩士學位 Master'},
                        {value:'D',label:'博士學位 Doctoral'},
                    ]
                },
                q3:{
                    id:1,
                    title:'入學途徑 Admission route',
                    hints:'',
                    remark:'',
                    options:[
                        {value:'exam',label:'入學考試 Admission exams',conditions:{q1:['MO'],q2:['B']}},
                        {value:'recommend',label:'澳門中學校長推薦新生入學計劃 Principals’ recommendation',conditions:{q1:['MO'],q2:['B']}},
                        {value:'telent',label:'專才入學計劃 Local talents and professionals',conditions:{q1:['MO'],q2:['B']}},
                        {value:'direct',label:'直接入學 Direct admission',conditions:{q1:['MO'],q2:['B']}},
                        {value:'other',label:'其他 Others (現就讀於高等院校學士課程之學生適用 Applicable to students who are currently studying bachelor programs in higher education institutions)',conditions:{q1:['MO'],q2:['B']}},
                        
                        {value:'GK',label:'高考生 Mainland China GAOKAO students',conditions:{q1:['CN'],q2:['B']}},
                        {value:'cn',label:'直接入學 Direct admission (非高考生持國際課程或公開考試成績的內地籍高中應屆畢業生 Non-GAOKAO student and Fresh graduates from high schools who hold international curriculum or public exam result)',conditions:{q1:['CN'],q2:['B']}},
                        
                        {value:'mo',label:'入學考試 Admission exams',conditions:{q1:['HK','TW','FR'],q2:['B']}},
                        {value:'cn',label:'直接入學 Direct admission',conditions:{q1:['CN'],q2:['B']}},
                    ]
                },
                q4:{
                    id:1,
                    title:'q3',
                    hints:'',
                    options:[
                        {value:'mo',label:'m'},
                        {value:'cn',label:'c'},
                        {value:'hk',label:'h'},
                        {value:'tw',label:'t'},
                        {value:'fr',label:'f'},
                    ]
                },
            },
            radioStyle:{
                display: 'flex',
                height: '30px',
                lineHeight: '30px',
            },
        }
    },
    methods: {

    },
}
</script>