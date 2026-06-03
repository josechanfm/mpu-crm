<template>
    <DepartmentLayout :title="mode === 'edit' ? 'Edit Event' : 'Create Event'">
        <div class="mx-auto pt-5">
            <!-- Back & Save buttons -->
            <div class="flex flex-justify justify-between pb-3 gap-5">
                <a-button :href="route('grp.invitation-events.index')">Back to Events</a-button>
                <a-button type="primary" @click="submitForm" :loading="submitting">
                    {{ mode === 'edit' ? 'Update Event' : 'Create Event' }}
                </a-button>
            </div>

            <!-- Event Form Card -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Event Information</h3>
                <a-form
                    ref="eventFormRef"
                    :model="form"
                    :rules="rules"
                    layout="vertical"
                >
                    <a-row :gutter="16">
                        <a-col :span="12">
                            <a-form-item label="Event Name" name="name" required>
                                <a-input type="input" v-model:value="form.name" placeholder="e.g., Annual Tech Conference" />
                            </a-form-item>
                        </a-col>
                        <a-col :span="12">
                            <a-form-item label="Venue" name="venue">
                                <a-input type="input" v-model:value="form.venue" placeholder="Moscone Center" />
                            </a-form-item>
                        </a-col>
                    </a-row>

                    <a-row :gutter="16">
                        <a-col :span="12">
                            <a-form-item label="Location" name="location">
                                <a-input type="input" v-model:value="form.location" placeholder="City, Country" />
                            </a-form-item>
                        </a-col>
                        <a-col :span="12">
                            <a-form-item label="Max Guests per Invitation" name="max_guests_per_invitation">
                                <a-input-number v-model:value="form.max_guests_per_invitation" :min="1" :max="20" />
                            </a-form-item>
                        </a-col>
                    </a-row>

                    <a-row :gutter="16">
                        <a-col :span="12">
                            <a-form-item label="Start Date" name="start_date" required>
                                <a-date-picker
                                    v-model:value="form.start_date"
                                    show-time
                                    format="YYYY-MM-DD HH:mm:ss"
                                    style="width: 100%"
                                />
                            </a-form-item>
                        </a-col>
                        <a-col :span="12">
                            <a-form-item label="End Date" name="end_date">
                                <a-date-picker
                                    v-model:value="form.end_date"
                                    show-time
                                    format="YYYY-MM-DD HH:mm:ss"
                                    style="width: 100%"
                                />
                            </a-form-item>
                        </a-col>
                    </a-row>

                    <a-row :gutter="16">
                        <a-col :span="12">
                            <a-form-item label="RSVP Deadline" name="rsvp_deadline">
                                <a-date-picker
                                    v-model:value="form.rsvp_deadline"
                                    show-time
                                    format="YYYY-MM-DD HH:mm:ss"
                                    style="width: 100%"
                                />
                            </a-form-item>
                        </a-col>
                        <a-col :span="12">
                            <a-form-item label="Status" name="is_active">
                                <a-switch v-model:checked="form.is_active" checked-children="Active" un-checked-children="Inactive" />
                            </a-form-item>
                        </a-col>
                    </a-row>

                    <a-form-item label="Description" name="description">
                        <a-textarea v-model:value="form.description" :rows="3" />
                    </a-form-item>

                    <a-form-item label="Plain Text Template (fallback)" name="email_template_html">
                    <div ref="editorWrapper" class="resizable-editor-wrapper">
                    <QuillEditor
                        v-model:content="form.email_template_html"
                        content-type="html"
                        :options="editorOptions"
                        theme="snow"
                        class="stretch-editor"
                        />
                    </div>
                    <div class="text-gray-500 text-sm mt-1">
                        Available placeholders: <code v-pre>{{guest_name}}</code>, <code v-pre>{{guest_organization}}</code>,
                        <code v-pre>{{event_name}}</code>, <code v-pre>{{event_date}}</code>,
                        <code v-pre>{{event_location}}</code>, <code v-pre>{{rsvp_link}}</code>,
                        <code v-pre>{{qr_code_image}}</code>
                    </div>
                    </a-form-item>

                </a-form>
            </div>

            <!-- Guest Management (only in edit mode) -->
            <InvitationGuests
                v-if="mode === 'edit' && eventId"
                :event="event"
                :max-guests-per-invitation="form.max_guests_per_invitation"
            />

        </div>
    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from "@/Layouts/DepartmentLayout.vue";
import { router } from "@inertiajs/vue3";
import { message, Modal } from "ant-design-vue";
import { UploadOutlined, PlusOutlined } from "@ant-design/icons-vue";
import dayjs from "dayjs";
import axios from "axios";
import InvitationGuests from './InvitationGuests.vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

