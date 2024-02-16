<template>
    <a-menu v-model:selectedKeys="current" mode="horizontal">
      <a-menu-item v-for="item in menuItems" :key="item.key">
        <template #icon>
          <component :is="item.icon" />
        </template>
        <inertia-link :href="route(item.route)" :target="item.target">{{ item.title }}</inertia-link>
      </a-menu-item>
    </a-menu>
</template>
  <script>
  import { defineComponent, ref } from 'vue';
  import { HomeOutlined, VerifiedOutlined, AppstoreOutlined, IdcardOutlined, BankOutlined, EditOutlined, SettingOutlined } from '@ant-design/icons-vue';
  export default defineComponent({
    components: {
      HomeOutlined,
      VerifiedOutlined,
      AppstoreOutlined,
      IdcardOutlined,
      BankOutlined,
      EditOutlined,
      SettingOutlined,
    },
    setup() {
      const current = ref(['member']);
      const menuItems=ref([
        {
            key:'member',
            icon:'home-outlined',
            title:'主頁',
            route:'member',
        // },{
        //     key:'professionals.index',
        //     icon:'verified-outlined',
        //     title:'專業認證',
        //     route:'professionals.index',
        // },{
        //     key:'membership',
        //     icon:'idcard-outlined',
        //     title:'會籍',
        //     route:'membership',
        // },{
        //     key:'courses',
        //     icon:'bank-outlined',
        //     title:'課程',
        //     route:'courses',
        // },{
        //     key:'forms.index',
        //     icon:'edit-outlined',
        //     title:"報名",
        //     route:'forms.index',
        // },{
        //     key:'admin.index',
        //     icon:'appstore-outlined',
        //     title:'行政管理',
        //     route:'admin.index',
        //     target:'_blank'
        },
      ]);
      return {
        current,
        menuItems
      };
    },
    created(){
    }
  });
  </script>

<style>
.ant-menu-root{
    padding-top:10px;
}

.ant-menu-title-content{
  padding-top:5px!important
}
</style>

