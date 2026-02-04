<template>
    <DepartmentLayout title="編輯員工記錄" :breadcrumb="breadcrumb">
        <div class="text-right pb-5">
            <inertia-link :href="route('personnel.staffs.index')" >
                <a-button type="primary" ghost>Back</a-button>
            </inertia-link>
        </div>
        <div class="mb-5">
            <a-card class="rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300" >
            <div class="p-6">
                <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ staff.name_zh }}</h3>
                <p class="text-gray-500">{{ staff.name_pt }}</p>
                </div>

                <div class="space-y-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <span class="text-blue-600 font-bold">#</span>
                    </div>
                    <div>
                    <p class="text-sm text-gray-500">員工編號</p>
                    <p class="font-semibold text-gray-900">{{ staff.staff_num }}</p>
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    </div>
                    <div>
                    <p class="text-sm text-gray-500">電郵地址</p>
                    <p class="font-semibold text-gray-900 break-all">{{ staff.email }}</p>
                    </div>
                </div>
                </div>
            </div>
            </a-card>
        </div>
        <div class="">
                <a-row  :gutter="16">
                    <a-col :span="12" :xs="24" :sm="24" :md="24" :xl="12">
                        <div class="p-1 bg-white rounded-lg shadow-lg">
                            <div class="flex items-center bg-yellow-300 p-2 rounded-t-lg">
                                <div class="relative">
                                    <template v-if="staff.avatar">
                                        <img :src="'/images/staffs/' + staff.avatar" alt="User Avatar"
                                            class="w-28 h-28 object-cover">
                                        </template>
                                    <template v-else>
                                        <div class="w-28 h-28 bg-white flex justify-center items-center">
                                            No Photo<br>未上載相片
                                        </div>
                                    </template>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-xl font-semibold mt-2">{{ staff.name_zh }}</h3>
                                    <h3 class="text-lg text-gray-600">{{ staff.name_pt }}</h3>
                                    <h4 class="text-md text-gray-500">{{ staff.netid }}</h4>
                                    <form>
                                        <div class="pt-5 flex gap-2">
                                            <a-button v-if="avatars[0].image" type="primary" @click="cardPrint(0)">Print</a-button>
                                            <a-button @click="openCropModel(0)">選擇相片</a-button>
                                            <a-button v-if="avatars[0].upload" @click="uploadAvatar(0)">上戴相片</a-button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap mt-4 mb-6">
                            <div class="w-full md:w-1/2 px-3">
                                <div v-if="avatars[0].upload" class="flex items-end">
                                    <img :src="avatars[0].upload" class="w-64 h-56 object-cover mr-2" alt="Avatar Preview" />
                                    <a class="text-red-500 cursor-pointer" @click="clearUploadAvatar(0)">
                                        <DeleteOutlined />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </a-col>
                    <a-col :span="12" :xs="24" :sm="24" :md="24" :xl="12">
                        <div v-for="(relative, i) in staff.relatives" class="mb-4">
                            <div class="p-1 bg-white rounded-lg shadow-lg">
                                <div class="flex items-center bg-teal-300 p-2 rounded-t-lg">
                                    <div class="relative">
                                        <template v-if="relative.avatar">
                                            <img :src="'/images/staffs/' + relative.avatar" alt="User Avatar"
                                                class="w-28 h-28 object-cover">
                                            </template>
                                        <template v-else>
                                            <div class="w-28 h-28 bg-white flex justify-center items-center">
                                                No Photo<br>未上載相片
                                            </div>
                                        </template>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-xl font-semibold mt-2">{{ relative.name_zh }}</h3>
                                        <h3 class="text-lg text-gray-600">{{ relative.name_pt }}</h3>
                                        <h4 class="text-md text-gray-500">{{ relative.netid }}</h4>
                                        <form>
                                            <div class="pt-5 flex gap-2">
                                                <a-button v-if="avatars[i+1].image" type="primary" @click="cardPrint(i+1)">Print</a-button>
                                                <a-button @click="openCropModel(i+1)">選擇相片</a-button>
                                                <a-button v-if="avatars[i+1].upload" @click="uploadAvatar(i+1)">上戴相片</a-button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        <div v-if="avatars[i+1].upload" class="flex flex-wrap mt-4 mb-6">
                            <div class="w-full md:w-1/2 px-3">
                                <div class="flex items-end">
                                    <img :src="avatars[i+1].upload" class="w-64 h-56 object-cover mr-2" alt="Avatar Preview" />
                                    <a class="text-red-500 cursor-pointer" @click="clearUploadAvatar(i+1)">
                                        <DeleteOutlined />
                                    </a>
                                </div>
                            </div>
                        </div>

                        </div>

                    </a-col>
                </a-row>

                            <CropperModal v-if="showCropModal" :minAspectRatioProp="{ width: 8, height: 8 }"
                                :maxAspectRatioProp="{ width: 8, height: 8 }" @croppedImageData="setCroppedImageData"
                                @showModal="closeCropModel()" />


        </div>
    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from "@/Layouts/DepartmentLayout.vue";
