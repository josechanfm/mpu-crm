<script setup>
import StaffLayout from '@/Layouts/StaffLayout.vue'
import QRCodeVue3 from 'qrcode-vue3'
import { computed, nextTick, onMounted, ref, watch } from 'vue'

defineOptions({ layout: StaffLayout })

/* ---------- 状态 ---------- */
const url = ref('')
const qrUrl = ref('')
const renderKey = ref(0)
const title = ref('')
const logoDataUrl = ref('')
const logoName = ref('')
const fileInputRef = ref(null)
const qrRef = ref(null)
const composeCanvasRef = ref(null)
const error = ref('')
const copied = ref(false)
const isGenerating = ref(false)
const qrReady = ref(false)

/* ---------- 常量 ---------- */
const QR_SIZE = 512
const COMPOSE_PADDING = 10
const TITLE_HEIGHT = 100

/* ---------- QR 组件 props ---------- */
const qrOptions = {
  typeNumber: 0,
  mode: 'Byte',
  errorCorrectionLevel: 'H',
}

const imageOptions = {
  hideBackgroundDots: true,
  imageSize: 0.28,   // Logo 占二维码 28%
  margin: 2,         // Logo 自身的白边（不是二维码外框）
  crossOrigin: 'anonymous',
}

/* ---------- 生成二维码 ---------- */
async function generateQr() {
  const trimmed = url.value.trim()
  if (!trimmed) {
    error.value = '请输入 URL'
    return
  }
  error.value = ''
  isGenerating.value = true
  qrReady.value = false

  qrUrl.value = ''
  renderKey.value += 1
  await nextTick()

  await new Promise((r) => setTimeout(r, 200))

  qrUrl.value = trimmed
  renderKey.value += 1
  isGenerating.value = false
}

/* ---------- ⭐ isStale：模板中使用，必须定义 ---------- */
const isStale = computed(
  () => !!qrUrl.value && url.value.trim() !== qrUrl.value
)

/* ---------- 等待 QR 组件渲染完成（轮询） ---------- */
async function waitForQrReady(maxTries = 40, interval = 50) {
  for (let i = 0; i < maxTries; i++) {
    await nextTick()
    const el = qrRef.value?.$el || qrRef.value
    if (el && (el.querySelector?.('canvas') || el.querySelector?.('img'))) {
      qrReady.value = true
      return true
    }
    await new Promise((r) => setTimeout(r, interval))
  }
  return false
}

watch(qrUrl, async (val) => {
  if (val) {
    qrReady.value = false
    await waitForQrReady()
  } else {
    qrReady.value = false
  }
})

onMounted(async () => {
  await nextTick()
  if (qrUrl.value) await waitForQrReady()
})

/* ---------- 从 QR 组件提取 data URL ---------- */
async function getQrDataUrl() {
  if (!qrReady.value) {
    const ok = await waitForQrReady()
    if (!ok) throw new Error('二维码尚未渲染完成')
  }

  // 方式 1：getInstance().getRawData('png')
  try {
    const instance = qrRef.value?.getInstance?.()
    if (instance && typeof instance.getRawData === 'function') {
      const blob = await instance.getRawData('png')
      if (blob) return await blobToDataURL(blob)
    }
  } catch (e) {
    console.warn('[QR] getInstance 失败，改用 DOM 提取：', e)
  }

  // 方式 2：从 DOM 提取
  const el = qrRef.value?.$el || qrRef.value
  if (!el) throw new Error('找不到 QR 组件根元素')

  const canvas =
    el.querySelector?.('canvas') ||
    (el instanceof HTMLCanvasElement ? el : null)
  if (canvas && typeof canvas.toDataURL === 'function') {
    return canvas.toDataURL('image/png')
  }

  const img = el.querySelector?.('img')
  if (img && img.src) {
    if (img.src.startsWith('data:')) return img.src
    return await urlToDataURL(img.src)
  }

  throw new Error('无法从 QR 组件获取图片')
}

function blobToDataURL(blob) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader()
    reader.onload = () => resolve(reader.result)
    reader.onerror = reject
    reader.readAsDataURL(blob)
  })
}

async function urlToDataURL(src) {
  const resp = await fetch(src, { mode: 'cors' })
  const blob = await resp.blob()
  return blobToDataURL(blob)
}

/* ---------- Logo 上传 ---------- */
function pickLogo() {
  fileInputRef.value?.click()
}

