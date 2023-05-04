<template>
      <a-comment v-for="inquiry in inquiries">
        <template #author>
          <a>{{ inquiry.email }}</a>
        </template>
        <template #avatar>
          <a-avatar src="https://joeschmoe.io/api/v1/random" alt="Han Solo" />
        </template>
        <template #content>
          <Editor v-model="inquiry.content"/>
          <p>
            {{ inquiry.content }}  
          </p>
        </template>
        <template #actions>
          {{ inquiry.title }}
        </template>
        <p>
            Staff: {{ inquiry.admin_user.name }}
          </p>
          <a-button @click="$emit('editModal',inquiry)">Edit</a-button>

        <p v-if="inquiry.children">
            <CommentBox :inquiries="inquiry.children" @editModal="editRecord"/>
        </p>
      </a-comment>
</template>


<script>
import Editor from '@tinymce/tinymce-vue';

export default {
    components: {
      Editor
    },
    props: ['inquiries'],
    data() {
        return {

        }
    },
    methods: {
      editRecord(record){
        this.$emit('editModal',record)
      }
    },
}
</script>