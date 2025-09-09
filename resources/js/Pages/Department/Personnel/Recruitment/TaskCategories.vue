<template>
    <DepartmentLayout title="標準流程" :breadcrumb="breadcrumb">
        <div class="mx-auto pt-5">
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <ol>
                    <li v-for="t in vacancyTypes">
                        <inertia-link :href="route('personnel.recruitment.tasks.index',{vacancy_type:t.value})">{{ t.label }}</inertia-link>
                    </li>
                </ol>
            </div>
        </div>


    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from "@/Layouts/DepartmentLayout.vue";
import {
    UploadOutlined,
    LoadingOutlined,
    PlusOutlined,
    InfoCircleFilled,
} from "@ant-design/icons-vue";
import Icon, { RestFilled } from "@ant-design/icons-vue";
import { message } from "ant-design-vue";
import dayjs from 'dayjs';
import axios from "axios";

export default {
    components: {
        DepartmentLayout,
        UploadOutlined,
        LoadingOutlined,
        PlusOutlined,
        RestFilled,
        message,
        dayjs
    },
    props: ["departments","vacancyTypes"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"職位招聘" ,url:route('personnel.recruitment.vacancies.index')},
                {label:"標準流程" ,url:null},
            ],

        };
    },
    created() {

    },
    mounted() {
    }, 
    methods: {
    },
};
</script>
