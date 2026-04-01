<template>
  <div class="max-w-2xl mx-auto my-8 p-8 bg-white rounded-xl shadow-lg border border-gray-100">
    <div class="flex justify-between items-start mb-2">
      <h2 class="text-2xl font-bold text-gray-800">Reading Practice</h2>
      <span v-if="level" class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full">
        LEVEL {{ level }}
      </span>
    </div>
    <p class="text-gray-500 mb-8 text-sm">Listen to the text, then record your own voice to check accuracy.</p>

    <div class="relative p-6 bg-gray-50 rounded-lg border border-gray-200 mb-8">
      <div v-if="loading" class="text-center py-4 text-gray-400 italic">Loading content...</div>
      <div v-else>
        <p class="text-lg leading-relaxed text-gray-700 select-none font-medium">
          {{ targetText }}
        </p>

        <div v-if="translation" class="mt-4 border-t pt-4 border-gray-100">
          <button 
            @click="showTranslation = !showTranslation"
            class="text-xs font-bold text-blue-500 hover:text-blue-700 uppercase tracking-tight flex items-center gap-1"
          >
            <span>{{ showTranslation ? '▲ Hide Translation' : '▼ Show Translation' }}</span>
          </button>
          
          <transition name="fade">
            <p v-if="showTranslation" class="mt-2 text-gray-500 leading-relaxed">
              {{ translation }}
            </p>
          </transition>
        </div>
      </div>

      <div class="absolute -top-3 right-4 flex gap-2">
<button 
  @click="playReference"
  :disabled="loading || isPlaying"
  class="flex items-center gap-2 px-3 py-1 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white text-xs font-bold rounded-full shadow-sm transition-all"
>
  <span v-if="isPlaying">⏳ SPEAKING...</span>
  <span v-else-if="playCount === 0">🔊 LISTEN (NORMAL)</span>
  <span v-else>🐌 LISTEN (SLOW)</span>
