<template>
    <MemberLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $t('member_dashboard') }}
            </h2>
        </template>
        <div class="mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="lg:col-span-3">
                    <div class="container mx-auto pt-5" v-if="applications">
                        <div class="bg-white relative shadow rounded-lg pl-5">
                            <a-typography-title :level="4" class="pt-5">{{ $t('rec.recruitment') }}</a-typography-title>
                            <a-list item-layout="horizontal" :data-source="applications">
                                <template #renderItem="{ item }">
                                    <a-list-item>
                                        <a-list-item-meta>
                                            <template #title>
                                                <template v-if="item.vacancy.type=='ADM'">
                                                    <a :href="route('recruitment.admin.apply',{code:item.vacancy.code})" target="_blank">{{  item.vacancy.title_zh }}</a>
                                                </template>
                                                <template v-if="item.vacancy.type=='ACA'">
                                                    <a :href="route('recruitment.academic.apply',{code:item.vacancy.code})" target="_blank">{{  item.vacancy.title_zh }}</a>
                                                </template>
                                                
                                            </template>
                                            <template #description>
                                                <div><strong>遞交日期: </strong>{{  item.submited_at??'未遞交' }}</div>
                                                <template v-if="item.supplement_start">
                                                    <div><strong>補充交期限: </strong>{{ item.supplement_start }} to {{ item.supplement_end }}</div>
                                                    <div><strong>補充文件:</strong></div>
                                                </template>
                                                <div><strong>通告</strong></div>
                                                <ol>
                                                    <li v-for="notice in item.vacancy.notices">
                                                        {{ notice.date_start }} : {{ notice.title_zh }}
                                                    </li>
                                                </ol>
                                            </template>
                                            <template #avatar>
                                                <a-avatar>
                                                    <template #icon><EditOutlined /></template>
                                                </a-avatar>
                                            </template>
                                        </a-list-item-meta>
                                    </a-list-item>
                                </template>
                            </a-list>
                        </div>
                    </div>


                    <div class="container mx-auto pt-5" v-if="enquiries">
                        <div class="bg-white relative shadow rounded-lg pl-5">
                            ssssssssssss
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-2">
                    <div class="container mx-auto pt-5">
                        <div class="bg-white relative shadow rounded-lg">
                            <div class="p-5">
                                <a-avatar :size="64">
                                    <template #icon><UserOutlined /></template>
                                </a-avatar>
                            </div>

                            <div class="mt-2">
                                <h1 class="font-bold text-center text-3xl text-gray-900">Macao Polytechnic University</h1>
                                <p class="text-center text-sm text-gray-400 font-medium"> {{$page.props.user.name}} </p>
                                <div class="my-5 px-6">
                                    <a href="#"
                                        class="text-gray-200 block rounded-lg text-center font-medium leading-6 px-6 py-3 bg-gray-900 hover:bg-black hover:text-white">{{ $page.props.user.email }}</a>
                                </div>
                                <div class="flex justify-between items-center my-5 px-6">
                                    <a href="https://www.mpu.edu.mo"
                                        class="text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded transition duration-150 ease-in font-medium text-sm text-center w-full py-3">MPU</a>
                                    <a href="https://www.mpu.edu.mo/en/services.php"
                                        class="text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded transition duration-150 ease-in font-medium text-sm text-center w-full py-3">Service</a>
                                    <a :href="route('recruitment')"
                                        class="text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded transition duration-150 ease-in font-medium text-sm text-center w-full py-3">Vacancy</a>
                                    <a href="https://www.mpu.edu.mo/en/contact_us.php"
                                        class="text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded transition duration-150 ease-in font-medium text-sm text-center w-full py-3">Contact</a>
                                </div>
                                <div class="w-full">
                                    <h3 class="font-medium text-gray-900 text-left px-6">Message</h3>
                                    <div class="mt-5 w-full flex flex-col items-center overflow-hidden text-sm">
                                        <template v-for="message in messages">
                                            <a href="#"
                                                class="w-full border-t border-gray-100 text-gray-600 py-4 pl-6 pr-3 w-full block hover:bg-gray-100 transition duration-150">
                                                    <MessageOutlined class="mr-2"/>
                                                {{ message.title }}
                                                <span class="text-gray-500 text-xs"> {{ message.created_at }}</span>
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </MemberLayout>
</template>


<script setup>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import { UserOutlined, MessageOutlined, EditOutlined } from '@ant-design/icons-vue'

const data = [{
        title: 'Ant Design Title 1',
    }, {
        title: 'Ant Design Title 2',
    }, {
        title: 'Ant Design Title 3',
    }, {
        title: 'Ant Design Title 4',
}];

defineProps({
    applications:Object,
    entries:Object,
    messages:Object,
    enquiries:Object
});
</script>
