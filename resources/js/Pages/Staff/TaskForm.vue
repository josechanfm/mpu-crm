<!-- resources/js/Pages/Staff/TaskForm.vue -->
<template>
  <StaffLayout>
  <div class="m-0 lg:m-5">
    <div class="mb-5">
      <a-button @click="goBack" class="back-button">
        <ArrowLeftOutlined />
        Back
      </a-button>
      <h1 class="form-title">{{ formTitle }}</h1>
    </div>

    <a-card   :body-style="isMobile ? { padding: '5px' } : { padding: '10px' }">
      <a-form
        :model="form"
        :rules="rules"
        layout="vertical"
        ref="formRef"
        @finish="handleSubmit"
      >
        <a-row :gutter="24">
          <a-col :span="12">
            <a-form-item label="Title" name="title" required>
              <a-input
                type="input"
                v-model:value="form.title"
                placeholder="Enter task title"
                :disabled="readonly"
              />
            </a-form-item>
          </a-col>

          <a-col :span="12">
            <a-form-item label="Status" name="status" required>
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
            <a-form-item label="Department" name="department_id">
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
            <a-form-item label="Description" name="description">
              <a-textarea
                v-model:value="form.description"
                placeholder="Enter task description"
                :disabled="readonly"
                :rows="5"
              />
            </a-form-item>
          </a-col>

          <a-col :span="24">
            <a-form-item label="Action" name="action">
              <a-textarea
                v-model:value="form.action"
                placeholder="Enter action items"
                :disabled="readonly"
                :rows="5"
              />
            </a-form-item>
          </a-col>
          <a-col :span="24">
            <a-form-item label="Result" name="result">
              <a-textarea
                v-model:value="form.result"
                placeholder="Enter results"
                :disabled="readonly"
                :rows="5"
              />
            </a-form-item>
          </a-col>
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
import { ref, reactive, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { message } from 'ant-design-vue';
import {
  ArrowLeftOutlined,
  SaveOutlined,
  EditOutlined,
} from '@ant-design/icons-vue';

const props = defineProps({
  task: Object,
  departments: Array,
  users: Array,
  readonly: Boolean,
});

const formRef = ref();
const submitting = ref(false);
const page = usePage();

const isEditing = computed(() => !!props.task?.id);
const isMobile = ref(window.innerWidth < 768);

const formTitle = computed(() => {
  if (props.readonly) return 'Task Details';
  return isEditing.value ? 'Edit Task' : 'Create New Task';
});

const form = reactive({
  title: props.task?.title || '',
  description: props.task?.description || '',
  action: props.task?.action || '',
  result: props.task?.result || '',
  status: props.task?.status || 'pending',
  user_id: props.task?.user_id || '',
  department_id: props.task?.department_id || null,
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

const handleSubmit = async () => {
  try {
    await formRef.value?.validate();
    submitting.value = true;
    console.log('submitting');
    console.log(formRef, form, props.task)

     if (isEditing.value) {
      console.log('update');
      router.put(route('staff.tasks.update', props.task.id), form);
    } else {
      console.log('store');
      router.post(route('staff.tasks.store'), form);
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