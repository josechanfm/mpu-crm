<template>
    <div>
        <div class="m-4 text-center text-lg">
            Student
        </div>
        <a-menu 
            v-model:openKeys="openedMenu" 
            v-model:selectedKeys="selectedKeys" 
            mode="inline" 
            theme="light"
            :inline-collapsed="collapsed"
            style="font-size:16px">
            <template v-for="item in menu" :key="item.key">
                <template v-if="!item.children">
                    <a-menu-item :key="item.key">
                        <template #icon>
                            <PieChartOutlined />
                        </template>
                        <inertia-link :href="route(item.route)"> {{ item.title }} </inertia-link>
                    </a-menu-item>
                </template>
                <template v-else>
                    <sub-menu :key="item.key" :menu-info="item" />
                </template>
            </template>
            <a-menu-item>
                <template #icon>
                    <LogoutOutlined />
                </template>
                <a @click.prevent='logout'>
                    Logout
                </a>
            </a-menu-item>
        </a-menu>
    </div>
</template>
<script>
import { defineComponent, ref, watch } from 'vue';
import { MenuFoldOutlined, MenuUnfoldOutlined, PieChartOutlined, MailOutlined, StepForwardFilled, LogoutOutlined } from '@ant-design/icons-vue'; // you can rewrite it to a single file component, if not, you should config vue alias to vue/dist/vue.esm-bundler.js
import { Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia';
import menu from './menu.js';

const SubMenu = {
    name: 'SubMenu',
    props: {
        menuInfo: {
            type: Object,
            default: () => ({}),
        },
    },
    template: `
      <a-sub-menu :key="menuInfo.key">
        <template #icon><MailOutlined /></template>
        <template #title>{{ menuInfo.title }}</template>
        <template v-for="item in menuInfo.children" :key="item.key">
          <template v-if="!item.children">
            <a-menu-item :key="item.key">
              <template #icon>
                <PieChartOutlined />
              </template>
                <a v-if="item.target" :href="item.url" :target="item.target">
                  {{ item.title }}
                </a>
                <inertia-link :href="route(item.route)"> {{ item.title }} </inertia-link>
            </a-menu-item>
          </template>
          <template v-else>
            <sub-menu :menu-info="item" :key="item.key" />
          </template>
        </template>
      </a-sub-menu>
    `,
    components: {
        PieChartOutlined,
        MailOutlined,
        Link
    },
};

export default defineComponent({
    components: {
        'sub-menu': SubMenu,
        MenuFoldOutlined,
        MenuUnfoldOutlined,
        PieChartOutlined,
        LogoutOutlined,
    },
    props: ['menuKeys'],

    setup() {

        const collapsed = false

        const toggleCollapsed = () => {
            collapsed = !collapsed;
        };

        return {
            menu,
            collapsed,
            // selectedKeys,
            toggleCollapsed,
            logout() { Inertia.post(route('logout')) }
        };
    },

    data() {
        return {
            openKeys: [],
            selectedKeys: [],
        }
    },

    computed: {
        openedMenu() {
            let arr = []

            menu.forEach((ele, key) => {

                if (ele.children != null && this.searchMenu(ele.children, ele.key) != "") {

                    this.openKeys.push(this.searchMenu(ele.children, ele.key).toString())
                }
            })

            let key = route().current().split('.')
            key.pop()

            key = this.openKeys[0] + "." + key[0]

            this.selectedKeys.push(key)

            //bring key to parent
            this.menuKeys.menuOpenKey = this.openKeys
            this.menuKeys.menuSelectKey = this.selectedKeys

            return this.openKeys
        },

    },
    methods: {
        searchMenu(child, parent) {

            let found = false

            child.forEach((ele) => {
                let child_key = ele.key.split(".")

                let route_parent = route().current().split(".")

                // remove the action
                route_parent.pop()

                // child keys in parent
                if (child_key.includes(parent)) {

                    // route in child keys
                    route_parent.map(k => child_key.includes(k) == true ? found = true : "")
                }
            })
            return found == true ? parent : ""

        }
    }
});
</script>