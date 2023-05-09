<template>
      <a-comment v-for="inquiry in inquiries">
        <template #author>
          {{ inquiry.email }},  {{ inquiry.phone }}
        </template>
        <template #avatar>
          <a-avatar src="https://joeschmoe.io/api/v1/random" alt="Han Solo" />
        </template>
        <template #content>
          <a-typography-title :level="5">{{ inquiry.title }}</a-typography-title>
          <p>{{ inquiry.content }} </p>  
        </template>
        <template #actions>
          <div>
            <div style="background-color: white;">
              <quill-editor v-model:value="inquiry.response" style="min-height:200px;"/>
            </div>
            
            <div>
            <a-button @click="updateInquiry(inquiry)">Save</a-button>
            <div class="float-right">
              <p> Staff: {{ inquiry.admin_user.name }}</p>
            </div>
          </div>

          </div>
          
        </template>

        <p v-if="inquiry.children">
            <CommentBox :inquiries="inquiry.children" />
        </p>
      </a-comment>
</template>


<script>
import Editor from '@tinymce/tinymce-vue';
import { quillEditor } from 'vue3-quill';

export default {
    components: {
      Editor,
      quillEditor
    },
    props: ['inquiries'],
    data() {
        return {

        }
    },
    methods: {
      updateInquiry(inquiry) {
        this.$inertia.patch(route('manage.department.inquiries.update',{department:inquiry.department_id,inquiry:inquiry.id}),inquiry,{
          onSuccess:(page)=>{
              console.log(page);
          },
          onError:(error)=>{
              console.log(error);
          }
      });
      }
    },
}
</script>