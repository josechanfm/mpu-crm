<template>
    <div>
        <a-sub-menu :key="menuInfo.key">
            <template #icon>
                <component :is="menuInfo.icon"></component>
            </template>
            <template #title>{{ menuInfo.title }}</template>
            <template v-for="item in menuInfo.children" :key="item.key">
                <template v-if="!item.children">
                    <a-menu-item :key="item.key" 
                        style="font-size:16px">
                        <template #icon>
                            <!-- <PieChartOutlined /> -->
                            <component :is="item.icon"></component>
                            <!-- {{ item.icon != "" ? item.icon : "" }} -->
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
    </div>
</template>
    
<script>

import { defineComponent, ref, watch } from 'vue';

import { Inertia } from '@inertiajs/inertia';
import SubMenu from './SubMenu.vue';
import * as AntdIcons from '@ant-design/icons-vue';

export default defineComponent({
  components: {
    ...AntdIcons
  },

  props: ['menuInfo'],
})
</script>