function onLogoChange(e) {
  const file = e.target.files?.[0]
  if (!file) return
  if (!file.type.startsWith('image/')) {
    error.value = '请选择图片文件'
    return
  }
  const reader = new FileReader()
  reader.onload = () => {
    logoDataUrl.value = reader.result
    logoName.value = file.name
    error.value = ''
  }
  reader.readAsDataURL(file)
}

function removeLogo() {
  logoDataUrl.value = ''
  logoName.value = ''
  if (fileInputRef.value) fileInputRef.value.value = ''
}

/* ---------- 图片加载 ---------- */
function loadImage(src) {
  return new Promise((resolve, reject) => {
    const img = new Image()
    img.crossOrigin = 'anonymous'
    img.onload = () => resolve(img)
    img.onerror = () => reject(new Error('图片加载失败'))
    img.src = src
  })
}

/* ---------- 合成：QR + 标题 → canvas ---------- */
async function composeCanvas() {
  const canvas = composeCanvasRef.value
  if (!canvas) throw new Error('合成 canvas 不存在')

  const ctx = canvas.getContext('2d')
  const hasTitle = title.value.trim().length > 0
  const qrDataUrl = await getQrDataUrl()

  const totalW = QR_SIZE + COMPOSE_PADDING * 2
  const totalH = QR_SIZE + COMPOSE_PADDING * 2 + (hasTitle ? TITLE_HEIGHT : 0)

  canvas.width = totalW
  canvas.height = totalH

  ctx.fillStyle = '#ffffff'
  ctx.fillRect(0, 0, totalW, totalH)

  const qrImg = await loadImage(qrDataUrl)
  ctx.drawImage(qrImg, COMPOSE_PADDING, COMPOSE_PADDING, QR_SIZE, QR_SIZE)

  if (hasTitle) {
    const text = title.value.trim()
    const maxWidth = totalW - COMPOSE_PADDING * 2
    const fontFamily =
      'system-ui, -apple-system, "Segoe UI", "PingFang SC", "Microsoft YaHei", sans-serif'

    let fontSize = 36
    while (fontSize > 14) {
      ctx.font = `bold ${fontSize}px ${fontFamily}`
      if (ctx.measureText(text).width <= maxWidth) break
      fontSize -= 2
    }
    ctx.font = `bold ${fontSize}px ${fontFamily}`
    ctx.fillStyle = '#111827'
    ctx.textAlign = 'center'
    ctx.textBaseline = 'middle'
    ctx.fillText(text, totalW / 2, COMPOSE_PADDING + QR_SIZE + TITLE_HEIGHT / 2)
  }

  return canvas
}

/* ---------- 复制 ---------- */
async function copyImage() {
  if (!qrUrl.value) {
    error.value = '请先生成二维码'
    return
  }
  try {
    const canvas = await composeCanvas()
    const blob = await new Promise((resolve, reject) => {
      canvas.toBlob(
        (b) => (b ? resolve(b) : reject(new Error('导出 Blob 失败'))),
        'image/png'
      )
    })

    if (!navigator.clipboard || !window.ClipboardItem) {
      throw new Error('当前浏览器不支持复制图片，请改用下载')
    }
    await navigator.clipboard.write([new ClipboardItem({ 'image/png': blob })])
    copied.value = true
    setTimeout(() => (copied.value = false), 1500)
    error.value = ''
  } catch (e) {
    console.error('[QR] 复制失败：', e)
    error.value = '复制失败：' + (e.message || '未知错误')
  }
}

