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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manual
            </h2>
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