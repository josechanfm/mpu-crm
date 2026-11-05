<template>
    <BlankLayout title="Confirmation / 確認">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{form.title}}
            </h2>
        </template>
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-8 p-4 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg ">
                <div v-if="form.settings.receipt_num">
                    <p>Your application reference is / 您的申請參考編號是：{{ entry.uid }}</p>
                </div>

                <div v-if="form.show_receipt">
                    <a-button :href="route('form.entry.receipt',{entry:entry.id, uuid:entry.uuid})">
                        Print Confirmation / 列印確認
                    </a-button>
                </div>
                <div v-if="form.thanks">
                    <div id="pure-html" v-html="form.thanks"></div>
                </div>
                <div v-else>
                    <p>Thank you very much! / 非常感謝您！</p>
                </div>

            </div>
        </div>
    </BlankLayout>

</template>

<script>
import BlankLayout from '@/Layouts/BlankLayout.vue';

export default {
    components: {
        BlankLayout,
    },
    props: ['form','entry'],
    data() {
        return {
        }
    },
    created(){
    },
    methods: {
    },
}
</script>

<style>
#pure-html {
  all: initial
}

#pure-html * {
  all: revert;
}
</style>