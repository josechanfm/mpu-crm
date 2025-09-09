<template>
  <div class="quill-container">
    <div ref="editorContainer" id="editor" class="quill-editor"></div>
  </div>
</template>

<script>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

export default {
  props: {
    modelValue: {
      type: String,
      default: ''
    },
    placeholder: {
      type: String,
      default: '請輸入內容...'
    },
    showToolbar: {
      type: Boolean,
      default: true
    },
    showPreview: {
      type: Boolean,
      default: true
    },
  },
  
  emits: ['update:modelValue'],
  
  setup(props, { emit }) {
    const editorContainer = ref(null);
    const fileInput = ref(null);
    let quill = null;
    const content = ref(props.modelValue);
    const showImageModal = ref(false);
    const uploading = ref(false);
    const toolbarOptions = [
      [{ header: [1, 2, false] }],
      ['bold', 'italic', 'underline'],
      ['image', 'code-block'],
      ['link']
    ];
    
    // 初始化编辑器
    const initEditor = () => {
      if (!editorContainer.value) return;
      
      const options = {
        modules: {
          toolbar: {
            container: toolbarOptions, // Selector for toolbar container
            handlers: {
              // image: imageHandler
            }
          },

        },
        placeholder: 'Compose an epic...',
        theme: 'snow', // or 'bubble'
      };
      //quill = new Quill('#editor', options);
      quill = new Quill(editorContainer.value, options);
      
      // 设置初始内容
      if (content.value) {
        quill.root.innerHTML = content.value;
      }
      
      // 监听内容变化
      quill.on('text-change', () => {
        const html = editorContainer.value.querySelector('.ql-editor').innerHTML;
        content.value = html;
        emit('update:modelValue', html);
      });
    };
    const imageHandler = async () => {
      console.log(quill);
      const range = quill.getSelection();
      if (!range) {
        console.warn('No selection range available');
        return;
      }
      const input = document.createElement('input');
      input.setAttribute('type', 'file');
      input.setAttribute('accept', 'image/*');
      input.click();
      console.log(input);
      // input.onchange = async () => {
      //   const file = input.files[0];
      //   if (!file) return;

      //   try {
      //     const formData = new FormData();
      //     formData.append('image', file);

      //     // 使用 Axios 上傳
      //     const response = await axios.post(
      //       route('admin.content.upload.image'), 
      //       formData,
      //       {
      //         headers: {
      //           'Content-Type': 'multipart/form-data',
      //         }
      //       }
      //     );

      //     // 上傳成功處理
      //     if (response.data && response.data.url) {
      //       quill.deleteText(range.index, 1);
      //       quill.insertEmbed(range.index, 'image', response.data.url, 'user');
      //       quill.setSelection(range.index + 1, 0, 'silent');
      //     } else {
      //       throw new Error('Invalid response format');
      //     }
      //   } catch (error) {
      //     // 錯誤處理
      //     quill.deleteText(range.index, 1);
          
      //     // 顯示錯誤訊息
      //     const errorMessage = error.response?.data?.error || 
      //                         error.response?.data?.message || 
      //                         '圖片上傳失敗';
          
      //     quill.insertText(range.index, `[${errorMessage}]`, { color: 'red' });
      //     quill.setSelection(range.index + errorMessage.length + 2);
          
      //     console.error('圖片上傳錯誤:', error);
      //   } finally {
      //     quill.enable(true); // 重新啟用編輯器
      //   }
      // };
    };
    // 触发图片上传
    const triggerImageUpload = () => {
      if (!props.showToolbar) return;
      showImageModal.value = true;
      
      // 清空文件输入
      if (fileInput.value) {
        fileInput.value.value = '';
      }
    };
    
    // 处理图片上传
    const handleImageUpload = async (event) => {
      const file = event.target.files[0];
      if (!file) return;
      
      uploading.value = true;
      
      try {
        // 模拟上传过程
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        // 创建临时URL预览
        const imageUrl = URL.createObjectURL(file);
        
        // 获取当前光标位置
        const range = quill.getSelection();
        
        // 插入图片
        quill.insertEmbed(range.index, 'image', imageUrl);
        
        // 移动光标到图片后面
        quill.setSelection(range.index + 1);
        
        showImageModal.value = false;
      } catch (error) {
        console.error('圖片上傳失敗:', error);
      } finally {
        uploading.value = false;
      }
    };
    
    // 监听外部内容变化
    watch(() => props.modelValue, (newValue) => {
      if (newValue !== content.value && quill) {
        quill.root.innerHTML = newValue;
        content.value = newValue;
      }
    });
    
    onMounted(() => {
      initEditor();
    });
    
    onBeforeUnmount(() => {
      if (quill) {
        quill.off('text-change');
      }
    });
    
    return {
      editorContainer,
      fileInput,
      content,
      showImageModal,
      uploading,
      triggerImageUpload,
      handleImageUpload
    };
  }
};
</script>

<style scoped>
.quill-container {
  max-width: 1000px;
  margin: 0 auto;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.quill-editor {
  height: 300px;
  background: white;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-bottom: 20px;
}

.custom-toolbar {
  background-color: #f5f5f5;
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 8px;
  margin-bottom: 15px;
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
}

.custom-toolbar button,
.custom-toolbar select {
  background: white;
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 5px 10px;
  cursor: pointer;
}

.custom-toolbar button:hover,
.custom-toolbar select:hover {
  background-color: #f0f0f0;
}

.content-preview {
  margin-top: 30px;
  padding: 20px;
  background-color: #f9f9f9;
  border: 1px solid #eee;
  border-radius: 4px;
}

.content-preview h3 {
  margin-top: 0;
  color: #333;
  border-bottom: 1px solid #eee;
  padding-bottom: 10px;
}

.image-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 30px;
  border-radius: 8px;
  width: 400px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.modal-content h3 {
  margin-top: 0;
  color: #333;
}

.modal-content input[type="file"] {
  margin: 15px 0;
  width: 100%;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 15px;
}

.modal-actions button {
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  background: #f0f0f0;
}

.modal-actions button:hover {
  background: #e0e0e0;
}
</style>