<!-- resources/js/Pages/Staff/Tasks.vue -->
<template>
  <StaffLayout>
  <div class="p-4 md:p-6 bg-white rounded-lg min-h-[calc(100vh-100px)]">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
      <h1 class="text-2xl md:text-3xl font-bold text-gray-900">工作備忘</h1>
      <a-button type="primary" @click="goToCreate" class="h-10 rounded-lg shadow-sm hover:shadow-md transition-shadow">
        <PlusOutlined />
        <span class="hidden sm:inline ml-1">新增備忘</span>
      </a-button>
    </div>

    <!-- Filters -->
    <div class="filters-section pb-0 lg:pb-10">
      <a-row :gutter="[16, 16]">
        <a-col :xs="24" :sm="24" :md="8">
                <a-input
                    v-model:value="localFilters.search"
                    placeholder="Search tasks..."
                    allow-clear
                    @press-enter="handleSearch"
                    @change="handleSearch"
                >
                    <template #prefix>
                    <SearchOutlined />
                    </template>
                </a-input>
        </a-col>
        <a-col :xs="12" :sm="12" :md="6">
            <a-select
                v-model:value="localFilters.status"
                placeholder="狀態"
                @change="handleFilterChange"
                style="width:100%"
            >
                <a-select-option value="pending">Pending</a-select-option>
                <a-select-option value="in_progress">In Progress</a-select-option>
                <a-select-option value="completed">Completed</a-select-option>
                <a-select-option value="cancelled">Cancelled</a-select-option>
            </a-select>
        </a-col>
        <a-col :xs="12" :sm="12" :md="6">
            <a-select
                v-model:value="localFilters.department_id"
                placeholder="部門"
                allow-clear
                @change="handleFilterChange"
                :option-label-prop="'label'"
                style="width:100%"
            >
                <a-select-option :value="null" label="All Departments">
                    All Departments
                </a-select-option>
                <a-select-option
                    v-for="dept in departments"
                    :key="dept.id"
                    :value="dept.id"
                    :label="dept.name_zh || dept.name"
                >
                    {{ dept.name_zh || dept.name }}
                </a-select-option>
            </a-select>
        </a-col>
        <a-col :xs="24" :sm="24" :md="4">
            <a-form-item :label="isMobile ? '' : '&nbsp;'">
          <a-button @click="resetFilters" size="large" block>
            <ReloadOutlined />
            清空
          </a-button>
          </a-form-item>
        </a-col>
      </a-row>
    </div>



    <!-- Mobile Cards View -->
    <div v-if="isMobile" class="space-y-4">
      <div 
        v-for="task in tasks.data" 
        :key="task.id" 
        class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 overflow-hidden"
      >
        <div class="p-4">
          <!-- Card Header -->
          <div class="flex justify-between items-start gap-2 mb-3">
            <h3 class="text-base font-semibold text-gray-900 flex-1 line-clamp-2">
              {{ task.title }}
            </h3>
            <a-dropdown>
              <a-button type="text" size="small" class="hover:bg-gray-100 rounded-full">
                <MoreOutlined class="text-gray-500" />
              </a-button>
              <template #overlay>
                <a-menu>
                  <a-menu-item @click="editTask(task.id)" class="flex items-center gap-2">
                    <EditOutlined /> Edit
                  </a-menu-item>
                  <a-menu-item :href="route('staff.tasks.edit', task.id)" class="flex items-center gap-2">
                    <EditOutlined /> Edit
                  </a-menu-item>
                  <a-menu-item danger @click="deleteTask(task.id)" class="flex items-center gap-2">
                    <DeleteOutlined /> Delete
                  </a-menu-item>
                </a-menu>
              </template>
            </a-dropdown>
          </div>

          <!-- Status Badge -->
          <div class="mb-3">
            <a-tag :color="getStatusColor(task.status)" class="rounded-full px-3 py-1 text-xs font-medium">
              {{ formatStatus(task.status) }}
            </a-tag>
          </div>

          <!-- Meta Info -->
          <div class="space-y-2 text-sm text-gray-600">
            <!-- <div class="flex items-center gap-2">
              <UserOutlined class="text-blue-500 w-4" />
              <span>{{ task.user?.name || 'N/A' }}</span>
            </div> -->
            <div class="flex items-center gap-2">
              <ApartmentOutlined class="text-purple-500 w-4" />
              <span>{{ task.department?.name || task.department?.name_zh || 'N/A' }}</span>
            </div>
            <div class="flex items-center gap-2">
              <MenuOutlined class="text-purple-500 w-4" />
              <p class="line-clamp-3">
                {{ task.description || 'N/A' }}
              </p>
            </div>
            <div class="flex items-center gap-2">
              <CalendarOutlined class="text-green-500 w-4" />
              <span>{{ formatDate(task.created_at) }}</span>
            </div>
          </div>

          <!-- Description -->
          <div v-if="task.description" class="mt-3 pt-3 border-t border-gray-100">
            <p class="text-sm text-gray-500 line-clamp-2">
              {{ task.department.abbr }}
            </p>
          </div>
        </div>
      </div>

      <!-- Mobile Pagination -->
      <div class="flex justify-center pt-4">
        <a-pagination
          v-model:current="tasks.current_page"
          :total="tasks.total"
          :page-size="tasks.per_page"
          :show-size-changer="false"
          size="small"
          @change="handlePageChange"
          class="mobile-pagination"
        />
      </div>
    </div>

    <!-- Desktop Table View -->
    <div v-else class="overflow-x-auto">
      <a-table
        :columns="columns"
        :data-source="tasks.data"
        :loading="loading"
        :pagination="{
          current: tasks.current_page,
          pageSize: tasks.per_page,
          total: tasks.total,
          showSizeChanger: true,
          showQuickJumper: true,
          pageSizeOptions: ['10', '20', '50', '100'],
          showTotal: (total) => `Total ${total} tasks`,
        }"
        @change="handleTableChange"
        row-key="id"
        :scroll="{ x: 800 }"
        class="custom-table"
      >
        <template #bodyCell="{ column, record }">
          <template v-if="column.key === 'status'">
            <a-tag :color="getStatusColor(record.status)" class="rounded-full px-3 py-0.5 text-xs font-medium">
              {{ formatStatus(record.status) }}
            </a-tag>
          </template>
          <template v-if="column.key === 'actions'">
            <div class="flex items-center gap-1">
              <a-button type="link" size="small" :href="route('staff.tasks.edit', record.id)" class="text-blue-500 hover:text-blue-700">
                <EditOutlined />
              </a-button>
              <a-popconfirm
                title="Are you sure you want to delete this task?"
                @confirm="deleteTask(record.id)"
              >
                <a-button type="link" danger size="small">
                  <DeleteOutlined />
                </a-button>
              </a-popconfirm>
            </div>
          </template>
          <template v-if="column.key === 'user'">
            <span class="text-gray-700">{{ record.user?.name }}</span>
          </template>
          <template v-if="column.key === 'department'">
            <span class="text-gray-700">{{ record.department?.name || record.department?.name_zh || 'N/A' }}</span>
          </template>
          <template v-if="column.key === 'created_at'">
            <span class="text-gray-500 text-sm">{{ formatDate(record.created_at) }}</span>
          </template>
        </template>
      </a-table>
    </div>

    <!-- Empty State -->
    <div v-if="!tasks.data || tasks.data.length === 0" class="text-center py-12">
      <div class="text-6xl mb-4">📋</div>
      <h3 class="text-xl font-semibold text-gray-700 mb-2">No Tasks Found</h3>
      <p class="text-gray-500">Try adjusting your filters or create a new task.</p>
      <a-button type="primary" @click="goToCreate" class="mt-4 rounded-lg">
        <PlusOutlined />
        Create Task
      </a-button>
    </div>
  </div>
  </StaffLayout>
