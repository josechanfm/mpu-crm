<template>
  <div>
    <!-- Trigger Button -->
    <button @click="openModal">Generate QR Code</button>

    <!-- Modal -->
    <div v-if="isModalOpen" class="modal-overlay">
      <div class="modal-content">
        <h3>QR Code</h3>
        <qrcode-vue3 :value="url" :size="200" />
        <button @click="copyImage">Copy Image</button>
        <button @click="closeModal">Close</button>
      </div>
    </div>
  </div>
</template>

<script>
import { QrcodeVue } from 'qrcode-vue3';

export default {
  components: {
    QrcodeVue,
  },
  data() {
    return {
      isModalOpen: false,
      url: '',
    };
  },
  methods: {
    openModal(url) {
      this.url = url; // Set the URL for the QR code
      this.isModalOpen = true;
    },
    closeModal() {
      this.isModalOpen = false;
    },
    copyImage() {
      const canvas = document.querySelector("canvas");
      if (canvas) {
        canvas.toBlob((blob) => {
          const item = new ClipboardItem({ "image/png": blob });
          navigator.clipboard.write([item]).then(() => {
            alert('Image copied to clipboard!');
          });
        });
      }
    },
  },
};
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: white;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
}
</style>