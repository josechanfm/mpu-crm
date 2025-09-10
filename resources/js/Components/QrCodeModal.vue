<template>
    <!-- Trigger Button -->
    <a-button type="primary" ghost @click="openModal" >Generate QR Code</a-button>

    <!-- Modal -->
    <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50">
      <div class="bg-white p-6 rounded-lg shadow-lg text-center">
        <h3 class="text-lg font-bold mb-4 text-center max-w-xs mx-auto break-words">{{ title }}</h3>
        <div class="relative">
          <qrcode-vue 
            :value="fullUrl" 
            ref="qrCode" 
            :download="true"
            :downloadOptions="{ name: title, extension: 'png' }"
            :qrOptions="{ typeNumber: 0, mode: 'Byte', errorCorrectionLevel: 'H' }"
            image='/storage/images/mpu_logo.png'
            :imageOptions="{ hideBackgroundDots: true, imageSize: 0.4, margin: 0 }"
            :dotsOptions="{
                type: 'dots',
                color: '#26249a',
                gradient: {
                  type: 'linear',
                  rotation: 0,
                  colorStops: [
                    { offset: 0, color: '#26249a' },
                    { offset: 1, color: '#26249a' },
                  ],
                },
              }"
            :backgroundOptions="{ color: '#ffffff' }"
            :cornersSquareOptions="{ type: 'square', color: '#008000' }"
            :cornersDotOptions="{ type: undefined, color: '#000000' }"
          />
        </div>
        <button @click="closeModal" class="bg-red-500 text-white px-4 py-2 rounded mt-4">Close</button>
      </div>
    </div>

</template>

<script>
import QrcodeVue from 'qrcode-vue3';

export default {
  components: {
    QrcodeVue,
  },
  props: {
    fullUrl: {
      type: String,
      required: true,
    },
    title: {
      type: String,
      required: false,
      default: 'QR Code', // Default title
    },
  },
  data() {
    return {
      isModalOpen: false,
    };
  },
  methods: {
    openModal() {
      this.isModalOpen = true;
    },
    closeModal() {
      this.isModalOpen = false;
    },
  },
};
</script>

<style scoped>
/* No additional CSS needed when using Tailwind CSS */
</style>