<template>
    <a-layout style="min-height: 100vh">

        <a-layout-sider v-model:collapsed="collapsed" :trigger="null" collapsible theme="light" width="250px" class="shadow-md " >
            <div class="m-4 text-center text-lg" v-if="collapsed">
                <inertia-link :href="route('staff')"">MPU </inertia-link>
            </div>
            <div class="m-4 text-center text-lg" v-else>
                <inertia-link :href="route('staff')">MPU </inertia-link>
            </div>
            <DepartmentMenu :menuKeys='menuKeys'/>
        </a-layout-sider>
        
        <a-layout>
            <a-layout-header class="shadow-md border-b-2 border-red-600 flex" style="background: #fff; padding: 0">
                <menu-unfold-outlined v-if="collapsed" class="trigger" @click="() => (collapsed = !collapsed)" />
                <menu-fold-outlined v-else class="trigger" @click="() => (collapsed = !collapsed)" />

                <div class="flex-1"></div>

                <a-dropdown placement="bottomRight">
                    <a class="trigger" @click.prevent>
                        {{ $page.props.currentUser.username }}
                    </a>
                    <template #overlay>
                        <a-menu>
                            <a-menu-item>
                                <a href="javascript:;">我的資料</a>
                            </a-menu-item>
                            <a-menu-divider />
                            <a-menu-item>
                                <a href="javascript:;">系統設置</a>
                            </a-menu-item>
                            <a-menu-item>
                                <inertia-link :href="route('master')">行政管理後臺</inertia-link>
                            </a-menu-item>
                            <a-menu-item @click="logout">
                                <a>登出</a>
                            </a-menu-item>
                        </a-menu>
                    </template>
                </a-dropdown>
            </a-layout-header>

            <a-layout-content>
                <header class="flex justify-between items-center bg-gray-200 m-4 py-4 px-6 mb-5 bg-white shadow sm:rounded-lg">
                <div class="text-lg font-bold">
                  {{ title }}
                </div>
                <nav class="text-sm" v-if="breadcrumb">
                  <ol class="list-none flex">
                    <li class="breadcrumb-item hidden md:inline" v-for="(item, idx) in breadcrumb">
                      <inertia-link v-if="item.url" :href="item.url">{{ item.label }}</inertia-link>
                      <span v-else>{{ item.label }}</span>
                    <span class="pl-2 pr-2" v-if="idx < breadcrumb.length-1">&gt;</span>
                    </li>
                    <li class="breadcrumb-item block md:hidden">
                      <span v-if="breadcrumb.length>1">
                        <inertia-link :href="breadcrumb[breadcrumb.length-2].url">
                          {{ breadcrumb[breadcrumb.length-2].label }}
                        </inertia-link>
                      </span>
                      <span v-else>
                        <inertia-link :href="route('manage')">
                          Home
                        </inertia-link>
                      </span>
                    </li>
                    <li>
                      <span class="pl-2 pr-2">|</span>
                      <a href="javascript:history.back();" class="inline">Back</a>
                    </li>
                  </ol>
                  
                </nav>
              </header>

                <div class="mx-6">
                    <main>
                        <slot />
                    </main>
                </div>

            </a-layout-content>
        </a-layout>
    </a-layout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import PageHeader from '@/Components/Department/PageHeader.vue';
import DepartmentMenu from '@/Components/Department/DepartmentMenu.vue';

import {
    MenuUnfoldOutlined,
    MenuFoldOutlined,
} from '@ant-design/icons-vue';


defineProps({
    title: String,
    department:Object,
    breadcrumb: Object,
});

const menuKeys = reactive({
    menuOpenKey: '',
    menuSelectKey: ''
})

const showingNavigationDropdown = ref(false);
const selectedKeys = ref(['1']);
const collapsed = ref(false);

const switchToTeam = (team) => {
    Inertia.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};
onMounted (() => {

});

const logout = () => {
    Inertia.post(route('manage.logout'));
};

</script>
  
<style>
#app .trigger {
    font-size: 18px;
    line-height: 64px;
    padding: 0 24px;
    cursor: pointer;
    transition: color 0.3s;
}

#app .trigger:hover {
    color: #1890ff;
}

#app .logo {
    height: 32px;
    background: rgba(255, 255, 255, 0.3);
    margin: 16px;
}

.site-layout .site-layout-background {
    background: #fff;
}
</style>
