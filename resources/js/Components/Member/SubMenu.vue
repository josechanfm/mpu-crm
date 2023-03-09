<template>
    <a-sub-menu :key="filteredMenuInfo.key" v-if="hasChildrenWithPermission">
        <template #icon v-if="filteredMenuInfo.icon">
            <component :is="filteredMenuInfo.icon" />
        </template>
        <template #title>{{ $t(filteredMenuInfo.title) }}</template>
        <template v-for="item in filteredMenuInfo.children" :key="item.key">
            <template v-if="!item.children">
                <a-menu-item :key="item.key">
                    <template #icon v-if="item.icon">
                        <component :is="item.icon" />
                    </template>
                    <inertia-link :href="route(item.route)">
                        {{ $t(item.title) }}
                    </inertia-link>
                </a-menu-item>
            </template>
            <template v-else>
                <sub-menu :menu-info="item" :key="item.key" />
            </template>
        </template>
    </a-sub-menu>
</template>

<script>
import * as AntdIcons from '@ant-design/icons-vue';
export default {
    name: "SubMenu",
    components: {
        ...AntdIcons,
    },
    props: {
        menuInfo: {
            type: Object,
            default: () => ({}),
        },
    },
    computed: {
        hasChildrenWithPermission () {
            return !this.menuInfo.permission || this.filteredMenuInfo.children.length > 0;
        },
        filteredMenuInfo () {
            const permissions = this.$page.props.currentUserPermissions
            return {
                ...this.menuInfo,
                children: this.menuInfo.children.filter(item => {
                    return !item.permission || permissions.includes(item.permission)
                }),
            }
        },
    },
}
</script>

<style scoped>
</style>