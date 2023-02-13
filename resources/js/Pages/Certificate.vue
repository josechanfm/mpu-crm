<script>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import CertificateCard from '@/Pages/CertificateCard.vue';

export default {
    components: {
        MemberLayout,
        CertificateCard
    },

    data() {
        return{
            cardHolder:{
                'name':'Jose',
                'number':'4642 3489 9867 7632'
            },

        }
    }
}

</script>

<template>
    <MemberLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <CertificateCard title="Member Card" :holder="cardHolder">
                <template #description>
                    afasdf
                </template>
            </CertificateCard>
        </div>
        
    </MemberLayout>

    
</template>

