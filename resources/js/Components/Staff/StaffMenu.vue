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
                  <a :href="item.url" :target="item.target">{{ item.title }}</a>
                </template>
            </a-menu-item>
        </template>
        <template v-if="$page.props.currentUser.roles.length>0">
          <template v-for="role in $page.props.currentUser.roles">
          <a-menu-item v-if="role!='admin' && role!='master'">
              <inertia-link :href="route('manage.department.redirect',{roleName:role})">{{ role }}</inertia-link>
          </a-menu-item>
        </template>
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
        // },{
        //     key:'official-web',
        //     icon:'verified-outlined',
        //     title:'MPU',
        //     url:'https://www.mpu.edu.mo',
        //},{
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
            Inertia.post(route('staff.logout'));
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

