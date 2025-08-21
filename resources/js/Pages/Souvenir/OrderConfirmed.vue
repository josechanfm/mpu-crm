<template>
    <div>
        <form id="paymentForm" :action="paymentUrl" method="POST">
            <div v-for="(value, field) in paymentData" :key="field">
                {{ field }}:
                <input type="input" :name="field" :value="value">
            </div>
        </form>
    </div>
</template>

<script>
export default {
    props: {
        paymentData: Object,
        paymentUrl: String,
    },
    mounted() {
        this.$nextTick(() => {
            // Automatically submit the form
            document.getElementById('paymentForm').submit();
        });
    }
}
</script>