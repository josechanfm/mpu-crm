<template>
    <MemberLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard..
            </h2>
        </template>
        <div class="mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="lg:col-span-3">
                    <div class="container mx-auto pt-5">
                        <div class="bg-white relative shadow rounded-lg pl-5">
                            <a-list item-layout="horizontal" :data-source="data">
                                <template #renderItem="{ item }">
                                    <a-list-item>
                                        <a-list-item-meta
                                            description="Ant Design, a design language for background applications, is refined by Ant UED Team">
                                            <template #title>
                                                <a href="https://www.antdv.com/">{{ item.title }}</a>
                                            </template>
                                            <template #avatar>
                                                <a-avatar src="https://joeschmoe.io/api/v1/random" />
                                            </template>
                                        </a-list-item-meta>
                                    </a-list-item>
                                </template>
                            </a-list>

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

                            <div class="mt-16">
                                <h1 class="font-bold text-center text-3xl text-gray-900">Macao Polytechnic University</h1>
                                <p class="text-center text-sm text-gray-400 font-medium"> member.display_name </p>
                                <p>
                                    <span>
                                        
                                    </span>
                                </p>
                                <div class="my-5 px-6">
                                    <a href="#"
                                        class="text-gray-200 block rounded-lg text-center font-medium leading-6 px-6 py-3 bg-gray-900 hover:bg-black hover:text-white">Connect
                                        with <span class="font-bold">@pantazisoft</span></a>
                                </div>
                                <div class="flex justify-between items-center my-5 px-6">
                                    <a href=""
                                        class="text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded transition duration-150 ease-in font-medium text-sm text-center w-full py-3">Facebook</a>
                                    <a href=""
                                        class="text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded transition duration-150 ease-in font-medium text-sm text-center w-full py-3">Twitter</a>
                                    <a href=""
                                        class="text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded transition duration-150 ease-in font-medium text-sm text-center w-full py-3">Instagram</a>
                                    <a href=""
                                        class="text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded transition duration-150 ease-in font-medium text-sm text-center w-full py-3">Email</a>
                                </div>

                                <div class="w-full">
                                    <h3 class="font-medium text-gray-900 text-left px-6">近況</h3>
                                    <div class="mt-5 w-full flex flex-col items-center overflow-hidden text-sm">
                                        <a href="#"
                                            class="w-full border-t border-gray-100 text-gray-600 py-4 pl-6 pr-3 w-full block hover:bg-gray-100 transition duration-150">
                                            <img src="https://avatars0.githubusercontent.com/u/35900628?v=4" alt=""
                                                class="rounded-full h-6 shadow-md inline-block mr-2">
                                            Updated his status
                                            <span class="text-gray-500 text-xs">24 min ago</span>
                                        </a>

                                        <a href="#"
                                            class="w-full border-t border-gray-100 text-gray-600 py-4 pl-6 pr-3 w-full block hover:bg-gray-100 transition duration-150">
                                            <img src="https://avatars0.githubusercontent.com/u/35900628?v=4" alt=""
                                                class="rounded-full h-6 shadow-md inline-block mr-2">
                                            Added new profile picture
                                            <span class="text-gray-500 text-xs">42 min ago</span>
                                        </a>

                                        <a href="#"
                                            class="w-full border-t border-gray-100 text-gray-600 py-4 pl-6 pr-3 w-full block hover:bg-gray-100 transition duration-150">
                                            <img src="https://avatars0.githubusercontent.com/u/35900628?v=4" alt=""
                                                class="rounded-full h-6 shadow-md inline-block mr-2">
                                            Posted new article in <span class="font-bold">#Web Dev</span>
                                            <span class="text-gray-500 text-xs">49 min ago</span>
                                        </a>

                                        <a href="#"
                                            class="w-full border-t border-gray-100 text-gray-600 py-4 pl-6 pr-3 w-full block hover:bg-gray-100 transition duration-150">
                                            <img src="https://avatars0.githubusercontent.com/u/35900628?v=4" alt=""
                                                class="rounded-full h-6 shadow-md inline-block mr-2">
                                            Edited website settings
                                            <span class="text-gray-500 text-xs">1 day ago</span>
                                        </a>

                                        <a href="#"
                                            class="w-full border-t border-gray-100 text-gray-600 py-4 pl-6 pr-3 w-full block hover:bg-gray-100 transition duration-150 overflow-hidden">
                                            <img src="https://avatars0.githubusercontent.com/u/35900628?v=4" alt=""
                                                class="rounded-full h-6 shadow-md inline-block mr-2">
                                            Added new rank
                                            <span class="text-gray-500 text-xs">5 days ago</span>
                                        </a>

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
import { UserOutlined } from '@ant-design/icons-vue'

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
    member:Object,
});
</script>
