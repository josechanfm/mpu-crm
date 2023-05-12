<template>
    <a-comment v-for="inquiry in inquiries" class="border-sollid border-1 border-indigo-600">
      <template #avatar>
        <a-avatar src="https://joeschmoe.io/api/v1/random" alt="Han Solo" />
      </template>
      <template #author>
        {{ inquiry.email }}, {{ inquiry.phone }}
      </template>
      <template #datetime >
          Created: at: {{ inquiry.created_at }}
          Responsed at: {{ inquiry.response_date }}
      </template>
      <template #content>
        <a-typography-title :level="5">{{ inquiry.title }}</a-typography-title>
        <p>{{ inquiry.content }}</p>
      </template>
      <div style="background-color: white;" v-html="inquiry.response" class="py-1 my-2"></div>
      <div class="my-2">
        <a-button @click="emailModal(inquiry)">Email</a-button>
        <a-button @click="inquiryModal(inquiry)">Edit Inquiry</a-button>
        <a-button @click="inquiryFollowupModal(inquiry)">Followup Inquiry</a-button>
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
      <p v-if="inquiry.children">
        <CommentCard :inquiries="inquiry.children" @emailBox="emailModal" @inquiryBox="inquiryModal"/>
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
  emits:['emailBox','inquiryBox'],
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
      this.$inertia.patch(
        route('manage.department.inquiries.update', 
          { 
            department: inquiry.department_id, inquiry: inquiry.id 
          }
        ), 
        inquiry, {
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
    inquiryModal(record) {
      this.$emit('inquiryBox', record)
    },
    inquiryFollowupModal(record) {
      record.parent_id=record.id;
      record.id=null;
      this.$emit('inquiryBox', record)
    }
  },
}
</script>