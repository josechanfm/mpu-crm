<!-- resources/js/Components/FileUpload.vue -->
<template>
  <div class="file-upload-component">
    <!-- Existing Files Display -->
    <div v-if="existingFiles && existingFiles.length > 0" class="mb-4">
      <div class="grid xs:grid-cols-1 md:grid-cols-4 xl:grid-cols-6 gap-3">
        <div
          v-for="file in existingFiles"
          :key="file.id"
          class="relative border rounded-lg p-3 hover:shadow-md transition-shadow bg-white"
          :class="{
            'border-blue-500 bg-blue-50': file.is_primary && !isMarkedForDeletion(file.id),
            'border-red-500 bg-red-50 opacity-60': isMarkedForDeletion(file.id),
            'border-gray-200': !file.is_primary && !isMarkedForDeletion(file.id),
          }"
        >
          <!-- Deletion Overlay -->
          <div 
            v-if="isMarkedForDeletion(file.id)" 
            class="absolute inset-0 bg-red-50 bg-opacity-60 rounded-lg flex items-center justify-center"
          >
            <div class="text-center">
              <div class="text-red-500 text-2xl mb-1">🗑️</div>
              <div class="text-red-600 text-xs font-semibold">將被刪除</div>
            </div>
          </div>

          <!-- File Preview - Image -->
          <div v-if="file.file_url && isImage(file)" class="flex flex-col items-center">
            <img 
              :src="file.file_url" 
              class="w-full aspect-square object-cover rounded-lg"
              :class="{ 'opacity-50': isMarkedForDeletion(file.id) }"
              :alt="file.original_name"
            />
            <div class="text-xs font-medium truncate w-full text-center mt-2" :title="file.original_name">
              {{ file.original_name }}
            </div>
            <div class="text-xs text-gray-500">{{ formatFileSize(file.file_size) }}</div>
          </div>

          <!-- File Preview - Non-Image -->
          <div v-else class="flex flex-col items-center">
            <div class="w-full aspect-square flex items-center justify-center bg-gray-50 rounded-lg">
              <div class="text-5xl">{{ getFileIcon(file) }}</div>
            </div>
            <div class="text-xs font-medium truncate w-full text-center mt-2" :title="file.original_name">
              {{ file.original_name }}
            </div>
            <div class="text-xs text-gray-500">{{ formatFileSize(file.file_size) }}</div>
          </div>

          <!-- Primary Badge -->
          <div v-if="file.is_primary && !isMarkedForDeletion(file.id)" class="absolute top-2 right-2">
            <a-tag color="blue" size="small" class="text-xs">主要</a-tag>
          </div>

          <!-- File Actions -->
          <div class="flex justify-center gap-1 mt-2">
            <!-- Set Primary Button -->
            <a-button
              v-if="!readonly"
              type="text"
              size="small"
              @click="handleSetPrimary(file.id)"
              :disabled="isMarkedForDeletion(file.id)"
              :class="file.is_primary ? 'text-yellow-500' : 'text-gray-400 hover:text-blue-500'"
              :title="file.is_primary ? '主要檔案' : '設為主要'"
            >
              <StarOutlined :class="file.is_primary ? 'text-yellow-500' : ''" />
            </a-button>
            
            <!-- Download Button -->
            <a-button
              type="text"
              size="small"
              @click="handleDownload(file.id)"
              class="text-green-500 hover:text-green-700"
              title="Download"
            >
              <DownloadOutlined />
            </a-button>
            
            <!-- Delete/Restore Button -->
            <a-popconfirm
              v-if="!readonly && !isMarkedForDeletion(file.id)"
              title="確定要刪除這個檔案嗎？"
              @confirm="handleDelete(file.id)"
              ok-text="確定"
              cancel-text="取消"
            >
              <a-button type="text" size="small" danger title="Delete">
                <DeleteOutlined />
              </a-button>
            </a-popconfirm>
            
            <!-- Restore Button -->
            <a-button
              v-if="!readonly && isMarkedForDeletion(file.id)"
              type="text"
              size="small"
              @click="handleRestore(file.id)"
              class="text-green-500 hover:text-green-700"
              title="Restore file"
            >
              <UndoOutlined />
            </a-button>
          </div>

        </div>
      </div>
    </div>

    <!-- File Upload -->
    <div v-if="!readonly">
      <a-upload
        v-model:file-list="fileList"
        list-type="picture"
        :before-upload="beforeUpload"
        :multiple="multiple"
        :max-count="maxFiles"
        @remove="handleRemove"
        class="file-uploader"
      >
        <a-button class="upload-btn">
          <UploadOutlined />
          {{ uploadButtonText }}
        </a-button>
        <div class="text-xs text-gray-400 mt-1">
          {{ helpText }}
        </div>
      </a-upload>
      
      <!-- Upload Queue -->
      <div v-if="newFiles && newFiles.length > 0" class="mt-3">
        <div class="text-sm font-medium text-gray-700 mb-2">
          待上傳檔案 ({{ newFiles.length }})
        </div>
        <div class="flex flex-wrap gap-2">
          <a-tag
            v-for="(file, index) in newFiles"
            :key="index"
            closable
            @close="removeNewFile(index)"
            color="blue"
            class="text-sm"
          >
            📎 {{ file.name }} ({{ formatFileSize(file.size) }})
          </a-tag>
        </div>
      </div>
    </div>

    <!-- Readonly mode -->
    <div v-else-if="existingFiles && existingFiles.length > 0" class="text-sm text-gray-500">
      共 {{ existingFiles.length }} 個檔案
    </div>

    <!-- No files -->
    <div v-if="!existingFiles || existingFiles.length === 0" class="text-sm text-gray-400">
      {{ readonly ? '尚無檔案' : '尚未上傳任何檔案' }}
    </div>

    <!-- Deletion Summary -->
    <div v-if="deleteFiles.length > 0" class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 text-sm text-red-600">
        <div class="flex items-center gap-2">
          <span class="font-semibold">🗑️ 將刪除 {{ deleteFiles.length }} 個檔案：</span>
          <span class="text-xs truncate max-w-[200px] sm:max-w-[300px]">
            {{ getDeletedFileNames() }}
          </span>
        </div>
        <a-button 
          type="link" 
          size="small" 
          class="text-red-500 hover:text-red-700 sm:ml-auto text-left sm:text-right"
          @click="clearAllDeletions"
        >
          全部復原
        </a-button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { message } from 'ant-design-vue';
