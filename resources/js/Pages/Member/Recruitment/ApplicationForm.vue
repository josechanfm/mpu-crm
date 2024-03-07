<template>
    <MemberLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            
        </div>
    </MemberLayout>

    
</template>

<script>
import MemberLayout from '@/Layouts/MemberLayout.vue';

export default {
    components: {
        MemberLayout,
    },
    props: ['vacancy'],
    data() {
        return{

        }
    }
}

</script>
