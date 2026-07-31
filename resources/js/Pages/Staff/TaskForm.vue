<!-- resources/js/Pages/Staff/TaskForm.vue -->
<template>
  <StaffLayout>
  <div class="m-0 lg:m-5">
    <div class="mb-5">
      <a-button @click="goBack" class="back-button">
        <ArrowLeftOutlined />
        返回
      </a-button>
      <h1 class="form-title">{{ formTitle }}</h1>
    </div>
    <a-card :body-style="isMobile ? { padding: '5px' } : { padding: '10px' }">
      <a-form
        :model="form"
        :rules="rules"
        layout="vertical"
        ref="formRef"
        @finish="handleSubmit"
      >
        <a-row :gutter="24">
          <a-col :span="12">
            <a-form-item label="標題" name="title" required>
              <a-input
                type="input"
                v-model:value="form.title"
                placeholder="Enter task title"
                :disabled="readonly"
              />
            </a-form-item>
          </a-col>

          <a-col :span="12">
            <a-form-item label="狀態" name="status" required>
              <a-select
                v-model:value="form.status"
                placeholder="Select status"
                :disabled="readonly"
              >
                <a-select-option value="pending">待處理</a-select-option>
                <a-select-option value="in_progress">進行中</a-select-option>
                <a-select-option value="completed">已完成</a-select-option>
                <a-select-option value="cancelled">已取消</a-select-option>
              </a-select>
            </a-form-item>
          </a-col>
          <a-col :span="12" v-if="departments.length>1">
            <a-form-item label="部門" name="department_id">
              <a-select
                v-model:value="form.department_id"
                placeholder="Select department"
                :disabled="readonly"
                allow-clear
                show-search
                :filter-option="filterOption"
              >
                <a-select-option
                  v-for="dept in departments"
                  :key="dept.id"
                  :value="dept.id"
                >
                  {{ dept.name_zh }}
                </a-select-option>
              </a-select>
            </a-form-item>
          </a-col>

          <a-col :span="24">
            <a-form-item label="簡介" name="description">
              <a-textarea
                v-model:value="form.description"
                placeholder="Enter task description"
                :disabled="readonly"
                :rows="5"
              />
            </a-form-item>
          </a-col>

          <a-col :span="24">
            <a-form-item label="處理" name="action">
              <a-textarea
                v-model:value="form.action"
                placeholder="Enter action items"
                :disabled="readonly"
                :rows="5"
              />
            </a-form-item>
          </a-col>
          <a-col :span="24">
            <a-form-item label="結果" name="result">
              <a-textarea
                v-model:value="form.result"
                placeholder="Enter results"
                :disabled="readonly"
                :rows="5"
              />
            </a-form-item>
          </a-col>


            <!-- ============================================ -->
            <!-- REUSABLE FILE UPLOAD COMPONENT -->
            <!-- ============================================ -->
            <a-col :span="24">
              <a-form-item label="文檔" name="files">
                <FileUpload
                  ref="fileUploadRef"
                  :existing-files="form.existing_files"
                  :readonly="readonly"
                  :max-files="10"
                  :multiple="true"
                  upload-button-text="上傳檔案"
                  help-text="最多 10 個檔案。支援格式：圖片 (JPG, PNG, GIF, WebP)、PDF、Word、Excel。每個檔案最大 10MB"
                  download-route="staff.task.download-file"
                  set-primary-route="staff.task.set-primary-file"
                  @update:existing-files="handleFilesUpdate"
                  @primary-set="handlePrimarySet"
                  @files-change="handleFilesChange"
                />
              </a-form-item>
            </a-col>
            <!-- ============================================ -->
            <!-- END FILE UPLOAD COMPONENT -->
            <!-- ============================================ -->
             
            
        </a-row>

        <div v-if="!readonly" class="form-actions">
          <a-space>
            <a-button @click="goBack">返回</a-button>
            <a-button type="primary" html-type="submit" :loading="submitting">
              <SaveOutlined />
              {{ isEditing ? '修改' : '新增' }} 備忘
            </a-button>
          </a-space>
        </div>

        <div v-else class="form-actions">
          <a-button type="primary" @click="editTask">
            <EditOutlined />
            修改
          </a-button>
        </div>
      </a-form>
    </a-card>
  </div>
  </StaffLayout>
</template>

