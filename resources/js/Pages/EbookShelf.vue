<script setup>
import BlankLayout from '@/Layouts/BlankLayout.vue';
import DeleteTeamForm from '@/Pages/Teams/Partials/DeleteTeamForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TeamMemberManager from '@/Pages/Teams/Partials/TeamMemberManager.vue';
import UpdateTeamNameForm from '@/Pages/Teams/Partials/UpdateTeamNameForm.vue';
import { marked } from 'marked';

defineProps({
    ebooks:Object
});

</script>

<template>
    <BlankLayout title="manual">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ebooks
            </h2>
        </template>

        <div>
    <div class=" py-10 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 items-end">
            <div v-for="ebook in ebooks" class="flex flex-col justify-end">
                <a :href="'/flipbooks/'+ebook.uid" target="_blank">
                    <img 
                        :src="'flipbooks/'+ebook.uid+'/files/thumb/1.jpg'" 
                        alt="Ebook Cover" 
                        class="w-full h-auto max-h-48 object-contain object-bottom rounded shadow-lg" 
                    />
                </a>
            </div>
        </div>
    </div>
</div>


    </BlankLayout>
</template>
<style scope>

</style>