<template>
    <a-list item-layout="vertical" size="large" :data-source="departments">
      <template #footer>
        <div>
          <b>ant design vue</b>
          footer part
        </div>
      </template>
      <template #renderItem="{ item }">
        <a-list-item key="item.title">
          <template #actions>
            <span v-for="{ type, text } in actions" :key="type">
              <component :is="type" style="margin-right: 8px" />
              {{ text }}
            </span>
          </template>
          <template #extra>
            <img
              width="272"
              alt="logo"
              src="https://gw.alipayobjects.com/zos/rmsportal/mqaQswcyDLcXyDKnZfES.png"
            />
          </template>
          <a-list-item-meta :description="item.description">
            <template #title>
                <inertia-link :href="'/manage/department/'+item.id+'/members'">{{ item.title }}</inertia-link>

            </template>
            <template #avatar><a-avatar :src="item.avatar" /></template>
          </a-list-item-meta>
          {{ item.content }}
          <br>
          <inertia-link :href="'/manage/department/'+item.id+'/members'">Members</inertia-link>
          <br>
          <inertia-link :href="'/manage/departments/'+item.id+'/edit'">Profile</inertia-link>
          <br>
          <inertia-link :href="'/manage/dashboard/'+item.id">Dashboard</inertia-link>
        </a-list-item>
      </template>
    </a-list>
  </template>

  <script>
  import { StarOutlined, LikeOutlined, MessageOutlined } from '@ant-design/icons-vue';
  import { defineComponent } from 'vue';

  const listData = [];
  for (let i = 0; i < 23; i++) {
    listData.push({
      href: 'https://www.antdv.com/',
      title: `ant design vue part ${i}`,
      avatar: 'https://joeschmoe.io/api/v1/random',
      description: 'Ant Design, a design language for background applications, is refined by Ant UED Team.',
      content: 'We supply a series of design principles, practical patterns and high quality design resources (Sketch and Axure), to help people create their product prototypes beautifully and efficiently.',
    });
  }
  export default defineComponent({
    components: {
      StarOutlined,
      LikeOutlined,
      MessageOutlined,
    },
    props: ['departments'],
    setup() {
      const pagination = {
        onChange: page => {
          console.log(page);
        },
        pageSize: 3,
      };
      const actions = [{
        type: 'StarOutlined',
        text: '156',
      }, {
        type: 'LikeOutlined',
        text: '156',
      }, {
        type: 'MessageOutlined',
        text: '2',
      }];
      return {
        listData,
        pagination,
        actions,
      };
    },
  });
  </script>