</button>
      </div>
    </div>

    <div class="flex flex-col items-center gap-6">
      <div class="flex items-center justify-center gap-4 w-full">
        <button 
          @click="toggleListening" 
          :disabled="loading"
          :class="isListening 
            ? 'bg-red-500 hover:bg-red-600 ring-4 ring-red-100' 
            : 'bg-green-600 hover:bg-green-700 shadow-md'"
          class="flex items-center gap-3 px-8 py-3 text-white font-bold rounded-xl transition-all duration-300 transform active:scale-95 disabled:opacity-50"
        >
          <span v-if="!isListening">🎤 Start Recording</span>
          <span v-else class="flex items-center gap-2">
            <span class="w-2 h-2 bg-white rounded-full animate-ping"></span>
            Stop Recording
          </span>
        </button>

        <button 
          @click="fetchNewMaterial"
          class="p-3 text-gray-400 hover:text-blue-600 bg-gray-200 hover:bg-blue-100 rounded-xl transition-colors"
          title="Next Material"
        >
          <span class="text-xl">⏭️</span>
        </button>
      </div>

      <div v-if="isListening || transcript" class="w-full text-center">
        <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Live Transcript</span>
        <p class="mt-2 text-gray-600 min-h-[1.5rem] italic">
          "{{ transcript || 'Waiting for speech...' }}"
        </p>
      </div>

      <transition name="fade">
        <div v-if="accuracy !== null" 
          class="w-full mt-4 p-6 rounded-2xl flex items-center justify-between"
          :class="scoreStyles.bg"
        >
          <div>
            <p class="text-sm font-medium" :class="scoreStyles.text">Accuracy Score</p>
            <p class="text-4xl font-black" :class="scoreStyles.text">{{ accuracy }}%</p>
          </div>
          <div class="text-4xl">{{ scoreStyles.emoji }}</div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      targetText: "",
      translation: "",
      showTranslation: false, // Control for the hidden state
      level: "A1",
      transcript: "",
      isListening: false,
      accuracy: null,
      loading: false,
      recognition: null,
      silenceTimer: null,
      silenceDuration: 5000, // 5 seconds of silence before auto-stop
      playCount: 0,
      isPlaying: false // New state to disable button while speaking
    };
  },

  computed: {
    scoreStyles() {
      if (this.accuracy >= 90) return { bg: 'bg-green-50', text: 'text-green-700', emoji: '🌟' };
      if (this.accuracy >= 70) return { bg: 'bg-blue-50', text: 'text-blue-700', emoji: '👍' };
      if (this.accuracy >= 40) return { bg: 'bg-yellow-50', text: 'text-yellow-700', emoji: '😐' };
      return { bg: 'bg-red-50', text: 'text-red-700', emoji: '❌' };
    }
  },

  mounted() {
    this.initSpeechRecognition();
    this.fetchNewMaterial();
  },

  methods: {
    async fetchNewMaterial() {
      this.loading = true;
      this.resetDemo();
      this.showTranslation = false; // Reset translation visibility for new content
      this.playCount = 0; // RESET HERE

      try {
        const response = await axios.get('/learner/get_material', {
          params: { type: 'speech', level: this.level }
        });
        
        if (response.data) {
          this.targetText = response.data.content;
          this.translation = response.data.translation;
          this.level = response.data.level;
        }
      } catch (error) {
        console.error("Error fetching material:", error);
      } finally {
        this.loading = false;
      }
    },

    initSpeechRecognition() {
      const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
      if (!SpeechRecognition) return;

      this.recognition = new SpeechRecognition();
      this.recognition.continuous = true;
      this.recognition.interimResults = true;
      this.recognition.lang = 'en-US';

      // Triggered when the browser stops the microphone (due to silence or manual stop)
      this.recognition.onend = () => {
        this.isListening = false;
        this.clearSilenceTimer();
      };

      this.recognition.onresult = (event) => {
        // RESET TIMER: The user is speaking
        this.resetSilenceTimer();

        let finalTranscript = '';
        for (let i = event.resultIndex; i < event.results.length; ++i) {
          if (event.results[i].isFinal) {
            finalTranscript += event.results[i][0].transcript;
          }
        }

        if (finalTranscript) {
          this.transcript = finalTranscript;
          this.calculateAccuracy();
          
          // OPTIONAL: If it's a short A1 sentence, stop immediately after first final result
          //this.toggleListening(); 
        }
      };
    },

    playReference() {
      if (this.isPlaying) return; // Prevent overlapping clicks

      window.speechSynthesis.cancel();
      const utterance = new SpeechSynthesisUtterance(this.targetText);
      utterance.lang = 'en-US';

      // Set speed based on current playCount
      utterance.rate = this.playCount === 0 ? 1.0 : 0.3;
      
      // Start state
      this.isPlaying = true;

      // IMPORTANT: The "Switch" happens here
      utterance.onend = () => {
        this.isPlaying = false;
        // Only increment after the first successful play
        if (this.playCount === 0) {
          this.playCount = 1; 
        }
      };

      utterance.onerror = () => {
        this.isPlaying = false;
      };

      window.speechSynthesis.speak(utterance);
    },

    toggleListening() {
      if (!this.recognition) return alert("Browser not supported.");
      if (this.isListening) {
        this.recognition.stop();
        this.isListening = false;
      } else {
        this.transcript = "";
        this.accuracy = null;
        this.recognition.start();
        this.isListening = true;
      }
    },

    resetDemo() {
      this.transcript = "";
      this.accuracy = null;
      this.isListening = false;
      if (this.recognition) this.recognition.stop();
    },

    calculateAccuracy() {
      const clean = (str) => str.toLowerCase().replace(/[^\w\s]/g, "").trim().split(/\s+/);
      const targetWords = clean(this.targetText);
      const spokenWords = clean(this.transcript);
      
      let matchCount = 0;
      targetWords.forEach(word => {
        if (spokenWords.includes(word)) matchCount++;
      });

      this.accuracy = Math.round((matchCount / targetWords.length) * 100);
    },

    resetSilenceTimer() {
        this.clearSilenceTimer();
        this.silenceTimer = setTimeout(() => {
          if (this.isListening) {
            console.log("Auto-stopping due to silence...");
            this.toggleListening();
          }
        }, this.silenceDuration);
      },

    clearSilenceTimer() {
      if (this.silenceTimer) {
        clearTimeout(this.silenceTimer);
        this.silenceTimer = null;
      }
    },

    // Update toggleListening to start the timer
    toggleListening() {
      if (!this.recognition) return alert("Browser not supported.");
      
      if (this.isListening) {
        this.recognition.stop();
        this.clearSilenceTimer();
        this.isListening = false;
      } else {
        this.transcript = "";
        this.accuracy = null;
        this.recognition.start();
        this.isListening = true;
        this.resetSilenceTimer(); // Start the countdown immediately
      }
    },
    
    // Ensure we clean up if the user leaves the page
    beforeUnmount() {
      this.clearSilenceTimer();
      if (this.recognition) this.recognition.stop();
    }    
  }

};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>