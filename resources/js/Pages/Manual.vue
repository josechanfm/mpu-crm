<script setup>
import BlankLayout from '@/Layouts/BlankLayout.vue';
import DeleteTeamForm from '@/Pages/Teams/Partials/DeleteTeamForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TeamMemberManager from '@/Pages/Teams/Partials/TeamMemberManager.vue';
import UpdateTeamNameForm from '@/Pages/Teams/Partials/UpdateTeamNameForm.vue';
import { marked } from 'marked';

defineProps({
    manual:Object
});
const markdown = (content) => {
    return marked.parse(content)
}

</script>

<template>
    <BlankLayout title="manual">
    <template #header>
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ manual.title }}
            </h2>
            <div v-if="manual.parent" class="text-right">
                <a :href="'/manual?route=' + manual.parent.route" class="text-blue-500 hover:underline">反回</a>
            </div>
        </div>
    </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div id="pure-html" v-html="manual.content"/>
            </div>
        </div>
    </BlankLayout>
</template>
<style scope>
#pure-html {
  all: initial
}

#pure-html * {
  all: revert;
}
</style>