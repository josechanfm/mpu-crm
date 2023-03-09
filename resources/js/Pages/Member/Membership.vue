<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard..
            </h2>
        </template>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <MembershipCard v-for="card in cardHolder" title="Professional Certificate" :card="card">
                        <template #description>
                            <div v-html="card.description"></div>
                        </template>
                    </MembershipCard>
                </div>
            </div>
        </div>
    </AppLayout>
</template>


<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import MembershipCard from '@/Components/Member/MembershipCard.vue';

export default {
    components: {
        AppLayout,
        MembershipCard,
    },
    props: ['member'],
    data() {
        return {
            cardHolder:[{
                'organization':'澳門柔道協會會員',
                'title':'裁判資格認證',
                'name':'謝廣漢',
                'number':'0001',
                'rank_caption':'段數',
                'rank':'1',
                'face':'/storage/card1.png',
                'description':'澳門柔道總會裁判資格認證<br><ul><li>aaa</li></ul>'
            },{
                'organization':'澳門柔道協會教練員',
                'title':'教官資格認證',
                'name':'謝廣漢',
                'number':'0012',
                'rank_caption':'級別',//'段數',
                'rank':'1',
                'face':'/storage/card2.png',
                'description':'aaaaaaaaaaaaaaa'
            }],

        }
    },
    created() {
    },
    methods: {

    },
}
</script>