import {
  UploadOutlined,
  DeleteOutlined,
  DownloadOutlined,
  StarOutlined,
  UndoOutlined,
} from '@ant-design/icons-vue';

const props = defineProps({
  existingFiles: {
    type: Array,
    default: () => [],
  },
  readonly: {
    type: Boolean,
    default: false,
  },
  maxFiles: {
    type: Number,
    default: 10,
  },
  multiple: {
    type: Boolean,
    default: true,
  },
  uploadButtonText: {
    type: String,
    default: '上傳檔案',
  },
  helpText: {
    type: String,
    default: '最多 10 個檔案。支援格式：圖片 (JPG, PNG, GIF, WebP)、PDF、Word、Excel。每個檔案最大 10MB',
  },
  allowedTypes: {
    type: Array,
    default: () => [
      'image/jpeg', 'image/png', 'image/gif', 'image/webp',
      'application/pdf',
      'application/msword',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      'application/vnd.ms-excel',
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
      'application/vnd.ms-powerpoint',
      'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    ],
  },
  maxFileSize: {
    type: Number,
    default: 10 * 1024 * 1024,
  },
  downloadRoute: {
    type: String,
    default: 'staff.task.download-file',
  },
  setPrimaryRoute: {
    type: String,
    default: 'staff.task.set-primary-file',
  },
});

const emit = defineEmits([
  'update:existingFiles',
  'file-added',
  'file-removed',
  'file-deleted',
  'file-restored',
  'primary-set',
  'files-change',
  'download', // ✅ Add this line
]);

const fileList = ref([]);
const newFiles = ref([]);
const deleteFiles = ref([]);

// Check if file is an image
const isImage = (file) => {
  const imageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
  return file.mime_type && imageTypes.includes(file.mime_type);
};

// Check if a file is marked for deletion
const isMarkedForDeletion = (fileId) => {
  return deleteFiles.value.includes(fileId);
};

// Get names of files marked for deletion
const getDeletedFileNames = () => {
  const names = props.existingFiles
    .filter(file => deleteFiles.value.includes(file.id))
    .map(file => file.original_name)
    .join('、');
  return names || '無';
};

// Clear all deletions
const clearAllDeletions = () => {
  const restoredFiles = props.existingFiles.filter(file => 
    deleteFiles.value.includes(file.id)
  );
  
  deleteFiles.value = [];
  emit('update:existingFiles', props.existingFiles);
  emit('files-change', {
    existingFiles: props.existingFiles,
    newFiles: newFiles.value,
    deleteFiles: deleteFiles.value,
  });
  message.success('已復原所有檔案');
};

// Watch for changes and emit
watch(
  () => ({ 
    existingFiles: props.existingFiles, 
    newFiles: newFiles.value, 
    deleteFiles: deleteFiles.value 
  }),
  () => {
    emit('files-change', {
      existingFiles: props.existingFiles,
      newFiles: newFiles.value,
      deleteFiles: deleteFiles.value,
    });
  },
  { deep: true }
);

// Get file icon based on mime type
const getFileIcon = (file) => {
  const icons = {
    'image': '🖼️',
    'application/pdf': '📄',
    'application/msword': '📝',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document': '📝',
    'application/vnd.ms-excel': '📊',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': '📊',
    'application/vnd.ms-powerpoint': '📽️',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation': '📽️',
    'application/zip': '📦',
    'video/mp4': '🎬',
    'audio/mpeg': '🎵',
  };
  
  const mimeType = file.mime_type || file.type || '';
  if (mimeType.startsWith('image/')) return icons['image'];
  return icons[mimeType] || '📎';
};

// Format file size
const formatFileSize = (bytes) => {
  if (!bytes) return '0 B';
  const units = ['B', 'KB', 'MB', 'GB'];
  let size = bytes;
  let unitIndex = 0;
  while (size >= 1024 && unitIndex < units.length - 1) {
    size /= 1024;
    unitIndex++;
  }
  return size.toFixed(1) + ' ' + units[unitIndex];
};

// Before upload - validate and store file
const beforeUpload = (file) => {
  // Validate file type
  if (!props.allowedTypes.includes(file.type)) {
    message.error('不支援的檔案格式。請上傳圖片、PDF、Word 或 Excel 檔案。');
    return false;
  }

  // Validate file size
  if (file.size > props.maxFileSize) {
    message.error(`檔案大小必須小於 ${formatFileSize(props.maxFileSize)}。`);
    return false;
  }

  // Check if already in list
  const exists = newFiles.value.some(f => f.name === file.name && f.size === file.size);
  if (exists) {
    message.warning('此檔案已在待上傳清單中。');
    return false;
  }

  // Check max files limit
  const totalFiles = (props.existingFiles?.length || 0) + newFiles.value.length;
  if (totalFiles >= props.maxFiles) {
    message.error(`最多只能上傳 ${props.maxFiles} 個檔案。`);
    return false;
  }

  // Add to new files list
  newFiles.value.push(file);
  emit('file-added', file);
  message.success(`已加入: ${file.name}`);
  
  return false;
};

// Remove file from upload queue
const removeNewFile = (index) => {
  const file = newFiles.value[index];
  newFiles.value.splice(index, 1);
  emit('file-removed', file);
};

// Remove from file list (Ant Design upload component)
const handleRemove = (file) => {
  const index = newFiles.value.findIndex(f => f.uid === file.uid);
  if (index > -1) {
    const removedFile = newFiles.value[index];
    newFiles.value.splice(index, 1);
    emit('file-removed', removedFile);
  }
};

// Delete existing file
const handleDelete = (fileId) => {
  if (deleteFiles.value.includes(fileId)) {
    return;
  }
  
  deleteFiles.value.push(fileId);
  
  const updatedFiles = props.existingFiles.filter(f => f.id !== fileId);
  emit('update:existingFiles', updatedFiles);
  
  const deletedFile = props.existingFiles.find(f => f.id === fileId);
  emit('file-deleted', { id: fileId, file: deletedFile });
  
  message.success(`檔案 "${deletedFile?.original_name}" 已標記為刪除`);
};

// Restore deleted file
const handleRestore = (fileId) => {
  deleteFiles.value = deleteFiles.value.filter(id => id !== fileId);
  
  const originalFiles = props.existingFiles;
  const restoredFile = originalFiles.find(f => f.id === fileId);
  
  if (restoredFile) {
    const updatedFiles = [...props.existingFiles, restoredFile];
    emit('update:existingFiles', updatedFiles);
    emit('file-restored', restoredFile);
    message.success(`已復原: ${restoredFile.original_name}`);
  }
};

// Set primary file
const handleSetPrimary = (fileId) => {
  const updatedFiles = props.existingFiles.map(file => ({
    ...file,
    is_primary: file.id === fileId,
  }));
  emit('update:existingFiles', updatedFiles);
  emit('primary-set', fileId);
};

// Download file
const handleDownload = (fileId) => {
  if (props.downloadRoute) {
    window.open(route(props.downloadRoute, fileId), '_self');
  }
  emit('download', fileId);
};

// Reset component state
const reset = () => {
  newFiles.value = [];
  deleteFiles.value = [];
  fileList.value = [];
};

// Get all files
const getAllFiles = () => {
  return {
    existing: props.existingFiles,
    new: newFiles.value,
    deleted: deleteFiles.value,
  };
};

// Expose methods
defineExpose({
  reset,
  getAllFiles,
  newFiles,
  deleteFiles,
  isMarkedForDeletion,
});
</script>

<style scoped>
.file-upload-component {
  width: 100%;
}

/* Grid layout for files - Responsive */
.grid {
  display: grid;
  gap: 0.75rem;
}

.grid-cols-2 {
  grid-template-columns: repeat(2, 1fr);
}

@media (min-width: 640px) {
  .grid-cols-3 {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (min-width: 768px) {
  .grid-cols-4 {
    grid-template-columns: repeat(4, 1fr);
  }
}

@media (min-width: 1280px) {
  .grid-cols-6 {
    grid-template-columns: repeat(6, 1fr);
  }
}

/* Mobile optimization - larger images on mobile */
@media (max-width: 640px) {
  .grid-cols-2 {
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
  }
  
  .relative {
    padding: 0.5rem !important;
  }
  
  .relative img {
    min-height: 120px;
    max-height: 180px;
  }
  
  .relative .aspect-square {
    min-height: 100px;
  }
  
  .text-xs {
    font-size: 0.65rem !important;
  }
  
  .upload-btn {
    width: 100% !important;
    justify-content: center !important;
  }
}

/* File uploader */
.file-uploader :deep(.ant-upload-list) {
  margin-top: 8px;
}

.file-uploader :deep(.ant-upload-list-item) {
  border-radius: 8px;
  border: 1px solid #d9d9d9;
}

.file-uploader :deep(.ant-upload-list-item:hover) {
  border-color: #1890ff;
}

/* File card hover effects */
.relative {
  position: relative;
  transition: all 0.2s ease;
}

.relative:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Aspect ratio for images */
.aspect-square {
  aspect-ratio: 1 / 1;
}

/* Deletion styles */
.border-red-500 {
  border-color: #ef4444 !important;
}

.bg-red-50 {
  background-color: #fef2f2 !important;
}

/* Deletion overlay */
.absolute.inset-0 {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}

/* Upload button mobile full width */
@media (max-width: 640px) {
  .file-uploader :deep(.ant-upload) {
    display: block !important;
    width: 100% !important;
  }
  
  .file-uploader :deep(.ant-upload > .ant-btn) {
    width: 100% !important;
  }
}
</style>