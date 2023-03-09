<template>
    <div>
        <div class="m-4 text-center text-lg ">
            <inertia-link href='/organization'>Organization </inertia-link>
        </div>
        <a-menu 
            mode="inline" 
            theme="light" 
            v-model:openKeys="openedMenu" v-model:selectedKeys="selectedKeys" 
            style="font-size:16px"
            :inline-collapsed="collapsed">
            <template v-for="item in menu" :key="item.key">
                <template v-if="!item.children">
                    <a-menu-item :key="item.key" 
                        style="font-size:16px">
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
            <hr class="border-gray-600 mx-3" />
            <a-menu-item 
                style="font-size:16px">
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
import { Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia';
import menu from './menu.js';
import SubMenu from './SubMenu.vue';
import * as AntdIcons from '@ant-design/icons-vue';

export default defineComponent({
    components: {
        'sub-menu': SubMenu,
        ...AntdIcons
    },
    props: ['menuKeys'],

    setup() {

        const collapsed = ref(false)

        const toggleCollapsed = () => {
            collapsed.value = !collapsed.value;
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

                    let key = route().current().split('.')
                    key.pop()
                    this.selectedKeys.push(key.join('.'))
                }
            })
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