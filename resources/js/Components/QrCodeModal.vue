<template>
  <!-- Modal -->
  <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50" @click.self="closeModal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center transition-transform transform scale-95" @click.stop>
      <h3 id="modal-title" class="text-lg font-bold mb-4 text-center max-w-xs mx-auto break-words">{{ title }}</h3>
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
              color: qrCodeStyle[defaultStyle].dotColor,
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
          :cornersSquareOptions="{ type: qrCodeStyle[defaultStyle].squareType, color: qrCodeStyle[defaultStyle].squareColor }"
          :cornersDotOptions="{ type:  qrCodeStyle[defaultStyle].connerDotType, color:  qrCodeStyle[defaultStyle].connerDotColor }"
        />
      </div>
        <a-button 
          @click="copyToClipboard" 
          type="primary" ghost danger
          class="mr-5"
        >
          Copy URL to clipboard
        </a-button>
      <a-button @click="closeModal" type="primary" ghost>Close</a-button>
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
    isModalOpen: {
      type: Boolean,
      required: true,
    },
  },
  data() {
      return {
        defaultStyle:'style1',
        qrCodeStyle:{
          'style1':{
            squareType:'square',
            squareColor: '#26249a',
            connerDotType: 'square',
            connerDotColor: '#26249a',
            dotColor: '#26249a'
          },
          'style2':{
            squareType:'square',
            squareColor: '#008000',
            connerDotType: 'dot',
            connerDotColor: '#26249a',
            dotColor: '#000000'
          }

        }
      }
  },
  methods: {
    closeModal() {
      this.$emit('update:isModalOpen', false); // Emit event to parent to close modal

    },
    copyToClipboard() {
      navigator.clipboard.writeText(this.fullUrl)
        .then(() => {
          this.$message.info("URL copied to clipboard!")
        })
        .catch(err => {
          console.error('Failed to copy: ', err);
        });
    },
  },
};
</script>

<style scoped>
/* Add any additional styles if needed */
</style>