<script setup>
import StaffLayout from "@/Layouts/StaffLayout.vue";
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { message } from 'ant-design-vue';
import {
  ArrowLeftOutlined,
  SaveOutlined,
  EditOutlined,
} from '@ant-design/icons-vue';
import FileUpload from '@/Components/FileUpload.vue';

const props = defineProps({
  task: Object,
  departments: Array,
  users: Array,
  readonly: Boolean,
});

const formRef = ref();
const fileUploadRef = ref(null);
const submitting = ref(false);
const page = usePage();

const isEditing = computed(() => !!props.task?.id);
const isMobile = ref(window.innerWidth < 768);

const formTitle = computed(() => {
  if (props.readonly) return '備忘內容';
  return isEditing.value ? '修改備忘' : '新增備忘';
});

const form = reactive({
  title: props.task?.title || '',
  description: props.task?.description || '',
  action: props.task?.action || '',
  result: props.task?.result || '',
  status: props.task?.status || 'pending',
  user_id: props.task?.user_id || '',
  department_id: props.task?.department_id || null,
  // File state handled via @files-change event
  existing_files: props.task?.files || [],
  new_files: [],
  delete_files: [],  
});

const rules = {
  title: [
    { required: true, message: 'Please enter task title', trigger: 'blur' },
  ],
  status: [
    { required: true, message: 'Please select status', trigger: 'change' },
  ],
  user_id: [
    { required: true, message: 'Please select a user', trigger: 'change' },
  ],
};

const filterOption = (input, option) => {
  return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
};

const goBack = () => {
  router.get(route('staff.tasks.index'));
};

const editTask = () => {
  router.get(route('staff.tasks.edit', props.task.id));
};

// ============================================
// FILE UPLOAD EVENT HANDLERS
// ============================================

const handleFilesUpdate = (updatedFiles) => {
  form.existing_files = updatedFiles;
};

const handlePrimarySet = (fileId) => {
  router.post(
    route('staff.tasks.set-primary-file', fileId),
    { is_primary: true },
    {
      preserveState: true,
      onSuccess: () => {
        message.success('主要檔案已更新');
      },
      onError: () => {
        message.error('更新主要檔案失敗');
        if (props.task?.files) {
          form.existing_files = JSON.parse(JSON.stringify(props.task.files));
        }
      }
    }
  );
};

// Receives payload emitted from FileUpload component
const handleFilesChange = ({ existingFiles, newFiles, deleteFiles }) => {
  form.existing_files = existingFiles;
  form.new_files = newFiles;
  form.delete_files = deleteFiles;
};

// ============================================
// LIFECYCLE HOOKS
// ============================================

const handleResize = () => {
  isMobile.value = window.innerWidth < 768;
};

onMounted(() => {
  window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
  window.removeEventListener('resize', handleResize);
});

// ============================================
// FORM SUBMISSION
// ============================================

const handleSubmit = async () => {
  try {
    await formRef.value?.validate();
    submitting.value = true;

    // Prepare payload using reactive form data
    const payload = {
      title: form.title,
      description: form.description,
      action: form.action,
      result: form.result,
      status: form.status,
      user_id: form.user_id,
      department_id: form.department_id,
      delete_files: form.delete_files,
      files: form.new_files,
    };

    if (isEditing.value) {
      // Use POST with _method: 'put' for Laravel file upload support on update
      router.post(
        route('staff.tasks.update', props.task.id),
        {
          _method: 'put',
          ...payload,
        },
        {
          forceFormData: true,
          onSuccess: () => {
            message.success('Task updated successfully');
            fileUploadRef.value?.reset();
          },
          onError: () => {
            message.error('Error updating task');
          },
          onFinish: () => {
            submitting.value = false;
          },
        }
      );
    } else {
      // Standard POST request for creation
      router.post(route('staff.tasks.store'), payload, {
        forceFormData: true,
        onSuccess: () => {
          message.success('Task created successfully');
        },
        onError: () => {
          message.error('Error creating task');
        },
        onFinish: () => {
          submitting.value = false;
        },
      });
    }
  } catch (error) {
    submitting.value = false;
    message.error('Validation failed');
  }
};
</script>


<style scoped>

.back-button {
  flex-shrink: 0;
}

.form-title {
  margin: 0;
  font-size: 24px;
  font-weight: 600;
}

.form-actions {
  padding-top: 24px;
  border-top: 1px solid #f0f0f0;
  display: flex;
  justify-content: flex-end;
}
</style>