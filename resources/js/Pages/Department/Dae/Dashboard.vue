<template>
    <AdminLayout title="學生事務處" :department="department" :breadcrumb="breadcrumb">
        <a-typography-title :level="3">{{ department.name_zh }}</a-typography-title>
        <div class="container mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                Personnel department Dashboard
            </div>
            <div v-html="department.landing_page"></div>
            <div>
                <div>
                    <inertia-link :href="route('dae.souvenirs.index')" as="button">
                        <a-button>Souvenirs</a-button>
                    </inertia-link>
                </div>
                <div>
                    <inertia-link :href="route('dae.orders.index')">
                        <a-button>Order</a-button>
                    </inertia-link>
                </div>
                <div>
                    <inertia-link :href="route('dae.souvenir.pickup')">
                        <a-button>Pickup</a-button>
                    </inertia-link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';


export default {
    components: {
        AdminLayout,
    },
    props: ['department'],
    data() {
        return {
            breadcrumb:[
                {label:"學生事務處" ,url:null},
            ],

        }
    },
    created(){

    },
    mounted(){
    },
    methods: {

    },
}
</script>