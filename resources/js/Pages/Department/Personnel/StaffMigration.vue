<template>
    <DepartmentLayout title="Staff Info migration" :breadcrumb="breadcrumb">
        <div class="mx-auto pt-5">
            <h3 class="text-xl">員工卡及親屬醫療卡舊系統遷移及初始</h3>
            <ul>
                <li><a href="/personnel/staff/migration?action=fetch_remote_data">1. 由人事系統提取資料到本地資料庫</a></li>
                <li><a href="/personnel/staff/migration?action=copy_avatars">2. 抄staff_uploads文檔內容到staffs和staff_relatives</a></li>
                <li><a href="/personnel/staff/migration?action=create_notices">3. 生成需要發郵件的通知記錄</a></li>
                <li><a href="/personnel/staff/migration?action=staff&staff_num=0845">4. 以員工編號提取人事系統中的員工資料記錄</a></li>
            </ul>
            <p>
                第一次系統遷移,手工由wms滙出staffs和staffs_uploads, 注要需要在production上修改檔案名稱. staffs_uploads 改為, staff_uploads. 及staffs table上需要增加, avatars:varchar(255), uploaded_at:datetime, created_at:datetime, staff_uploads上加avatar:varchar(255)
            </p>
        </div>
    </DepartmentLayout>
</template>

<script>
import DepartmentLayout from "@/Layouts/DepartmentLayout.vue";
import {
} from "@ant-design/icons-vue";
import { message } from "ant-design-vue";
import dayjs from 'dayjs';

export default {
    components: {
        DepartmentLayout,
        message,
        dayjs
    },
    props: [],
    data() {
        return {
            breadcrumb:[
                {label:"人事處首頁" ,url:route('personnel.dashboard')},
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