/* ---------- 下载 ---------- */
async function downloadWithTitle() {
  if (!qrUrl.value) {
    error.value = '请先生成二维码'
    return
  }
  try {
    const canvas = await composeCanvas()
    const safeName = (title.value.trim() || 'qrcode').replace(/[\\/:*?"<>|]/g, '_')
    const link = document.createElement('a')
    link.download = `${safeName}.png`
    link.href = canvas.toDataURL('image/png')
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    error.value = ''
  } catch (e) {
    console.error('[QR] 下载失败：', e)
    error.value = '下载失败：' + (e.message || '未知错误')
  }
}

/* ---------- URL 变化清除错误 ---------- */
watch(url, () => {
  if (error.value) error.value = ''
})
</script>

<template>
  <div class="max-w-5xl mx-auto px-4 py-8">
    <!-- Header -->
    <header class="mb-6">
      <h1 class="text-2xl md:text-3xl font-bold text-gray-900">QR 码生成器</h1>
      <p class="text-sm text-gray-500 mt-1">
        输入 URL，可选标题与 Logo，点「生成二维码」创建。
      </p>
    </header>

    <div class="grid md:grid-cols-2 gap-5">
      <!-- ============ Left: 设置 ============ -->
      <section class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
        <h2 class="text-base font-semibold text-gray-800 mb-4">设置</h2>

        <!-- URL -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1.5">
            URL <span class="text-red-500">*</span>
          </label>
          <input
            v-model="url"
            type="url"
            placeholder="https://example.com"
            class="w-full h-11 px-3 rounded-xl border border-gray-300 text-sm
                   focus:outline-none focus:ring-2 focus:ring-indigo-500/30
                   focus:border-indigo-500 transition"
            @keyup.enter="generateQr"
          />
          <p v-if="isStale" class="text-xs text-amber-600 mt-1.5">
            输入已变更，点「生成二维码」以刷新。
          </p>
        </div>

        <!-- 标题 -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1.5">
            标题（可选）
          </label>
          <input
            v-model="title"
            type="text"
            maxlength="60"
            placeholder="显示在二维码下方"
            class="w-full h-11 px-3 rounded-xl border border-gray-300 text-sm
                   focus:outline-none focus:ring-2 focus:ring-indigo-500/30
                   focus:border-indigo-500 transition"
          />
          <p class="text-xs text-gray-400 mt-1">
            {{ title.length }} / 60，过长会自动缩小字号
          </p>
        </div>

        <!-- Logo -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1.5">
            Logo（可选）
          </label>
          <input
            ref="fileInputRef"
            type="file"
            accept="image/*"
            class="hidden"
            @change="onLogoChange"
          />
          <div class="flex items-center gap-2 flex-wrap">
            <button
              type="button"
              class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-lg
                     text-sm font-semibold bg-indigo-50 text-indigo-700
                     border border-indigo-200 hover:bg-indigo-100
                     hover:border-indigo-300 transition"
              @click="pickLogo"
            >
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2"
                   stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                <circle cx="8.5" cy="8.5" r="1.5"/>
                <path d="M21 15l-5-5L5 21"/>
              </svg>
              选择图片
            </button>
            <button
              v-if="logoDataUrl"
              type="button"
              class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-lg
                     text-sm font-semibold bg-red-50 text-red-700
                     border border-red-200 hover:bg-red-100
                     hover:border-red-300 transition"
              @click="removeLogo"
            >
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2"
                   stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                <path d="M10 11v6M14 11v6"/>
                <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/>
              </svg>
              移除
            </button>
            <span
              v-if="logoName"
              class="text-xs text-gray-500 truncate max-w-[160px]"
              :title="logoName"
            >
              {{ logoName }}
            </span>
          </div>
          <p class="text-xs text-gray-400 mt-1">
            图片会自动缩放并放置在二维码中央。
          </p>
        </div>

        <!-- ============ 生成按钮 ============ -->
        <button
          type="button"
          :disabled="!url.trim() || isGenerating"
          class="w-full inline-flex items-center justify-center gap-2 h-12 rounded-xl
                 text-sm font-semibold text-white
                 transition disabled:cursor-not-allowed"
          :style="{
            backgroundColor: (!url.trim() || isGenerating) ? '#d1d5db' : '#4f46e5',
            color: (!url.trim() || isGenerating) ? '#9ca3af' : '#ffffff',
            boxShadow: (!url.trim() || isGenerating)
              ? 'none'
              : '0 4px 12px rgba(79, 70, 229, 0.3)',
          }"
          @mouseenter="(e) => {
            if (url.trim() && !isGenerating) e.currentTarget.style.backgroundColor = '#4338ca'
          }"
          @mouseleave="(e) => {
            if (url.trim() && !isGenerating) e.currentTarget.style.backgroundColor = '#4f46e5'
          }"
          @click="generateQr"
        >
          <svg
            v-if="isGenerating"
            class="w-4 h-4 animate-spin"
            viewBox="0 0 24 24"
            fill="none"
            aria-hidden="true"
          >
            <circle cx="12" cy="12" r="9" stroke="currentColor"
                    stroke-width="3" opacity="0.25"/>
            <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor"
                  stroke-width="3" stroke-linecap="round"/>
          </svg>
          <svg
            v-else
            class="w-4 h-4"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
          >
            <rect x="3" y="3" width="7" height="7" rx="1"/>
            <rect x="14" y="3" width="7" height="7" rx="1"/>
            <rect x="3" y="14" width="7" height="7" rx="1"/>
            <path d="M14 14h3v3M21 14v0M14 21h3M21 21v0M17 17v4M21 17v4"/>
          </svg>

          {{ isGenerating ? '生成中...' : (qrUrl ? '重新生成' : '生成二维码') }}
        </button>

        <p class="text-xs text-gray-400 mt-2 text-center">
          生成后可在右侧预览、复制或下载。
        </p>

        <p v-if="error" class="text-sm text-red-600 mt-3">{{ error }}</p>
      </section>

      <!-- ============ Right: 预览 ============ -->
      <section class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
        <h2 class="text-base font-semibold text-gray-800 mb-4">预览</h2>

        <div
          class="flex flex-col items-center justify-center bg-gray-50 rounded-xl p-4 min-h-[260px]"
        >
