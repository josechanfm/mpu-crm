<template>
    <DepartmentLayout title="滙入用戶名單" >
        <div class="mx-auto pt-5">
            <div class="flex flex-justify justify-end pb-3 gap-5">
            </div>
            <div class="bg-white relative shadow rounded-lg overflow-x-auto">
                <div class="font-bold text-red-500 text-xl mt-10">錯過名單</div>
                <hr>
                <table width="100%">
                    <thead>
                        <tr>
                            <th>Netid</th>
                            <th>Name (zh)</th>
                            <th>Name (en)</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Faculty</th>
                            <th>Degree</th>
                            <th>Can Buy</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in records.wrongs" :key="row.id">
                            <td v-for="(value, key) in row" :key="key">
                                {{ value }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="font-bold text-green-500 text-xl mt-10">更新名單</div>
                <hr>
                <table width="100%">
                    <thead>
                        <tr>
                            <th>Netid</th>
                            <th>Name (zh)</th>
                            <th>Name (en)</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Faculty</th>
                            <th>Degree</th>
                            <th>Can Buy</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in records.updates" :key="row.id">
                            <td v-for="(value, key) in row" :key="key">
                                {{ value }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="font-bold text-blue-500 text-xl mt-10">正確名單</div>
                <hr>
                <table width="100%">
                    <thead>
                        <tr>
                            <th>Netid</th>
                            <th>Name (zh)</th>
                            <th>Name (en)</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Faculty</th>
                            <th>Degree</th>
                            <th>Can Buy</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in records.creates" :key="row.id">
                            <td v-for="(value, key) in row" :key="key">
                                {{ value }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="w-full flex justify-center p-10 gap-5">
                    <a-button :href="route('dae.souvenir.users.index')">Back</a-button>
                    <a-button type="primary" @click="confirmImport">Confirm Import</a-button>
                </div>
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
    DeleteOutlined,
    InfoCircleFilled,
} from "@ant-design/icons-vue";
import Icon, { RestFilled } from "@ant-design/icons-vue";
import { quillEditor, Quill } from "vue3-quill";
import { message } from "ant-design-vue";
import dayjs from 'dayjs';
import axios from "axios";

export default {
    components: {
        DepartmentLayout,
        UploadOutlined, LoadingOutlined, PlusOutlined, DeleteOutlined,
        RestFilled,
        quillEditor,
        message,
        dayjs
    },
    props: ["records"],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
                {label:"招聘流程" ,url:route('personnel.recruitment.workflows.index')},
                {label:"工作項目", url: null },
            ],
            dateFormat: "YYYY-MM-DD",
        };
    },
    created() {

    },
    mounted() {
    }, 
    computed: {
    },
    methods: {
        formatDate(dateString) {
            return dayjs(dateString).format('YYYY-MM-DD H:s');
        },
        confirmImport() {
            if (this.records && this.records.hasOwnProperty('wrongs')) {
                delete this.records.wrongs; // Remove 'wrongs' if it exists
            }
            // Append the records to the FormData object
            // const formData = new FormData();
            // formData.append('records', JSON.stringify(this.records)); // Convert records to JSON string
            // console.log('FormData to be sent:', formData.get('records'));
            // Send the FormData to the controller
            this.$inertia.post(route('dae.souvenir.user.import.confirm'), {records: this.records}, {
                onSuccess: (page) => {
                    console.log('Import confirmed successfully:', page);
                },
                onError: (errors) => {
                    console.error('Error confirming import:', errors);
                }
            });
            // Logic to confirm import
        }
    },
};
</script>

<style scoped>
table {
    border-collapse: collapse; /* Set border collapse */
    width: 100%; /* Optional: Set table width */
}

th,
td {
    border: 1px solid black; /* Set solid border */
    padding: 8px; /* Optional: Add padding for better spacing */
    text-align: left; /* Optional: Align text to the left */
}
</style>
