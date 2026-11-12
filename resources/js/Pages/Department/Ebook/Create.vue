<template>
    <WebLayout title="新增電子書">
        <div class="mx-auto max-w-xl px-4 py-8">
            <div class="mb-5">
                <h1 class="text-xl font-semibold text-gray-800">新增電子書</h1>
                <p class="mt-1 text-sm text-gray-500">上傳 PDF，產生翻頁書與二維碼</p>
            </div>
            <div class="rounded-lg bg-white p-6 shadow">
                <a-form layout="vertical" @submit.prevent="submit">
                    <a-form-item label="書籍名稱" required>
                        <a-input type="input" v-model:value="pdfFile.title" placeholder="電子書標題" />
                    </a-form-item>
                    <a-form-item label="二維碼標題">
                        <a-input type="input" v-model:value="pdfFile.qrcode_title" placeholder="印在二維碼下方的文字" />
                        <div class="mt-1 text-xs text-gray-400">留空時使用書籍名稱</div>
                    </a-form-item>
                    <a-form-item label="說明">
                        <a-textarea v-model:value="pdfFile.description" :rows="3" placeholder="簡短說明" />
                    </a-form-item>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <a-form-item label="開始日期">
                            <a-date-picker v-model:value="pdfFile.date_start" value-format="YYYY-MM-DD" class="w-full" />
                        </a-form-item>
                        <a-form-item label="結束日期">
                            <a-date-picker v-model:value="pdfFile.date_end" value-format="YYYY-MM-DD" class="w-full" />
                        </a-form-item>
                    </div>
                    <a-form-item label="PDF 檔案" required>
                        <a-upload accept=".pdf" :before-upload="beforeUpload" :show-upload-list="false">
                            <a-button>
                                <UploadOutlined /> {{ pdfName ? '更換 PDF' : '選擇 PDF' }}
                            </a-button>
                        </a-upload>
                        <div v-if="pdfName" class="mt-2 text-sm text-gray-600">{{ pdfName }}</div>
                        <div class="mt-1 text-xs text-gray-400">PDF，最大 20MB</div>
                    </a-form-item>
                    <a-form-item label="發佈">
                        <a-switch v-model:checked="pdfFile.published" />
                    </a-form-item>
                    <a-button type="primary" html-type="submit" :loading="loading">建立</a-button>
                </a-form>
            </div>
        </div>
    </WebLayout>
</template>

<script>
import WebLayout from "@/Layouts/WebLayout.vue";
import { message } from 'ant-design-vue';
import { UploadOutlined } from '@ant-design/icons-vue';

export default {
    components: {
        WebLayout,
        UploadOutlined,
    },
    data() {
        return {
            loading: false,
            pdfFile: {
                title: '',
                qrcode_title: '',
                description: '',
                date_start: null,
                date_end: null,
                published: false,
                file: null,
            },
        };
    },
    computed: {
        pdfName() {
            const files = this.pdfFile.file;
            if (!files || !files.length) return '';
            return files[files.length - 1].name || '';
        },
    },
    methods: {
        beforeUpload(file) {
            const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
            if (!isPdf) {
                message.error('只可上傳 PDF');
                return false;
            }
            if (file.size / 1024 / 1024 >= 20) {
                message.error('PDF 需小於 20MB');
                return false;
            }
            this.pdfFile.file = [file];
            return false;
        },
        submit() {
            if (!this.pdfFile.title) {
                message.error('請填寫書籍名稱');
                return;
            }
            if (!this.pdfFile.file) {
                message.error('請選擇 PDF');
                return;
            }
            this.loading = true;
            this.$inertia.post(route('manage.ebooks.store'), this.pdfFile, {
                forceFormData: true,
                onFinish: () => {
                    this.loading = false;
                },
            });
        },
    },
};
</script>
