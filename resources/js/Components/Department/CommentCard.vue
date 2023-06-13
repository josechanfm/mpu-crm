<template>
  <a-comment v-for="enquiry in enquiries" :style="'background-color:' + (enquiry.id == selected.id ? 'oldlace' : '')" class="pb-3 p-5">
    <template #avatar>
      <a-avatar src="https://joeschmoe.io/api/v1/random" alt="Han Solo" />
    </template>
    <template #author>
      <div>
        {{ enquiry.email }}, {{ enquiry.phone }}
      </div>

    </template>
    <template #datetime>
      Created: at: {{ enquiry.created_at }}
      Responsed at: {{ enquiry.response_date }}
    </template>
    <template #content>
      <a-typography-title :level="5">{{ enquiry.title }}</a-typography-title>
      <p>{{ enquiry.content }}</p>
    </template>
    <div v-html="enquiry.response" class="bg-white py-1 my-2"></div>
    <div class="my-2">
      <a-button @click="emailModal(enquiry)">Email</a-button>
      <a-button @click="enquiryModal(enquiry)">Edit Enquiry</a-button>
      <a-button @click="enquiryFollowupModal(enquiry)">Followup Enquiry</a-button>
      <div class="float-right">
        <p> Staff: {{ enquiry.admin_user.name }}</p>
      </div>
    </div>
    <div>
      <a-collapse v-model:activeKey="activeKey">
        <a-collapse-panel key="1" :header="'You have ' + enquiry.emails.length + ' emails'">
          <a-timeline>
            <a-timeline-item v-for="email in enquiry.emails">
              {{ formatDate(email.created_at) }} {{ email.subject }}<br>
              {{ email.content }}
              <hr>
              Attachments:<br>
              <ul>
                <li v-for="file in email.media">
                  <img :src="file.preview_url" />
                  <a :href="file.original_url" target="_blank">{{ file.file_name }}</a>
                </li>
              </ul>

            </a-timeline-item>
          </a-timeline>
        </a-collapse-panel>
      </a-collapse>

    </div>
    <p v-if="enquiry.children">
      <CommentCard :enquiries="enquiry.children" :selected="selected" @emailBox="emailModal" @enquiryBox="enquiryModal" />
    </p>

  </a-comment>
</template>


<script>
import Editor from '@tinymce/tinymce-vue';
import { quillEditor } from 'vue3-quill';
import dayjs from 'dayjs';

export default {
  components: {
    Editor,
    quillEditor,
    dayjs,
  },
  emits: ['emailBox', 'enquiryBox'],
  props: ['enquiries','selected'],
  data() {
    return {
      activeKey: 0
    }
  },
  methods: {
    formatDate(dateString) {
      const date = dayjs(dateString);
      return date.format('YYYY-MM-DD hh:ss');

    },
    updateEnquiry(enquiry) {
      this.$inertia.patch(
        route('manage.department.enquiries.update',
          {
            department: enquiry.department_id, enquiry: enquiry.id
          }
        ),
        enquiry, {
        onSuccess: (page) => {
          console.log(page);
        },
        onError: (error) => {
          console.log(error);
        }
      }
      );
    },
    emailModal(record) {
      this.$emit('emailBox', record)
    },
    enquiryModal(record) {
      this.$emit('enquiryBox', record)
    },
    enquiryFollowupModal(record) {
      record.parent_id = record.id;
      record.id = null;
      this.$emit('enquiryBox', record)
    }
  },
}
</script>