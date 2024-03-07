<template>
    <MemberLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5">
            <a-tabs v-model:activeKey="activeTabKey">
                <a-tab-pane key="1" tab="教職人員">
                    <a-collapse v-model:activeCollapseKey="activeKey" accordion>
                        <a-collapse-panel key="1" header="This is panel header 1">
                            <p> text </p>
                        </a-collapse-panel>
                        <a-collapse-panel key="2" header="This is panel header 2">
                            <p> text </p>
                        </a-collapse-panel>
                        <a-collapse-panel key="3" header="This is panel header 3">
                            <p> text </p>
                        </a-collapse-panel>
                    </a-collapse>
                </a-tab-pane>
                <a-tab-pane key="2" tab="非教職人員">
                    非教職人員
                </a-tab-pane>
            </a-tabs>
            </div>
        </div>
    </MemberLayout>

    
</template>

<script>
import MemberLayout from '@/Layouts/MemberLayout.vue';

export default {
    components: {
        MemberLayout,
    },
    props: ['vacancies'],
    data() {
        return{
            activeTabKey:'1',
            activeCollepseKey:'0'
        }
    }
}

</script>
