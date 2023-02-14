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
            cardHolder:[{
                'title':'澳門柔道協會會員',
                'name':'謝廣漢',
                'number':'0001',
                'dan':'7',
                'face':'/storage/card1.png'
            },{
                'title':'澳門柔道協會教練員',
                'name':'謝廣漢',
                'number':'0012',
                'face':'/storage/card2.png'
            }],

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
            <CertificateCard v-for="card in cardHolder" title="Member Card" :card="card">
                <template #description>
                    afasdf
                </template>
            </CertificateCard>
        </div>
        
    </MemberLayout>

    
</template>

