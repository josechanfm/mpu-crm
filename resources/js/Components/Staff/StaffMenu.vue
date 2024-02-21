<template>
    <a-menu v-model:selectedKeys="current" mode="horizontal">
        <template v-for="item in menuItems" :key="item.key">
            <a-menu-item >
                <template #icon>
                    <component :is="item.icon" />
                </template>
                <template v-if="item.route">
                    <inertia-link :href="route(item.route)" :target="item.target">{{ item.title }}</inertia-link>
                </template>
                <template v-else>
                    {{ item.path }}
                </template>
            </a-menu-item>
        </template>
        <a-menu-item>
            <a @click="logout">登出</a>
        </a-menu-item>

    </a-menu>
</template>

  <script>
  import { Inertia } from '@inertiajs/inertia';
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
      const current = ref(['staff']);
      const menuItems=ref([
        {
            key:'staff',
            icon:'home-outlined',
            title:'主頁',
            route:'staff',
        },{
            key:'professionals.index',
            icon:'verified-outlined',
            title:'專業認證',
            path:'staff',
        },{
            key:'membership',
            icon:'idcard-outlined',
            title:'會籍',
            route:'staff',
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
    },
    methods: {
        logout(){
            console.log("logout");
            Inertia.post(route('manage.logout'));
        }
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

