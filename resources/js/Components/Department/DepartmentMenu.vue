<template>
  <div>
    <a-menu 
      v-model:openKeys="openKeys" 
      v-model:selectedKeys="selectedKeys" 
      mode="inline" theme="light"
      :inline-collapsed="collapsed"
    >
      <a-menu-item key="1">
        <template #icon>
          <PieChartOutlined />
        </template>
        <span>
          <inertia-link :href="route('manage.dashboard')">
            所屬部門
          </inertia-link>
        </span>
      </a-menu-item>

      <a-sub-menu key="enquiry" v-if="is('DAMIA | admin | master')">
        <template #icon>
          <QuestionCircleOutlined />
        </template>
        <template #title>問題查詢</template>
        <a-menu-item key="enquriy_1">
          <inertia-link :href="route('registry.enquiries.index')">
            所有查詢
          </inertia-link>
        </a-menu-item>
        <a-menu-item key="enquiry_2">
          <inertia-link :href="route('registry.enquiry.questions.index')">
            須回應提問
          </inertia-link>
        </a-menu-item>
        <a-menu-item key="enqriry_3" >
          <inertia-link :href="route('registry.faqs.index')">
            常見問題
          </inertia-link>
        </a-menu-item>
        <a-menu-item key="enqriry_4" >
          <inertia-link :href="route('registry.report.index')">
            統計及報告
          </inertia-link>
        </a-menu-item>
      </a-sub-menu>


        <a-sub-menu key="staff_card" v-if="is('PES | admin | master')">
        <template #icon>
          <IdcardOutlined  />
        </template>
        <template #title>員工卡</template>
        <a-menu-item key="staff_card_1">
          <inertia-link :href="route('personnel.staffs.index')">
            員工名單
          </inertia-link>
        </a-menu-item>
        <a-menu-item key="staff_card_2">
          <inertia-link :href="route('personnel.staff.notices.index')">
            電邸自動通知
          </inertia-link>
        </a-menu-item>
      </a-sub-menu>

      <a-menu-item key="personnel" v-if="is('PES | admin | master')">
        <template #icon>
          <MoneyCollectOutlined />
        </template>
        <span>
          <inertia-link :href="route('personnel.gpdps.index')">
            財產申報
          </inertia-link>
        </span>
      </a-menu-item>

      <a-sub-menu key="recruitment" v-if="is('PES| admin | master')">
        <template #icon>
          <SolutionOutlined />
        </template>
        <template #title>職位招聘</template>
        <!-- <a-menu-item key="recruitment_0">
          <a href="https://jupiter.ipm.edu.mo/wms/personnel/workflows">
            標準流程
          </a>
        </a-menu-item> -->
        <a-menu-item key="recruitment_1">
          <inertia-link :href="route('personnel.recruitment.tasks.index')">
            標準流程
          </inertia-link>
        </a-menu-item>
        <a-menu-item key="recruitment_2">
          <inertia-link :href="route('personnel.recruitment.workflows.index')">
            招聘流程
          </inertia-link>
        </a-menu-item>
        <a-menu-item key="recruitment_2">
          <inertia-link :href="route('personnel.recruitment.vacancies.index')">
            職位空缺
          </inertia-link>
        </a-menu-item>
      </a-sub-menu>
      <a-menu-item key="personnel" v-if="is('DAE | admin | master')">
        <template #icon>
          <TrophyOutlined />
        </template>
        <span>
          <inertia-link :href="route('dae.dashboard')">
            學生事務處
          </inertia-link>
        </span>
      </a-menu-item>
        
      <a-menu-item key="forms">
        <template #icon>
          <FormOutlined />
        </template>
        <span>
          <inertia-link :href="route('manage.forms.index')">
            表格
          </inertia-link>
        </span>
      </a-menu-item>

      <a-menu-item key="shortener">
        <template #icon>
          <LinkOutlined />
        </template>
        <span>
          <inertia-link :href="route('manage.shorteners.index')">
            URL Shortener
          </inertia-link>
        </span>
      </a-menu-item>

    </a-menu>
  </div>
</template>
<script>
import { defineComponent, onMounted, reactive, toRefs, watch } from 'vue';
import { MenuFoldOutlined, MenuUnfoldOutlined, PieChartOutlined, MailOutlined, DesktopOutlined, InboxOutlined, AppstoreOutlined, QuestionCircleOutlined, MoneyCollectOutlined, SolutionOutlined, TrophyOutlined, LinkOutlined, FormOutlined, IdcardOutlined  } from '@ant-design/icons-vue';

export default defineComponent({
  components: {
    MenuFoldOutlined,
    MenuUnfoldOutlined,
    PieChartOutlined,
    MailOutlined,
    DesktopOutlined,
    InboxOutlined,
    AppstoreOutlined,
    QuestionCircleOutlined,
    MoneyCollectOutlined,
    SolutionOutlined,
    TrophyOutlined,
    FormOutlined,
    LinkOutlined,
    IdcardOutlined 
  },
  props: [],
  setup() {
    const state = reactive({
      collapsed: false,
      selectedKeys: ['1'],
      openKeys: [''],
      preOpenKeys: ['sub1'],
    });
    watch(() => state.openKeys, (_val, oldVal) => {
      state.preOpenKeys = oldVal;
    });
    const toggleCollapsed = () => {
      state.collapsed = !state.collapsed;
      state.openKeys = state.collapsed ? [] : state.preOpenKeys;
    };
    onMounted(()=> {
      axios.get('/staff/get-permissions').then(
            response => {
                if(JSON.stringify(window.Laravel.jsPermissions) !== JSON.stringify(response.data)){
                    window.Laravel.jsPermissions=response.data;
                    window.location.reload();
                }
            }
        )
    });
    return {
      ...toRefs(state),
      toggleCollapsed,
    };
  },
});
</script>