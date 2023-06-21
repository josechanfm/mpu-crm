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
      <a-sub-menu key="enquiry">
        <template #icon>
          <MailOutlined />
        </template>
        <template #title>Enquriy</template>
        <a-menu-item key="enquriy_1">
          <inertia-link :href="route('manage.enquiries.index')">
            Enquiries
          </inertia-link>
        </a-menu-item>
        <a-menu-item key="enquiry_2">
          <inertia-link :href="route('manage.enquiry.questions.index')">
            Questions
          </inertia-link>
        </a-menu-item>
        <a-menu-item key="enqriry_3" v-if="$page.props.currentUser.roles.includes('admin') || $page.props.currentUser.roles.includes('master')">
          <inertia-link :href="route('manage.enquiry.faqs.index')">
            Faqs
          </inertia-link>
        </a-menu-item>
      </a-sub-menu>
      <a-menu-item key="5">
        <template #icon>
          <InboxOutlined />
        </template>
        <span>
          <inertia-link :href="route('manage.forms.index')">
            Forms
          </inertia-link>
        </span>
      </a-menu-item>
      <a-sub-menu key="sub1">
        <template #icon>
          <MailOutlined />
        </template>
        <template #title>Navigation One</template>
        <a-menu-item key="5">
          <a href="/manage/enquiries">
            Enquiries
          </a>
        </a-menu-item>
        <a-menu-item key="6">Option 6</a-menu-item>
        <a-menu-item key="7">Option 7</a-menu-item>
        <a-menu-item key="8">Option 8</a-menu-item>
      </a-sub-menu>
      <a-sub-menu key="sub2">
        <template #icon>
          <AppstoreOutlined />
        </template>
        <template #title>Navigation Two</template>
        <a-menu-item key="9">Option 9</a-menu-item>
        <a-menu-item key="10">Option 10</a-menu-item>
        <a-sub-menu key="sub3" title="Submenu">
          <a-menu-item key="11">Option 11</a-menu-item>
          <a-menu-item key="12">Option 12</a-menu-item>
        </a-sub-menu>
      </a-sub-menu>
    </a-menu>
  </div>
</template>
<script>
import { defineComponent, reactive, toRefs, watch } from 'vue';
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
  props: ['department'],
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
    return {
      ...toRefs(state),
      toggleCollapsed,
    };
  },
});
</script>