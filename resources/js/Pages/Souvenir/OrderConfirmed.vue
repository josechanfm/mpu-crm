<template>
    <div class="overlay">
      <div class="spinner"></div>
      <p class="text">Redirecting to payment gateway, please wait...</p>
    </div>
    <div v-if="paymentData && Object.keys(paymentData).length">
        <form id="paymentForm" :action="paymentUrl" method="POST"  style="display: none;">
            <div v-for="(value, field) in paymentData" :key="field">
                {{ field }}:<input :name="field" :value="value" type="hidden">
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