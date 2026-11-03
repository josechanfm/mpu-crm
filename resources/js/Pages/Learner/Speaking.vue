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
  props: {
    lang: { type: String, default: 'en-US' },
    level: { type: String, default: 'A1' },
    translationLang: { type: String, default: 'vi' }
  },

  data() {
    return {
      targetText: '',
      translation: '',
      showTranslation: false,
      transcript: '',
      isListening: false,
      accuracy: null,
      loading: false,
      recognition: null,

      // ─── Timing tuned for slow / young speakers ────────────────
      // Give the user plenty of time to think and speak slowly.
      silenceDuration: 15000, // 15s of total silence before auto-stop (was 4000)
      // After a final result on a short sentence, wait a bit before stopping.
      // Cancelled if any new speech comes in, so it won't cut a child off.
      autoStopDelay: 3000,    // 3s (was 700)
      autoStopShortSentences: true,
      shortSentenceWordLimit: 8,

      silenceTimer: null,
      autoStopTimer: null,
      playCount: 0,
      isPlaying: false
    };
  },

  computed: {
    recognitionLang() {
      return this.lang && this.lang.startsWith('en') ? this.lang : 'en-US';
    },
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

  beforeUnmount() {
    this.clearSilenceTimer();
    this.cancelAutoStop();
    if (this.recognition) this.recognition.stop();
    if (window.speechSynthesis) window.speechSynthesis.cancel();
  },

  methods: {
    async fetchNewMaterial() {
      this.playCount = 0;
      this.loading = true;
      this.resetDemo();
      this.showTranslation = false;

      try {
        const response = await axios.get('/learner/get_material', {
          params: {
            type: 'speech',
            level: this.level,
            lang: this.lang,
            translation_lang: this.translationLang
          }
        });
        if (response.data) {
          this.targetText = response.data.content;
          this.translation = response.data.translation;
        }
      } catch (error) {
        console.error('Error fetching material:', error);
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
      this.recognition.lang = this.recognitionLang;

      this.recognition.onend = () => {
        this.isListening = false;
        this.clearSilenceTimer();
        this.cancelAutoStop();
      };

      this.recognition.onresult = (event) => {
        // Any new speech (interim OR final) means the user is still talking.
        // Cancel any pending auto-stop and restart the silence countdown.
        this.cancelAutoStop();
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

          const wordCount = this.targetText.trim().split(/\s+/).length;
          if (this.autoStopShortSentences && wordCount <= this.shortSentenceWordLimit) {
            this.scheduleAutoStop(this.autoStopDelay);
          }
        }
      };
    },

    playReference() {
      if (this.isPlaying) return;
      window.speechSynthesis.cancel();

      const utterance = new SpeechSynthesisUtterance(this.targetText);
      utterance.lang = this.recognitionLang;
      utterance.rate = this.playCount === 0 ? 1.0 : 0.6; // normal, then slow

      const voices = window.speechSynthesis.getVoices();
      const englishVoice = voices.find(v => v.lang && v.lang.toLowerCase().startsWith('en'));
      if (englishVoice) utterance.voice = englishVoice;

      this.isPlaying = true;
      utterance.onend = () => {
        this.isPlaying = false;
        if (this.playCount === 0) this.playCount = 1;
      };
      utterance.onerror = () => { this.isPlaying = false; };

      window.speechSynthesis.speak(utterance);
    },

    toggleListening() {
      if (!this.recognition) return alert('Browser not supported.');

      if (this.isListening) {
        this.recognition.stop();
        this.clearSilenceTimer();
        this.cancelAutoStop();
        this.isListening = false;
      } else {
        this.transcript = '';
        this.accuracy = null;
        this.recognition.lang = this.recognitionLang;
        this.recognition.start();
        this.isListening = true;
        this.resetSilenceTimer();
      }
    },

    resetDemo() {
      this.transcript = '';
      this.accuracy = null;
      this.isListening = false;
      this.clearSilenceTimer();
      this.cancelAutoStop();
      if (this.recognition) this.recognition.stop();
    },

    calculateAccuracy() {
      const clean = (str) =>
        str.toLowerCase().replace(/[^\w\s]/g, '').trim().split(/\s+/).filter(Boolean);
      const targetWords = clean(this.targetText);
      const spokenWords = clean(this.transcript);

      let matchCount = 0;
      targetWords.forEach(word => {
        if (spokenWords.includes(word)) matchCount++;
      });

      this.accuracy = targetWords.length
        ? Math.round((matchCount / targetWords.length) * 100)
        : 0;
    },

    // ─── Silence / auto-stop timers ────────────────────────────

    resetSilenceTimer() {
      this.clearSilenceTimer();
      this.silenceTimer = setTimeout(() => {
        this.silenceTimer = null;
        if (this.isListening) {
          console.log(`Auto-stopping after ${this.silenceDuration}ms of silence.`);
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

    // Auto-stop after a final result — but any new speech cancels it.
    scheduleAutoStop(delay) {
      this.cancelAutoStop();
      this.autoStopTimer = setTimeout(() => {
        this.autoStopTimer = null;
        if (this.isListening) this.toggleListening();
      }, delay);
    },

    cancelAutoStop() {
      if (this.autoStopTimer) {
        clearTimeout(this.autoStopTimer);
        this.autoStopTimer = null;
      }
    }
  }
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>