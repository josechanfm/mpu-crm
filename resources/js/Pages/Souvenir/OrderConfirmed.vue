<template>
    <template #header>
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                預購畢業生限量專屬紀念品<br>
                Pre-order of Graduation Gifts
            </h2>
            <div class="flex justify-end gap-2">
                <div v-if="user" @click="logout">Logout<br>登出</div>
                <div v-else @click="login">Login<br>登入</div>
            </div>
        </div>
    </template>
    <div v-if="paymentData && Object.keys(paymentData).length">
        <form id="paymentForm" :action="paymentUrl" method="POST">
            <div v-for="(value, field) in paymentData" :key="field">
                {{ field }}:
                <input :name="field" :value="value">
            </div>
        </form>
    </div>
    <div v-else>
        Loading...
    </div>
</template>

<script>
export default {
    props: {
        paymentData: {
            type: Object,
            required: true,
            default: () => ({})
        },
        paymentUrl: {
            type: String,
            required: true
        }
    },
    mounted() {
        this.$nextTick(() => {
            // Automatically submit the form
            document.getElementById('paymentForm').submit();
        });
    }
}
</script>