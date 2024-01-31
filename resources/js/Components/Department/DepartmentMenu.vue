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
            Department(s)
          </inertia-link>
        </span>
      </a-menu-item>
      <a-sub-menu key="enquiry" v-if="is('DAMIA | admin | master')">
        <template #icon>
          <MailOutlined />
        </template>
        <template #title>Enquriy</template>
        <a-menu-item key="enquriy_1">
          <inertia-link :href="route('registry.enquiries.index')">
            Enquiries
          </inertia-link>
        </a-menu-item>
        <a-menu-item key="enquiry_2">
          <inertia-link :href="route('registry.enquiry.questions.index')">
            Questions
          </inertia-link>
        </a-menu-item>
        <a-menu-item key="enqriry_3" >
          <inertia-link :href="route('registry.faqs.index')">
            Faqs
          </inertia-link>
        </a-menu-item>
      </a-sub-menu>
      <a-menu-item key="personnel" v-if="is('PES | admin | master')">
        <template #icon>
          <InboxOutlined />
        </template>
        <span>
          <inertia-link :href="route('personnel.gpdps.index')">
            個資通知
          </inertia-link>
        </span>
      </a-menu-item>
      <a-menu-item key="forms">
        <template #icon>
          <InboxOutlined />
        </template>
        <span>
          <inertia-link :href="route('manage.forms.index')">
            Forms
          </inertia-link>
        </span>
      </a-menu-item>
    </a-menu>
  </div>
</template>
<script>
import { defineComponent, onMounted, reactive, toRefs, watch } from 'vue';
import { MenuFoldOutlined, MenuUnfoldOutlined, PieChartOutlined, MailOutlined, DesktopOutlined, InboxOutlined, AppstoreOutlined } from '@ant-design/icons-vue';

export default defineComponent({
  components: {
    MenuFoldOutlined,
    MenuUnfoldOutlined,
    PieChartOutlined,
    MailOutlined,
    DesktopOutlined,
    InboxOutlined,
    AppstoreOutlined,
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
      axios.get('/get-permissions').then(
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