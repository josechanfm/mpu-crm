<template>
    <StaffLayout title="Dashboard">
    <div class="h-64 bg-gradient-to-tr from-green-800 to-green-500 rounded-md flex items-center">
      <div class="ml-2 lg:ml-20">
        <h2 class="text-white text-4xl">General Staff</h2>
        <p class="text-white mt-4 capitalize tracking-wider leading-7">Staff Landing page, which shows general information
          for both Administration and Teaching staff.</p>
        <a href="#"
          class="uppercase inline-block mt-8 text-sm bg-white py-2 px-4 rounded font-semibold hover:bg-indigo-100">Comming
          soon..!.</a>
      </div>
    </div>
    
    <!-- Added type="flex" and align="stretch" to ensure equal column heights -->
    <a-row :gutter="[16, 16]" type="flex" align="stretch" class="pt-5">
        <a-col v-for="(card, index) in cardItems" :key="index" :xs="24" :sm="12" :md="8" class="flex">
            <a-card
                :title="card.title"
                class="rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col justify-between w-full"
                :style="{ padding: '16px' }"
            >
                <div class="card-content flex-grow">
                    <p v-if="card.showEmail"><strong>Your email:</strong> {{ $page.props.currentUser.email }}</p>
                    <p>{{ card.description }}</p>
                    <p v-if="card.remark" class="text-xs text-amber-600 mt-2">
                        {{ card.remark }}
                    </p>
                </div>
                <div class="flex justify-end mt-4">
                  <inertia-link :href="card.route">
                    <a-button type="primary">{{ card.buttonText }}</a-button>
                  </inertia-link>
                </div>
            </a-card>
        </a-col>
    </a-row>

    <a-row class="pt-5" type="flex" align="stretch">
      <template v-for="form in forms" :key="form.id">
        <a-col :xs="{span: 24}" :lg="{span: 8}" class="flex">
          <a-card :title="form.title" :bordered="false" class="w-full flex flex-col justify-between">
            <template #extra>
              <inertia-link :href="route('staff.forms.show', { form: form.id })" type="primary">
                Apply
              </inertia-link>
            </template>
            <div class="flex items-start flex-grow">
              <img v-if="form.thumbnail" :src="form.thumbnail" alt="Thumbnail" class="w-1/3 mr-4" />
              <div class="flex-1">
                <div v-html="form.description" />
              </div>
            </div>
          </a-card>
        </a-col>
      </template>
    </a-row>
    
    <div v-if="$page.props.currentUser.roles.includes('master')">
      <a :href="route('master')">Master Dashboard</a>
    </div>

    <div v-if="$page.props.currentUser.roles.includes('admin')">
      <ol>
        <li v-for="department in departments" :key="department.id">
          <a :href="route(department.default_route)">
            {{ department.abbr }} - {{ department.name_zh }}
          </a>
        </li>
      </ol>
      <p class="text-red-500">This list only show for admin role.</p>
    </div>
  </StaffLayout>
</template>

<script>
import { router as Inertia } from '@inertiajs/vue3';
import StaffLayout from '@/Layouts/StaffLayout.vue';

export default {
    components: {
        StaffLayout,
    },
    props: ['departments', 'forms'],
    data() {
        return {
            cardItems: [
                {
                    title: 'Staff Cards',
                    description: 'Staff Card and Family Medical Card photo upload',
                    showEmail: true,
                    route: this.route('staff.cards'),
                    buttonText: 'Go to Service',
                },
                {
                    title: '工作備忘',
                    description: '用來記錄與管理個人工作備忘，整理待辦事項與執行重點，讓任務處理更有條理。',
                    showEmail: false,
                    route: this.route('staff.tasks.index', { status: 'pending' }),
                    buttonText: 'My Tasks',
                },
                {
                    title: '二維碼生成器',
                    description: '輸入網址，可選標題與 Logo，即時生成專屬二維碼，並支援複製與下載 PNG。',
                    showEmail: false,
                    route: this.route('widget.genQrcode', { status: 'pending' }),
                    buttonText: '去生成',
                },
                {
                    title: '短網址生成器',
                    description: '輸入長網址即時生成簡短短網址，支援一鍵複製與點擊數據追蹤。',
                    showEmail: false,
                    route: this.route('manage.shorteners.index'),
                    buttonText: '去生成',
                },
                {
                    title: '電子書生成器',
                    description: '上傳文件或編輯內容即時轉換生成電子書，支援多格式匯出與線上瀏覽。',
                    remark: '* 註：僅限已授權之管理員使用此功能。',
                    showEmail: false,
                    route: this.route('manage.ebooks.index'),
                    buttonText: '去生成',
                },
            ],
        };
    },
    methods: {},
};
</script>