import { DeleteOutlined } from '@ant-design/icons-vue';
import CropperModal from "@/Components/CropperModal.vue";
import StaffEditor from './StaffEditor.vue'; // Importing the StaffEditor component

export default {
    components: {
        DepartmentLayout,
        DeleteOutlined,
        CropperModal,
        StaffEditor
    },
    props: {
        staff: Object,  // Assuming this is passed as a prop from Laravel
    },
    data() {
        return {
            breadcrumb: [
                { label: "人事處首頁", url: route('personnel.dashboard') },
                { label: "編輯員工記錄", url: null },
            ],
            showCropModal: false,
            avatarPreview: null,
            avatars:[],
            avatarSelected:null,
            rules: {
                name_zh: { required: true, message: '請輸入姓名(中文)' },
                staff_num: { required: true, message: '請輸入員工編號' },
                email: { required: true, type: 'email', message: '請輸入有效的電郵地址' },
            },
            validateMessages: {
                required: '${label} 是必填項！',
                types: {
                    email: '請輸入有效的 ${label}！',
                },
            },
        };
    },
    created(){
        this.avatars.push({model:'staff',id:this.staff.id, image:this.staff.avatar, upload:null, showCropModal:false})
        this.staff.relatives.forEach(r=>{
            this.avatars.push({model:'relative',id:r.id, image:r.avatar, upload:null, showCropModal:false});
        });
    },
    methods: {

        updateRecord() {
            this.$refs.editFormRef.validateFields().then(() => {
                this.$inertia.patch(route('personnel.staffs.update', this.staff.id), {
                    relation: 'staff',
                    avatar: this.avatarPreview
                }, {
                    onSuccess: (page) => {
                        console.log('Record updated successfully:', page);
                    },
                    onError: (err) => {
                        console.error('Error updating record:', err);
                    },
                });
            }).catch(err => {
                console.log('Validation error:', err);
            });
        },
        cancelEdit() {
            // Redirect back to the previous page or a specific route
            this.$inertia.visit(route('personnel.staffs.index'));
        },
        uploadAvatar(selectedId) {
            console.log('upload avatar', selectedId, this.avatars[selectedId])
            this.$inertia.post(route("personnel.staff.avatarUpload", this.staff.id), this.avatars[selectedId], {
                onSuccess: (page) => {
                    this.avatars[selectedId].image=page.avatar
                    console.log(page);
                    this.avatars[selectedId].upload=null
                },
                onError: (err) => {
                    console.log(err);
                },
            });
        },
        setCroppedImageData(data) {
            this.avatars[this.avatarSelected].upload=data.imageBase64
            this.avatarPreview = data.imageBase64;
            this.avatarData = data;
        },
        openCropModel(selectedId){
            this.avatarSelected=selectedId
            this.showCropModal=true
        },
        closeCropModel(){
            this.avatarSelected=null
            this.showCropModal=false
        },
        clearUploadAvatar(selectedId){
            this.avatars[selectedId].upload=null
        },
        cardPrint(selectedId){
            console.log(this.avatars[selectedId])
            const url = route("personnel.staff.cardPrint", this.staff.id);
                // Use window.location to trigger the PDF download
            window.location.href = `${url}?model=${this.avatars[selectedId].model}&id=${this.avatars[selectedId].id}`;

            // this.$inertia.get(route("personnel.staff.cardPrint", this.staff.id), this.avatars[selectedId], {
            //     onSuccess: (page) => {
            //         console.log(page);
            //     },
            //     onError: (err) => {
            //         console.log(err);
            //     },
            // });
        }

    },
};
</script>

<style scoped>
/* You can add your custom styles here */
</style>