</template>

<script setup>
import StaffLayout from "@/Layouts/StaffLayout.vue";
import { ref, reactive, watch, computed, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import {
  PlusOutlined,
  SearchOutlined,
  ReloadOutlined,
  EyeOutlined,
  EditOutlined,
  DeleteOutlined,
  UserOutlined,
  MenuOutlined,
  ApartmentOutlined,
  CalendarOutlined,
  MoreOutlined,
} from '@ant-design/icons-vue';
import { message } from 'ant-design-vue';

const props = defineProps({
  tasks: Object,
  filters: Object,
  departments: Array,
  users: Array,
});

const loading = ref(false);
const isMobile = ref(window.innerWidth < 768);

// Handle window resize
const handleResize = () => {
  isMobile.value = window.innerWidth < 768;
};

onMounted(() => {
  window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
  window.removeEventListener('resize', handleResize);
});


// Computed
const hasActiveFilters = computed(() => {
  return !!(localFilters.search || (localFilters.status && localFilters.status !== 'null') || localFilters.department_id);
});

// Get department name
const getDepartmentName = (id) => {
  const dept = props.departments?.find(d => d.id === id);
  return dept?.name_zh || dept?.name || 'Unknown';
};

// Get status tag color for filter tags
const getStatusTagColor = (status) => {
  const colors = {
    pending: 'orange',
    in_progress: 'blue',
    completed: 'green',
    cancelled: 'red',
  };
  return colors[status] || 'default';
};

const columns = [
  {
    title: '流水號',
    dataIndex: 'id',
    key: 'id',
    width: 80,
  },
  {
    title: '標題',
    dataIndex: 'title',
    key: 'title',
    sorter: true,
    ellipsis: true,
  },
  {
    title: '狀態',
    key: 'status',
    filters: [
      { text: '待處理', value: 'pending' },
      { text: '進行中', value: 'in_progress' },
      { text: '已完成', value: 'completed' },
      { text: '已取消', value: 'cancelled' },
    ],
  },
  {
    title: '建立日期',
    key: 'created_at',
    sorter: true,
    width: 150,
  },
  {
    title: '操作',
    key: 'actions',
    width: 150,
    fixed: 'right',
  },
];

// Initialize local filters from props
const localFilters = reactive({
  search: props.filters?.search || '',
  status: props.filters?.status || null,
  department_id: props.filters?.department_id || null,
  per_page: props.filters?.per_page || 10,
});

// Watch for changes in localFilters and update URL
let filterTimeout = null;

const updateFilters = () => {
  if (filterTimeout) {
    clearTimeout(filterTimeout);
  }

  filterTimeout = setTimeout(() => {
    const params = {
      search: localFilters.search || '',
      status: localFilters.status || '',
      department_id: localFilters.department_id || '',
      per_page: localFilters.per_page || 10,
    };

    // Remove empty params
    Object.keys(params).forEach(key => {
      if (params[key] === '' || params[key] === null || params[key] === undefined) {
        delete params[key];
      }
    });

    router.get(
      route('staff.tasks.index'),
      params,
      {
        preserveState: true,
        preserveScroll: true,
        replace: true,
      }
    );
  }, 300);
};

const handleSearch = () => {
  updateFilters();
};

const handleFilterChange = () => {
  updateFilters();
};

const resetFilters = () => {
  localFilters.search = '';
  localFilters.status = '';
  localFilters.department_id = '';
  localFilters.per_page = 10;
  updateFilters();
};

const getStatusColor = (status) => {
  const colors = {
    pending: 'orange',
    in_progress: 'blue',
    completed: 'green',
    cancelled: 'red',
  };
  return colors[status] || 'default';
};

const formatStatus = (status) => {
  if (!status) return '';
  return status.replace('_', ' ').toUpperCase();
};

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

const goToCreate = () => {
  router.get(route('staff.tasks.create'));
};
const viewTask = (id) => {
  router.get(route('staff.tasks.show', id));
};

const editTask = (id) => {
  router.get(route('staff.tasks.edit', id));
};

const deleteTask = (id) => {
  router.delete(route('staff.tasks.destroy', id), {
    onSuccess: () => {
      message.success('Task deleted successfully');
    },
    onError: (errors) => {
      message.error('Failed to delete task');
    },
  });
};

const handlePageChange = (page) => {
  router.get(
    route('staff.tasks.index'),
    {
      ...localFilters,
      page: page,
    },
    {
      preserveState: true,
      preserveScroll: true,
    }
  );
};

const handleTableChange = (pagination, filters, sorter) => {
  if (pagination.pageSize !== localFilters.per_page) {
    localFilters.per_page = pagination.pageSize;
  }

  if (filters.status) {
    localFilters.status = filters.status[0] || '';
  }

  const params = {
    search: localFilters.search || '',
    status: localFilters.status || '',
    department_id: localFilters.department_id || '',
    per_page: pagination.pageSize || 10,
    page: pagination.current || 1,
  };

  if (sorter.field) {
    params.sort_by = sorter.field;
    params.sort_order = sorter.order === 'ascend' ? 'asc' : 'desc';
  }

  router.get(route('staff.tasks.index'), params, {
    preserveState: true,
    preserveScroll: true,
  });
};

watch(
  () => props.filters,
  (newFilters) => {
    if (newFilters) {
      localFilters.search = newFilters.search || '';
      localFilters.status = newFilters.status || null;
      localFilters.department_id = parseInt(newFilters.department_id) || null;
      localFilters.per_page = newFilters.per_page || 10;
    }
  },
  { deep: true, immediate: true }
);
</script>

<style scoped>
/* Custom Ant Design overrides with Tailwind compatibility */
:deep(.ant-select-selector) {
  border-radius: 0.5rem !important;
  border-color: #e5e7eb !important;
  height: 40px !important;
  display: flex !important;
  align-items: center !important;
}

:deep(.ant-select-selector:hover) {
  border-color: #3b82f6 !important;
}

:deep(.ant-select-focused .ant-select-selector) {
  border-color: #3b82f6 !important;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
}

:deep(.ant-input) {
  border-radius: 0.5rem !important;
  border-color: #e5e7eb !important;
  height: 30px !important;
}

:deep(.ant-input:hover) {
  border-color: #3b82f6 !important;
}

:deep(.ant-input:focus) {
  border-color: #3b82f6 !important;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
}

:deep(.ant-btn) {
  border-radius: 0.5rem !important;
  font-weight: 500 !important;
}

:deep(.ant-btn-primary) {
  background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
  border: none !important;
}

:deep(.ant-btn-primary:hover) {
  background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
  transform: translateY(-1px) !important;
}

/* Table styling */
:deep(.ant-table) {
  border-radius: 0.75rem !important;
  overflow: hidden !important;
}

:deep(.ant-table-thead > tr > th) {
  background: #f9fafb !important;
  color: #374151 !important;
  font-weight: 600 !important;
  border-bottom: 2px solid #e5e7eb !important;
}

:deep(.ant-table-tbody > tr:hover > td) {
  background: #f3f4f6 !important;
}

:deep(.ant-table-tbody > tr > td) {
  border-bottom-color: #f3f4f6 !important;
}

/* Tag styling */
:deep(.ant-tag) {
  border-radius: 9999px !important;
  padding: 0 12px !important;
  border: none !important;
  font-weight: 500 !important;
}

/* Pagination styling */
:deep(.ant-pagination-item) {
  border-radius: 0.5rem !important;
  border-color: #e5e7eb !important;
}

:deep(.ant-pagination-item-active) {
  border-color: #3b82f6 !important;
}

:deep(.ant-pagination-item-active a) {
  color: #3b82f6 !important;
}

:deep(.ant-pagination-item:hover) {
  border-color: #3b82f6 !important;
}

/* Mobile pagination */
.mobile-pagination :deep(.ant-pagination-item) {
  min-width: 32px !important;
  height: 32px !important;
  line-height: 30px !important;
}

/* Loading state */
:deep(.ant-spin) {
  color: #3b82f6 !important;
}

/* Empty state animations */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Card animations */
.task-card-enter-active,
.task-card-leave-active {
  transition: all 0.3s ease;
}

.task-card-enter-from,
.task-card-leave-to {
  opacity: 0;
  transform: translateY(20px);
}

/* Line clamp utility */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

:deep(.ant-form-item){
  height:35px;
}
</style>