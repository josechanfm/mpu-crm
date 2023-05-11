<template>
  <a-comment v-for="inquiry in inquiries">
    <template #author>
      {{ inquiry.email }}, {{ inquiry.phone }}
    </template>
    <template #avatar>
      <a-avatar src="https://joeschmoe.io/api/v1/random" alt="Han Solo" />
    </template>
    <template #content>
      <a-typography-title :level="5">{{ inquiry.title }}</a-typography-title>
      <p>{{ inquiry.content }}</p>
    </template>
    <template #actions>
      <div>
        <div style="background-color: white;">
          <quill-editor v-model:value="inquiry.response" style="min-height:200px;" />
        </div>
        <p></p>
        <div>
          <a-button @click="updateInquiry(inquiry)">Save</a-button>
          <a-button @click="emailModal(inquiry)">Email</a-button>
          <div class="float-right">
            <p> Staff: {{ inquiry.admin_user.name }}</p>
          </div>
        </div>
        <a-collapse v-model:activeKey="activeKey">
            <a-collapse-panel key="1" :header="'You have '+inquiry.emails.length + ' emails'">
              <a-timeline>
                <a-timeline-item v-for="email in inquiry.emails">
                  {{ formatDate(email.created_at) }} {{ email.subject }}<br>
                  {{ email.content }}
                  <hr>
                  Attachments:<br>
                  <ul>
                        <li v-for="file in email.media">
                            <img :src="file.preview_url"/>
                            <a :href="file.original_url" target="_blank">{{ file.file_name }}</a>

                        </li>
                    </ul>

                </a-timeline-item>
              </a-timeline>
            </a-collapse-panel>
      </a-collapse>

      </div>

    </template>

    <p v-if="inquiry.children">
      <CommentCard :inquiries="inquiry.children" @emailBox="emailModal"/>
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
  emits:['emailBox'],
  props: ['inquiries'],
  data() {
    return {
      activeKey:0
    }
  },
  methods: {
    formatDate(dateString){
      const date = dayjs(dateString);
      return date.format('YYYY-MM-DD hh:ss');

    },
    updateInquiry(inquiry) {
      this.$inertia.patch(route('manage.department.inquiries.update', { department: inquiry.department_id, inquiry: inquiry.id }), inquiry, {
        onSuccess: (page) => {
          console.log(page);
        },
        onError: (error) => {
          console.log(error);
        }
      });
    },
    emailModal(record) {
      this.$emit('emailBox', record)
    }
  },
}
</script>