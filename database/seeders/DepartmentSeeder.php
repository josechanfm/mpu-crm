<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\AdminUser;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments=[
            ["abbr"=>"CAD","name_zh"=>"行政管理委員會","name_en"=>"CONSELHO ADMINISTRATIVO","name_pt"=>"CONSELHO ADMINISTRATIVO","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"FCA","name_zh"=>"應用科學學院","name_en"=>"FACULDADE DE CIÊNCIAS APLICADAS","name_pt"=>"FACULDADE DE CIÊNCIAS APLICADAS","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"FCSD","name_zh"=>"健康科學及體育學院","name_en"=>"FACULDADE DE CIÊNCIAS DE SAÚDE E DESPORTO","name_pt"=>"FACULDADE DE CIÊNCIAS DE SAÚDE E DESPORTO","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"FLT","name_zh"=>"語言及翻譯學院","name_en"=>"FACULDADE DE LÍNGUAS E TRADUÇÃO","name_pt"=>"FACULDADE DE LÍNGUAS E TRADUÇÃO","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"FAD","name_zh"=>"藝術及設計學院","name_en"=>"FACULDADE DE ARTES E DESIGN","name_pt"=>"FACULDADE DE ARTES E DESIGN","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"FCHS","name_zh"=>"人文及社會科學學院","name_en"=>"FACULDADE DE CIÊNCIAS HUMANAS E SOCIAIS","name_pt"=>"FACULDADE DE CIÊNCIAS HUMANAS E SOCIAIS","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"FCG","name_zh"=>"管理科學學院","name_en"=>"FACULDADE DE CIÊNCIAS DE GESTÃO","name_pt"=>"FACULDADE DE CIÊNCIAS DE GESTÃO","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"CEUPDS","name_zh"=>"\"一國兩制\"研究中心","name_en"=>"CENTRO DE ESTUDOS «UM PAÍS, DOIS SISTEMAS»","name_pt"=>"CENTRO DE ESTUDOS «UM PAÍS, DOIS SISTEMAS»","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"CJT","name_zh"=>"博彩旅遊教學及研究中心","name_en"=>"CENTRO PEDAGÓGICO E CIENTÍFICO NAS ÁREAS DO JOGO E DO TURISMO","name_pt"=>"CENTRO PEDAGÓGICO E CIENTÍFICO NAS ÁREAS DO JOGO E DO TURISMO","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"CPCLP","name_zh"=>"葡語教學及研究中心","name_en"=>"CENTRO PEDAGÓGICO E CIENTÍFICO DA LÍNGUA PORTUGUESA","name_pt"=>"CENTRO PEDAGÓGICO E CIENTÍFICO DA LÍNGUA PORTUGUESA","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"CIA","name_zh"=>"機器翻譯暨人工智能應用技術教育部工程研究中心","name_en"=>"CENTRO DE INVESTIGAÇÃO DE SERVIÇO DA EDUCAÇÃO DE TECNOLOGIA APLICADA EM TRADUÇÃO AUTOMÁTICA E INTELIGÊNCIA ARTIFICIAL","name_pt"=>"CENTRO DE INVESTIGAÇÃO DE SERVIÇO DA EDUCAÇÃO DE TECNOLOGIA APLICADA EM TRADUÇÃO AUTOMÁTICA E INTELIGÊNCIA ARTIFICIAL","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"CEA","name_zh"=>"教與學中心","name_en"=>"CENTRO DE ENSINO E APRENDIZAGEM","name_pt"=>"CENTRO DE ENSINO E APRENDIZAGEM","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"CEC","name_zh"=>"持續教育中心","name_en"=>"CENTRO DE EDUCAÇÃO CONTÍNUA","name_pt"=>"CENTRO DE EDUCAÇÃO CONTÍNUA","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"ACS","name_zh"=>"長者書院","name_en"=>"ACADEMIA DO CIDADÃO SÉNIOR","name_pt"=>"ACADEMIA DO CIDADÃO SÉNIOR","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"CIPF","name_zh"=>"國際葡萄牙語培訓中心","name_en"=>"CENTRO INTERNACIONAL PORTUGUÊS DE FORMAÇÃO EM INTERPRETAÇÃO DE CONFERÊNCIA","name_pt"=>"CENTRO INTERNACIONAL PORTUGUÊS DE FORMAÇÃO EM INTERPRETAÇÃO DE CONFERÊNCIA","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"AE","name_zh"=>"北京大學醫學部 – 澳門理工大學護理書院","name_en"=>"ACADEMIA DE ENFERMAGEM DO CENTRO DE CIÊNCIAS DA SAÚDE DA UNIVERSIDADE DE PEQUIM E DA UNIVERSIDADE POLITÉCNICA DE MACAU","name_pt"=>"ACADEMIA DE ENFERMAGEM DO CENTRO DE CIÊNCIAS DA SAÚDE DA UNIVERSIDADE DE PEQUIM E DA UNIVERSIDADE POLITÉCNICA DE MACAU","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"SU","name_zh"=>"校務研究及協調部","name_en"=>"SERVIÇO DE ESTUDOS E COORDENAÇÃO DE ASSUNTOS UNIVERSITÁRIOS","name_pt"=>"SERVIÇO DE ESTUDOS E COORDENAÇÃO DE ASSUNTOS UNIVERSITÁRIOS","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"SAA","name_zh"=>"學術事務部","name_en"=>"SERVIÇO DE ASSUNTOS ACADÉMICOS","name_pt"=>"SERVIÇO DE ASSUNTOS ACADÉMICOS","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"DAMIA","name_zh"=>"招生註冊處","name_en"=>"DIVISÃO DE ADMISSÃO, MATRÍCULA E INSCRIÇÃO","name_pt"=>"DIVISÃO DE ADMISSÃO, MATRÍCULA E INSCRIÇÃO","landing_page"=>"<p>Department Landing page</p>","default_route"=>"registry.dashboard"],
            ["abbr"=>"DAE","name_zh"=>"學生事務處","name_en"=>"DIVISÃO DE ASSUNTOS DE ESTUDANTES","name_pt"=>"DIVISÃO DE ASSUNTOS DE ESTUDANTES","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"DEI","name_zh"=>"教研處","name_en"=>"DIVISÃO DE ENSINO E INVESTIGAÇÃO","name_pt"=>"DIVISÃO DE ENSINO E INVESTIGAÇÃO","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"BIB","name_zh"=>"圖書館","name_en"=>"BIBLIOTECA","name_pt"=>"BIBLIOTECA","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"SAF","name_zh"=>"行政及財政部","name_en"=>"SERVIÇO DE ADMINISTRAÇÃO E FINANÇAS","name_pt"=>"SERVIÇO DE ADMINISTRAÇÃO E FINANÇAS","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"PES","name_zh"=>"人事處","name_en"=>"DIVISÃO DE ASSUNTOS DE PESSOAL","name_pt"=>"DIVISÃO DE ASSUNTOS DE PESSOAL","landing_page"=>"<p>Department Landing page</p>","default_route"=>"personnel.dashboard"],
            ["abbr"=>"GF","name_zh"=>"財務處","name_en"=>"DIVISÃO DE ASSUNTOS FINANCEIROS","name_pt"=>"DIVISÃO DE ASSUNTOS FINANCEIROS","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"SGDC","name_zh"=>"校園管理及發展部","name_en"=>"SERVIÇO DE GESTÃO E DESENVOLVIMENTO DO CAMPUS","name_pt"=>"SERVIÇO DE GESTÃO E DESENVOLVIMENTO DO CAMPUS","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"DMDC","name_zh"=>"校園維護及發展處","name_en"=>"DIVISÃO DE MANUTENÇÃO E DESENVOLVIMENTO DO CAMPUS","name_pt"=>"DIVISÃO DE MANUTENÇÃO E DESENVOLVIMENTO DO CAMPUS","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"DOA","name_zh"=>"工程及採購處","name_en"=>"DIVISÃO DE OBRAS E AQUISIÇÕES","name_pt"=>"DIVISÃO DE OBRAS E AQUISIÇÕES","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"IT","name_zh"=>"資訊科技部","name_en"=>"SERVIÇO DE TECNOLOGIAS DE INFORMAÇÃO","name_pt"=>"SERVIÇO DE TECNOLOGIAS DE INFORMAÇÃO","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"GRP","name_zh"=>"公共關係辦公室","name_en"=>"GABINETE DE RELAÇÕES PÚBLICAS","name_pt"=>"GABINETE DE RELAÇÕES PÚBLICAS","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"],
            ["abbr"=>"BELL","name_zh"=>"理大 - 貝爾英語中心","name_en"=>"MPU - BELL CENTRO DE INGLÊS","name_pt"=>"MPU - BELL CENTRO DE INGLÊS","landing_page"=>"<p>Department Landing page</p>","default_route"=>"staff"]
        ];

        foreach($departments as $department){
            DB::table('departments')->insert($department);
    
        }
        $adminUser=AdminUser::where('username','admin')->first();
        $department=Department::where('abbr','PES')->first();
        $adminUser->departments()->attach($department);
        $department=Department::where('abbr','DAMIA')->first();
        $adminUser->departments()->attach($department);

    }
}
