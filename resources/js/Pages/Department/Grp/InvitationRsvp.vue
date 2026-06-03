<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full space-y-8 bg-white p-8 rounded-lg shadow-lg">
            <!-- Event Header -->
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">{{ event.name }}</h2>
                <p class="mt-2 text-gray-600">{{ event.venue }}, {{ event.location }}</p>
                <p class="text-gray-600">
                    {{ formatDate(event.start_date) }} – {{ formatDate(event.end_date) }}
                </p>
            </div>

            <!-- Guest Greeting -->
            <div class="border-t pt-4">
                <p class="text-lg">Dear <strong>{{ guest.name }}</strong>,</p>
                <p class="mt-2">We would be honored to have you at our event. Please confirm your attendance below.</p>
            </div>

            <!-- RSVP Form -->
            <a-form :model="form" layout="vertical" @finish="submitRsvp">
                <a-form-item label="RSVP Status" required>
                    <a-radio-group v-model:value="form.rsvp_status" button-style="solid">
                        <a-radio-button value="yes">Yes, I'll attend</a-radio-button>
                        <a-radio-button value="maybe">Maybe</a-radio-button>
                        <a-radio-button value="no">No, I cannot attend</a-radio-button>
                    </a-radio-group>
                </a-form-item>
                <template v-if="form.rsvp_status !== 'no'">
                    <a-form-item label="Number of Guests (including yourself)" v-if="event.max_guests_per_invitation > 1">
                        <a-input-number 
                            v-model:value="form.guests_count" 
                            :min="1" 
                            :max="maxGuests" 
                            class="w-full"
                        />
                        <div class="text-gray-500 text-sm mt-1">Maximum {{ maxGuests }} guests per invitation.</div>
                    </a-form-item>

                    <a-form-item label="Dietary Needs / Allergies">
                        <a-textarea 
                            v-model:value="form.dietary_needs" 
                            :rows="2" 
                            placeholder="Please let us know about any dietary restrictions or allergies."
                        />
                    </a-form-item>
                </template>
                <a-form-item label="Additional Notes / Comments">
                    <a-textarea 
                        v-model:value="form.response_notes" 
                        :rows="2" 
                        placeholder="Any other information you'd like to share?"
                    />
                </a-form-item>
                <a-form-item>
                    <a-button type="primary" html-type="submit" :loading="submitting" block>
                        Submit RSVP
                    </a-button>
                </a-form-item>
            </a-form>

            <!-- Confirmation Modal -->
            <a-modal 
                v-model:open="confirmModal.visible" 
                :title="confirmModal.title"
                :footer="null"
                :closable="false"
            >
                <div class="text-center py-4">
                    <CheckCircleOutlined style="font-size: 48px; color: #52c41a;" />
                    <p class="mt-4 text-lg">{{ confirmModal.message }}</p>
                    <a-button type="primary" class="mt-6" @click="closeConfirmation">
                        Close
                    </a-button>
                </div>
            </a-modal>
        </div>
    </div>
</template>

<script>
import { message } from "ant-design-vue";
import { CheckCircleOutlined } from "@ant-design/icons-vue";
import dayjs from "dayjs";

export default {
    components: { CheckCircleOutlined },
    props: {
        guest: {
            type: Object,
            required: true,
        },
        event: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            submitting: false,
            form: {
                rsvp_status: this.guest.rsvp_status || "pending",
                guests_count: this.guest.guests_count || 1,
                dietary_needs: this.guest.dietary_needs || "",
                response_notes: this.guest.response_notes || "",
            },
            confirmModal: {
                visible: false,
                title: "",
                message: "",
            },
        };
    },
    computed: {
        maxGuests() {
            return this.event.max_guests_per_invitation || 20;
        },
    },
    methods: {
        formatDate(date) {
            return date ? dayjs(date).format("MMMM D, YYYY h:mm A") : "TBD";
        },
        async submitRsvp() {
            this.submitting = true;
            try {
                const response = await this.$inertia.post(
                    route("rsvp.update", { uuid: this.guest.uuid }),
                    this.form,
                    {
                        preserveState: false,
                        onSuccess: () => {
                            this.confirmModal.visible = true;
                            this.confirmModal.title = "RSVP Confirmed";
                            this.confirmModal.message = "Thank you for your response! We look forward to seeing you.";
                        },
                        onError: (errors) => {
                            message.error(errors.message || "Failed to submit RSVP. Please try again.");
                        },
                    }
                );
            } catch (error) {
                message.error("An unexpected error occurred.");
            } finally {
                this.submitting = false;
            }
        },
        closeConfirmation() {
            this.confirmModal.visible = false;
        },
    },
};
</script>