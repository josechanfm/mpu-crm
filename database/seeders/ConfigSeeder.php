<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

          DB::table('configs')->insert([
            'key'=>'enquiry_form',
            'label'=>'privacy',
            'value'=>'{
              "question_zh": "本人已閱悉並同意以上內容：",
              "question_en": "I have read and agree to the above stated items.",
              "question_pt": "Li e concordo com o conteúdo acima referido.",
              "short": "同意"
            }'
          ]);
          DB::table('configs')->insert([
            'key'=>'enquiry_form',
            'label'=>'origin',
            'value'=>'{
                "short":"持有證件",
                "question_zh":"報考讀本校之學生現時所持有之證件",
                "question_en":"Type of personal Identification document held by the candidate",
                "question_pt":"",
                "options":[
                    {"value":"MO",
                      "label_zh":"澳門居民身份證",
                      "label_en":"Macao ID",
                      "label_pt":"BIR de Macau",
                      "url": "https://www.mpu.edu.mo/admission_local/zh/index.php"
                    },
                    {"value":"CN",
                      "label_zh":"中華人民共和國居民身份證",
                      "label_en":"China ID",
                      "label_pt":"BIR da República Popular da China",
                      "url": "https://www.mpu.edu.mo/admission_mainland/zh/index.php"
                    },
                    {"value":"HK",
                      "label_zh":"香港居民身份證",
                      "label_en":"Hong Kong ID",
                      "label_pt":"BIR de Hong Kong",
                      "url": "https://www.mpu.edu.mo/admission_overseas/zh/index.php"
                    },
                    {"value":"TW",
                      "label_zh":"台灣居民身份證",
                      "label_en":"Taiwan ID",
                      "label_pt":"BIR de Taiwan",
                      "url": "https://www.mpu.edu.mo/admission_overseas/zh/index.php"
                    },
                    {"value":"FR",
                      "label_zh":"外國護照 (非持有上述證件的外國居民)",
                      "label_en":"Passport (for candidates holding none of the ID documents listed above)",
                      "label_pt":"Passaporte estrangeiro (Residente estrangeiro que não seja titular dos documentos acima referidos)",
                      "url": "https://www.mpu.edu.mo/admission_overseas/zh/index.php"
                    }
                ]
            }'
        ]);

        DB::table('configs')->insert([
            'key'=>'enquiry_form',
            'label'=>'degree',
            'value'=>'{
                "question_zh":"入讀課程類別",
                "question_en":"Apply programme",
                "question_pt":"Tipo do curso inscrito",
                "short":"入讀課程",
                "options":[
                    {"value":"B",
                      "label_zh":"學士學位",
                      "label_en":"Bachelor\'s Degree",
                      "label_pt":"Licenciatura"
                    },
                    {"value":"M",
                      "label_zh":"碩士學位",
                      "label_en":"Master\'s Degree",
                      "label_pt":"Mestrado"
                    },
                    {"value":"D",
                      "label_zh":"博士學位",
                      "label_en":"Doctoral\'s Degree",
                      "label_pt":"Doutoramento"
                    }
                ]
            }'
        ]);
        DB::table('configs')->insert([
            'key'=>'enquiry_form',
            'label'=>'admission',
            'value'=>'{
                "question_zh":"入學途徑",
                "question_en":"Admission route",
                "question_pt":"Condição de acesso",
                "short":"入學途徑",
                "options":[
                    {"value":"EXAM",
                      "label_zh":"入學考試",
                      "label_en":"Admission exams",
                      "label_pt":"Exame de Admissão"
                    },
                    {"value":"RECOMMEND",
                      "label_zh":"澳門中學校長推薦新生入學計劃",
                      "label_en":"Principals’ recommendation",
                      "label_pt":"Admissão dos Alunos Recomendados por Director da Escola do Ensino Secundário"
                    },
                    {"value":"TELENT",
                      "label_zh":"專才入學計劃",
                      "label_en":"Local talents and professionals",
                      "label_pt":"Admissão dos Alunos com Talentos Especiais"
                    },
                    {"value":"DIRECT",
                      "label_zh":"直接入學",
                      "label_en":"Direct admission",
                      "label_pt":"Admissão Directa"
                    },
                    {"value":"OTHER",
                      "label_zh":"其他 (現就讀於高等院校學士課程之學生適用)",
                      "label_en":"Others ( Applicable to students who are currently studying bachelor programs in higher education institutions)",
                      "label_pt":"Outros (Para estudante que esteja a frequentar curso de licenciatura em instituições de ensino superior)"
                    },
                    {"value":"GAOKAO",
                      "label_zh":"高考生",
                      "label_en":"Mainland China GAOKAO students",
                      "label_pt":"Estudante de Gaokao do Interior da China"
                    },
                    {"value":"DIRECT",
                      "label_zh":"直接入學 (非高考生持國際課程或公開考試成績的內地籍高中應屆畢業生)",
                      "label_en":"Direct admission (Non-GAOKAO student and Fresh graduates from high schools who hold international curriculum or public exam result)",
                      "label_pt":"Admissão Directa (Estudante não Gaokao e recém-graduado do ensino secundário complementar do Interior da China, que possui resultados do currículo internacional ou do exame público )"
                    },
                    {"value":"EXAM",
                      "label_zh":"入學考試",
                      "label_en":"Admission exams",
                      "label_pt":"Exame de Admissão"
                    },
                    {"value":"DIRECT",
                      "label_zh":"直接入學",
                      "label_en":"Direct admission",
                      "label_pt":"Admissão Directa"
                    }
                ]
            }'
        ]);
        DB::table('configs')->insert([
            'key'=>'enquiry_form',
            'label'=>'profile',
            'value'=>'{
                "question_zh":"簡介",
                "question_en":"Profiles",
                "question_pt":"Perfil",
                "short":"簡介",
                "options":[
                    {"value":"STD",
                      "label_zh":"學生",
                      "label_en":"Student",
                      "label_pt":"Aluno"
                    },
                    {"value":"PAR",
                      "label_zh":"家長",
                      "label_en":"Parent",
                      "label_pt":"Pais"
                    },
                    {"value":"TEA",
                      "label_zh":"老師",
                      "label_en":"Teacher",
                      "label_pt":"Professor"
                    },
                    {"value":"EDU",
                      "label_zh":"教育機構/顧問",
                      "label_en":"Education centre/counsellor",
                      "label_pt":"Instituição / consultor de ensino"
                    },
                    {"value":"OTH",
                      "label_zh":"其他",
                      "label_en":"Other",
                      "label_pt":"Outros"
                    }
                ]
            }'
        ]);
        DB::table('configs')->insert([
            'key'=>'enquiry_form',
            'label'=>'apply',
            'value'=>'{
                "question_zh":"是否已報讀澳理大學位課程?",
                "question_en":"Have you submitted an application for admission in current year?",
                "question_pt":"Já se inscreveu no curso conferente de grau académico da UPM?",
                "short":"已報讀",
                "options":[
                    {"value":false,
                      "label_zh":"否",
                      "label_en":"No",
                      "label_pt":"Não"
                    },
                    {"value":true,
                      "label_zh":"是",
                      "label_en":"Yes",
                      "label_pt":"Sim"
                    }
                ],
                "other":{
                  "value":"OTH",
                  "label_zh":"請提供候選人編號",
                  "label_en":"Please provide your candidate number",
                  "label_pt":"Por favor forneça o número do candidato"
               }
            }'
        ]);
        DB::table('configs')->insert([
            'key'=>'enquiry_form',
            'label'=>'surname',
            'value'=>'{
                "question_zh":"姓",
                "question_en":"Surname",
                "question_pt":"Apelido",
                "short":"姓"
            }'
        ]);
        DB::table('configs')->insert([
            'key'=>'enquiry_form',
            'label'=>'givenname',
            'value'=>'{
                "question_zh":"名",
                "question_en":"Given Name",
                "question_pt":"Nome",
                "short":"名"
            }'
        ]);
        DB::table('configs')->insert([
            'key'=>'enquiry_form',
            'label'=>'email',
            'value'=>'{
                "question_zh":"電郵",
                "question_en":"Email",
                "question_pt":"Email",
                "short":"電郵"
            }'
        ]);
        DB::table('configs')->insert([
          'key'=>'enquiry_form',
          'label'=>'areacode',
          'value'=>'{
              "question_zh":"區號",
              "question_en":"Area code",
              "question_pt":"Código de área",
              "short":"區號"
          }'
        ]);
        DB::table('configs')->insert([
            'key'=>'enquiry_form',
            'label'=>'contact_number',
            'value'=>'{
                "question_zh": "聯絡電話",
                "question_en": "phone number",
                "question_pt": "Tel.",
                "short":"聯絡電話"
            }'
        ]);
        DB::table('configs')->insert([
            'key'=>'enquiry_form',
            'label'=>'phone',
            'value'=>'{
                "question_zh":"電話",
                "question_en":"Phone number",
                "question_pt":"Tel.",
                "short":"電話"
            }'
        ]);
        DB::table('configs')->insert([
            'key'=>'enquiry_form',
            'label'=>'subjects',
            'value'=>'{
                "question_zh":"查詢主題",
                "question_en":"Subject",
                "question_pt":"",
                "short":"主題",
                "options":[
                    {"value":"ADM",
                      "label_zh":"入學要求",
                      "label_en":"Admission Requirements",
                      "label_pt":"Requisitos de Admissão"
                    },
                    {"value":"PRO",
                      "label_zh":"課程資訊",
                      "label_en":"Programme Information",
                      "label_pt":"Informações de Cursos"
                    },
                    {"value":"APP",
                      "label_zh":"報名資訊",
                      "label_en":"Application Procedures",
                      "label_pt":"Informações de Inscrição"
                    },
                    {"value":"FEE",
                      "label_zh":"學費及奬學金",
                      "label_en":"Fees and Scholarships",
                      "label_pt":"Propinas e Bolsas de Estudo"
                    },
                    {"value":"RES",
                      "label_zh":"錄取資訊",
                      "label_en":"Admission Result",
                      "label_pt":"Informações de Admissão"
                    },
                    {"value":"UPD",
                      "label_zh":"更新報名資訊",
                      "label_en":"Updating Information on Application Form",
                      "label_pt":"Actualização das Informações de Inscrição"
                    },
                    {"value":"PAY",
                      "label_zh":"繳費事宜",
                      "label_en":"Payment Issue",
                      "label_pt":"Pagamento"
                    },
                    {"value":"REG",
                      "label_zh":"入學登記手續、體檢",
                      "label_en":"Student registration and physical examination",
                      "label_pt":"Formalidades de Matrícula e Exame Médico"
                    },
                    {"value":"OTH",
                      "label_zh":"其他",
                      "label_en":"others",
                      "label_pt":"Outros"
                    }
                ]
            }'
        ]);
    
        DB::table('configs')->insert([
            'key'=>'enquiry_form',
            'label'=>'agree',
            'value'=>'{
                "question_zh":"本人同意澳門理工大學就招生資訊或活動發送電郵或短訊予本人。",
                "question_en":"I give my consent to Macau Polytechnic University for send me emails or SMS regarding admissions information or activities.",
                "question_pt":"Concordo com o envio, pela UPM, de mensagens ou emails sobre as informações ou actividades de admissão.",
                "short":"同意"
            }'
        ]);

        DB::table('configs')->insert([
          'key'=>'enquiry_form',
          'label'=>'still_have_question',
          'value'=>'{
            "question_zh":"是否尚有查詢?",
            "question_en":"Still have Questions?",
            "question_pt":"Ainda tem perguntas",
            "short":"尚有查詢"
          }'
        ]);
        DB::table('configs')->insert([
          'key'=>'enquiry_form',
          'label'=>'write_question',
          'value'=>'{
            "question_zh":"請寫下你的問題.",
            "question_en":"Please write your question.",
            "question_pt":"Por favor escreva a sua pergunta.",
            "short":"尚有查詢"
          }'
        ]);
        
        DB::table('configs')->insert([
            'key'=>'phone_country_codes',
            'label'=>'phone_country_code',
            'value'=>'
            [
              {
                "countryCode": "86",
                "label_zh": "中國",
                "label_en": "China",
                "label_pt": "China"
              },{
                "countryCode": "853",
                "label_zh": "澳門",
                "label_en": "Macao",
                "label_pt": "Macau"
              },{
                "countryCode": "852",
                "label_zh": "香港",
                "label_en": "Hong Kong",
                "label_pt": "Hong Kong"
              },{
                "countryCode": "886",
                "label_zh": "台灣",
                "label_en": "Taiwan",
                "label_pt": "Taiwan"
              },{
                "countryCode": "1",
                "label_zh": "美國",
                "label_en": "United States",
                "label_pt": "Estados Unidos"
              },{
                "countryCode": "1",
                "label_zh": "加拿大",
                "label_en": "Canada",
                "label_pt": "Canadá"
              },{
                "countryCode": "44",
                "label_zh": "英國",
                "label_en": "United Kingdom",
                "label_pt": "Reino Unido"
              },{
                "countryCode": "61",
                "label_zh": "澳大利亞",
                "label_en": "Australia",
                "label_pt": "Austrália"
              },{
                "countryCode": "91",
                "label_zh": "印度",
                "label_en": "India",
                "label_pt": "Índia"
              },{
                "countryCode": "81",
                "label_zh": "日本",
                "label_en": "Japan",
                "label_pt": "Japão"
              },{
                "countryCode": "49",
                "label_zh": "德國",
                "label_en": "Germany",
                "label_pt": "Alemanha"
              },{
                "countryCode": "33",
                "label_zh": "法國",
                "label_en": "France",
                "label_pt": "França"
              },{
                "countryCode": "55",
                "label_zh": "巴西",
                "label_en": "Brazil",
                "label_pt": "Brazil"
              },{
                "countryCode": "52",
                "label_zh": "墨西哥",
                "label_en": "Mexico",
                "label_pt": "México"
              },{
                "countryCode": "238",
                "label_zh": "佛德角",
                "label_en": "Cape Verde",
                "label_pt": "Cape Verde"
              },{
                "countryCode": "239",
                "label_zh": "聖多美及普林西比",
                "label_en": "São Tomé and Príncipe",
                "label_pt": "São Tomé and Príncipe"
              },{
                "countryCode": "240",
                "label_zh": "赤道幾內亞",
                "label_en": "Equatorial Guine",
                "label_pt": "Guiné Equatorial"
              },{
                "countryCode": "245",
                "label_zh": "幾內亞比紹",
                "label_en": "Guinea-Bissau",
                "label_pt": "Guiné-Bissau"
              },{
                "countryCode": "244",
                "label_zh": "安哥拉",
                "label_en": "Angola",
                "label_pt": "Angola"
              },{
                "countryCode": "258",
                "label_zh": "莫桑比克",
                "label_en": "Mozambique",
                "label_pt": "Mozambique"
              },{
                "countryCode": "27",
                "label_zh": "南非",
                "label_en": "South Africa",
                "label_pt": "África do Sul"
              },{
                "countryCode": "7",
                "label_zh": "俄羅斯",
                "label_en": "Russia",
                "label_pt": "Rússia"
              },{
                "countryCode": "34",
                "label_zh": "西班牙",
                "label_en": "Spain",
                "label_pt": "Espanha"
              },{
                "countryCode": "351",
                "label_zh": "葡萄牙",
                "label_en": "Portugal",
                "label_pt": "Portugal"
              },{
                "countryCode": "39",
                "label_zh": "意大利",
                "label_en": "Italy",
                "label_pt": "Itália"
              },{
                "countryCode": "31",
                "label_zh": "荷蘭",
                "label_en": "Netherlands",
                "label_pt": "Holanda"
              },{
                "countryCode": "60",
                "label_zh": "馬來西亞",
                "label_en": "Malaysia",
                "label_pt": "Malásia"
              },{
                "countryCode": "63",
                "label_zh": "菲律賓",
                "label_en": "Philipines",
                "label_pt": "Philipinas"
              },{
                "countryCode": "65",
                "label_zh": "新加坡",
                "label_en": "Singapore",
                "label_pt": "Cingapura"
              },{
                "countryCode": "66",
                "label_zh": "泰國",
                "label_en": "Thailand",
                "label_pt": "Tailândia"
              },{
                "countryCode": "670",
                "label_zh": "東帝汶",
                "label_en": "Timor-Leste",
                "label_pt": "Timor-Leste"
              },{
                "countryCode": "82",
                "label_zh": "韓國",
                "label_en": "South Korea",
                "label_pt": "Coreia do Sul"
              },{
                "countryCode": "95",
                "label_zh": "緬甸",
                "label_en": "Myanmar",
                "label_pt": "Mianmar"
              },{
                "countryCode": "966",
                "label_zh": "沙特阿拉伯",
                "label_en": "Saudi Arabia",
                "label_pt": "Arábia Saudita"
              },{
                "countryCode": "971",
                "label_zh": "阿拉伯聯合酋長國",
                "label_en": "United Arab Emirates",
                "label_pt": "Emirados Árabes Unidos"
              }
            ]'
        ]);
        DB::table('configs')->insert([
          'key'=>'article_categories',
          'label'=>'Article Categories',
          'value'=>'
          [
            {"value":"GENERAL","label":"General Page"},
            {"value":"STAFF","label":"Staff Page"},
            {"value":"DEPARTMENT","label":"Department Page"}
          ]'
        ]);

        DB::table('configs')->insert([
          'key'=>'upload_document_types',
          'label'=>'Upload Document Type',
          'value'=>'
          [
            {"value":"IDC","label_zh":"身份證明文件","label_en":"Identification Document"},
            {"value":"EDU","label_zh":"學歷證明文件","label_en":"Education Certificate"},
            {"value":"RSM","label_zh":"履歷表","label_en":"Resume"},
            {"value":"WRK","label_zh":"工作證明文件","label_en":"Work certificate document"},
            {"value":"TRN","label_zh":"培訓證書/專業資格","label_en":"Training certificate/Professional qualification"},
            {"value":"ACH","label_zh":"學術成就","label_en":"Academic Achievement"},
            {"value":"OTH","label_zh":"其他相關證明文件","label_en":"Other relevant documents"}
          ]'
        ]);
        DB::table('configs')->insert([
          'key'=>'birth_places',
          'label'=>'Place of Birth',
          'value'=>'
          [
            {"value":"CHN","label_zh":"身份證明文件","label_en":"Identification Document"},
            {"value":"MAC","label_zh":"學歷證明文件","label_en":"Education Certificate"},
            {"value":"HKG","label_zh":"履歷表","label_en":"Resume"},
            {"value":"OTH","label_zh":"履歷表","label_en":"Resume"}
          ]'
        ]);
        DB::table('configs')->insert([
          'key'=>'id_types',
          'label'=>'ID Type',
          'value'=>'
          [
            {"value":"MAC_RST","label_zh":"澳門(永久)居民身份證","label_en":"China, Macao (Permanent)"},
            {"value":"MAC_TMP","label_zh":"澳門(非永久)居民身份證","label_en":"China, Macao (Non-permanent)"},
            {"value":"HKG_RST","label_zh":"香港居民身份證","label_en":"China, Hong Kong"},
            {"value":"CHN_IDC","label_zh":"中國內地居民身份證","label_en":"China, Mainland"},
            {"value":"PPT_INT","label_zh":"護照","label_en":"Passport"},
            {"value":"OTH_DOC","label_zh":"其他","label_en":"Other"}
          ]'
        ]);
        DB::table('configs')->insert([
          'key'=>'rec_nationalities',
          'label'=>'Recruitment Nationality',
          'value'=>'
          [
            {"value":"CHN","label_zh":"中國籍","label_en":"Chinese"},
            {"value":"PPT","label_zh":"葡國籍","label_en":"Portugese"},
            {"value":"OTH","label_zh":"其他","label_en":"Other"}
          ]'
        ]);
        DB::table('configs')->insert([
          'key'=>'rec_educations',
          'label'=>'Recuitment Educations',
          'value'=>'
          [
            {"value":"0_NON","label_zh":"見開考通告","label_en":"refer to recruitment notices"},
            {"value":"1_PRM","label_zh":"小學","label_en":"Primary"},
            {"value":"2_JHS","label_zh":"初中畢業","label_en":"Junior High School"},
            {"value":"3_SHS","label_zh":"高中畢業","label_en":"Senior High School"},
            {"value":"4_HDP","label_zh":"高等課程","label_en":"Deploma"},
            {"value":"5_BCH","label_zh":"學士學位","label_en":"Bachalor"},
            {"value":"6_MAST","label_zh":"碩士學位","label_en":"Master"},
            {"value":"7_PHD","label_zh":"博士學位","label_en":"PhD"},
            {"value":"8_PPD","label_zh":"博士後","label_en":"Postdoctoral"}
          ]'
        ]);
        DB::table('configs')->insert([
          'key'=>'rec_vehicles',
          'label'=>'Recuitment Vehicles',
          'value'=>'
          [
            {"value":"HV3","label_zh":"重型汽車駕駛執照，並具三年駕駛重型汽車的工作經驗","label_en":"Heavy vehicle 3 years"},
            {"value":"RH3","label_zh":"輕型汽車駕駛執照，並具三年駕駛輕型汽車的工作經驗","label_en":"Vehicle e years"},
            {"value":"IRN","label_zh":"見開考通告","label_en":"refer to recruitment notices"}
          ]'
        ]);


    }
}