export default {
    components: {
        DepartmentLayout,
        UploadOutlined,
        PlusOutlined,
        InvitationGuests,
        QuillEditor,   // <-- add this
    },
    props: {
        event: {
            type: Object,
            default: null,
        },
    },
    data() {
        return {
            mode: this.event && this.event.id ? "edit" : "create",
            eventId: this.event?.id || null,
            submitting: false,
            form: {
                name: "",
                description: "",
                start_date: null,
                end_date: null,
                location: "",
                venue: "",
                email_template_html: "",
                email_template_text: "",
                is_active: true,
                max_guests_per_invitation: 1,
                rsvp_deadline: null,
            },
            rules: {
                name: [{ required: true, message: "Event name is required" }],
                start_date: [{ required: true, message: "Start date is required" }],
            },
            editorOptions: {
                modules: {
                    toolbar: [
                        ['bold', 'italic'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['clean']
                    ]
                },
                placeholder: 'Dear {{guest_name}}...',
                theme: 'snow'
            },            
        };
    },
    created() {
        if (this.mode === "edit" && this.event) {
            // Populate form with event data
            this.form = {
                name: this.event.name || "",
                description: this.event.description || "",
                start_date: this.event.start_date ? dayjs(this.event.start_date) : null,
                end_date: this.event.end_date ? dayjs(this.event.end_date) : null,
                location: this.event.location || "",
                venue: this.event.venue || "",
                email_template_html: this.event.email_template_html || "",
                email_template_text: this.event.email_template_text || "",
                is_active: this.event.is_active ?? true,
                max_guests_per_invitation: this.event.max_guests_per_invitation || 1,
                rsvp_deadline: this.event.rsvp_deadline ? dayjs(this.event.rsvp_deadline) : null,
            };
        }
    },
    methods: {
        formatDate(dateStr) {
            return dateStr ? dayjs(dateStr).format("YYYY-MM-DD HH:mm") : "-";
        },
        getRsvpColor(status) {
            const map = { yes: "green", no: "red", maybe: "orange", pending: "default" };
            return map[status] || "default";
        },
        async submitForm() {
            try {
                await this.$refs.eventFormRef.validate();
                this.submitting = true;
                const url = this.mode === "edit"
                    ? route("grp.invitation-events.update", this.eventId)
                    : route("grp.invitation-events.store");
                const method = this.mode === "edit" ? "put" : "post";
                const data = { ...this.form };
                // Convert dayjs objects to ISO strings
                if (data.start_date) data.start_date = data.start_date.toISOString();
                if (data.end_date) data.end_date = data.end_date.toISOString();
                if (data.rsvp_deadline) data.rsvp_deadline = data.rsvp_deadline.toISOString();

                router[method](url, data, {
                    preserveState: false,
                    onSuccess: () => {
                        message.success(this.mode === "edit" ? "Event updated" : "Event created");
                        if (this.mode === "create") {
                            // Redirect to index or edit page
                            router.get(route("grp.invitation-events.index"));
                        }
                    },
                    onError: (errors) => {
                        console.error(errors);
                        message.error("Validation failed");
                    },
                    onFinish: () => { this.submitting = false; },
                });
            } catch (err) {
                console.log("Validation error", err);
                this.submitting = false;
            }
        },

        // ... existing methods (submitForm, etc.)
        initResizeObserver() {
            const wrapper = this.$refs.editorWrapper;
            if (!wrapper) return;

            const adjustEditorHeight = () => {
            const toolbar = wrapper.querySelector('.ql-toolbar');
            const container = wrapper.querySelector('.ql-container');
            if (toolbar && container) {
                const toolbarHeight = toolbar.offsetHeight;
                const wrapperHeight = wrapper.clientHeight;
                container.style.height = `${wrapperHeight - toolbarHeight}px`;
            }
            };

            // Initial adjustment
            adjustEditorHeight();

            // Observe size changes
            this.resizeObserver = new ResizeObserver(() => {
            adjustEditorHeight();
            });
            this.resizeObserver.observe(wrapper);
        },        
    },
};
</script>


<style scoped>
.resizable-editor-wrapper {
  resize: vertical;
  overflow: auto;
  min-height: 200px;
  max-height: 600px;
  border: 1px solid #d9d9d9;
  border-radius: 6px;
}
.resizable-editor-wrapper :deep(.ql-container) {
  border: none !important;
}

.resizable-editor-wrapper .stretch-editor {
  height: 100%;
}

.resizable-editor-wrapper :deep(.ql-container) {
  height: auto; /* let JS control it */
}
</style>