<QRCodeVue3
  v-if="qrUrl"
  :key="renderKey"
  ref="qrRef"
  :value="qrUrl"
  :width="256"
  :height="256"
  :qrOptions="qrOptions"
  :image="logoDataUrl || undefined"
  :imageOptions="imageOptions"
  :dotsOptions="{ type: 'square', color: '#111827' }"
  :backgroundOptions="{ color: '#ffffff' }"
  :cornersSquareOptions="{ type: 'square', color: '#111827' }"
  :cornersDotOptions="{ type: 'square', color: '#111827' }"
  :margin="4"
  myclass="qr-container"
  imgclass="qr-img"
/>

          <div
            v-else
            class="w-[256px] h-[256px] flex flex-col items-center justify-center gap-2
                   text-gray-400 text-sm bg-white rounded-lg border border-dashed border-gray-300"
          >
            <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.5"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <rect x="3" y="3" width="7" height="7" rx="1"/>
              <rect x="14" y="3" width="7" height="7" rx="1"/>
              <rect x="3" y="14" width="7" height="7" rx="1"/>
              <path d="M14 14h3v3M21 14v0M14 21h3M21 21v0M17 17v4M21 17v4"/>
            </svg>
            <span>点「生成二维码」创建</span>
          </div>

          <p
            v-if="title.trim() && qrUrl"
            class="mt-3 text-center font-bold text-gray-900 px-2 break-words max-w-full"
            :style="{ fontSize: title.length > 30 ? '13px' : '16px' }"
          >
            {{ title.trim() }}
          </p>
        </div>

        <div class="flex gap-2 mt-4">
          <button
            type="button"
            :disabled="!qrUrl"
            class="flex-1 inline-flex items-center justify-center gap-1.5 h-11 rounded-xl
                   text-sm font-semibold text-white
                   transition disabled:cursor-not-allowed"
            :style="{
              backgroundColor: !qrUrl ? '#e5e7eb' : '#4f46e5',
              color: !qrUrl ? '#9ca3af' : '#ffffff',
              boxShadow: !qrUrl ? 'none' : '0 4px 12px rgba(79, 70, 229, 0.3)',
            }"
            @click="copyImage"
          >
            <svg v-if="copied" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
            <svg v-else class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <rect x="9" y="9" width="13" height="13" rx="2"/>
              <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
            </svg>
            {{ copied ? '已复制' : '复制图片' }}
          </button>
          <button
            type="button"
            :disabled="!qrUrl"
            class="flex-1 inline-flex items-center justify-center gap-1.5 h-11 rounded-xl
                   text-sm font-semibold bg-gray-100 text-gray-700
                   border border-gray-200 hover:bg-gray-200 transition
                   disabled:opacity-50 disabled:cursor-not-allowed"
            @click="downloadWithTitle"
          >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
              <polyline points="7 10 12 15 17 10"/>
              <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            下载 PNG
          </button>
        </div>

        <p class="text-xs text-gray-400 mt-3 text-center">
          复制与下载的图片包含二维码与标题，可直接粘贴到聊天或文档。
        </p>
      </section>
    </div>

    <!-- 隐藏的合成 canvas -->
    <canvas ref="composeCanvasRef" class="hidden" aria-hidden="true"></canvas>
  </div>
</template>

<style scoped>
.qr-container {
  display: flex;
  align-items: center;
  justify-content: center;
}
.qr-img {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
}
</style>