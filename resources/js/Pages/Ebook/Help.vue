<script setup>
import WebLayout from '@/Layouts/WebLayout.vue'
import { reactive } from 'vue'

const tools = [
  { label: 'PDFUX — 新增空白頁',          host: 'pdfux.com',   href: 'https://pdfux.com/add-blank-pages-pdf/' },
  { label: 'iLovePDF — 如何新增頁面至 PDF', host: 'ilovepdf.com', href: 'https://www.ilovepdf.com/blog/how-to-add-pages-to-pdf' },
  { label: 'PDFgear — 新增頁面至 PDF',     host: 'pdfgear.com', href: 'https://www.pdfgear.com/add-pages-to-pdf/' },
]

const limits = [
  { label: '支援格式',     value: 'PDF（.pdf）' },
  { label: '單檔大小上限', value: '小於 20 MB' },
  { label: '建議頁數',     value: '建議 100 頁以內，以維持良好的轉檔與閱讀效能' },
  { label: '轉檔時間',     value: '依頁數與檔案大小而異，頁數越多、圖檔越大，等待時間越長' },
]
const faqs = [
  { q: '為什麼要讓 PDF 頁數變成奇數？', a: '翻頁書內頁採雙頁對開顯示。頁數為偶數時，最後兩頁一定會成對；頁數為奇數時，最後一頁會單獨顯示，系統也可在書末自動補上空白頁，使整本書的翻閱節奏更完整。' },
  { q: 'PDF 超過 20 MB 怎麼辦？', a: '請先壓縮 PDF（例如使用「減少檔案大小」功能），或將內容拆成多個 PDF 分次建立翻頁書。常見的壓縮工具包括 Adobe Acrobat、iLovePDF 與 Smallpdf。' },
  { q: '送出後可以修改內容嗎？', a: '轉檔完成後翻頁書內容即固定。若需修改，請重新整理 PDF 後建立一本新的翻頁書，再將舊的連結替換或刪除。' },
  { q: '轉檔失敗怎麼辦？', a: '請先確認：（1）檔案確實為 PDF 格式；（2）檔案未加密或設定密碼；（3）檔案大小未超過 20 MB。若仍失敗，請嘗試另存新檔後重新上傳，或聯絡系統管理員。' },
  { q: '翻頁書可以在手機上閱讀嗎？', a: '可以。翻頁書支援響應式版面，於手機、平板與桌機皆可正常閱讀，並支援觸控手勢翻頁。' },
]

const openFaqs = reactive(new Set())
function toggleFaq(i) {
  openFaqs.has(i) ? openFaqs.delete(i) : openFaqs.add(i)
}
</script>

<template>
  <WebLayout title="電子書">
  <div class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <!-- Hero -->
    <header class="bg-gradient-to-br from-slate-800 via-slate-700 to-slate-800 text-white">
      <div class="max-w-4xl mx-auto px-6 py-14 md:py-16 text-center">
        <span class="inline-block mb-4 px-4 py-1 text-xs tracking-[0.2em] border border-white/40 rounded-full text-white/90">
          使用說明
        </span>
        <h1 class="text-2xl md:text-4xl font-bold tracking-wide mb-3">PDF 轉電子翻頁書</h1>
        <p class="text-sm md:text-base text-white/80 max-w-2xl mx-auto leading-relaxed">
          將您的 PDF 檔案轉換為可線上翻閱的電子書，支援多種版型與跨頁顯示。
        </p>
      </div>
    </header>

    <main class="max-w-4xl mx-auto px-5 md:px-6 py-10 md:py-14 space-y-6">

      <!-- 1. 功能簡介 -->
      <section class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 md:p-8">
        <h2 class="flex items-center gap-3 text-lg md:text-xl font-bold text-slate-800 mb-5">
          <span class="flex items-center justify-center w-8 h-8 rounded-full bg-slate-800 text-white text-sm">1</span>
          功能簡介
        </h2>
        <p class="text-slate-700 leading-relaxed mb-4">
          「PDF 轉電子翻頁書」可將您上傳的 PDF 檔案，轉換為具有翻頁動畫效果的電子書。
          轉檔後的翻頁書可於瀏覽器中直接閱讀，支援滑鼠拖曳、鍵盤方向鍵與觸控手勢翻頁，
          並可套用不同的<strong class="text-slate-900">版型</strong>呈現不同的閱讀風格。
        </p>
        <div class="rounded-lg border-l-4 border-sky-400 bg-sky-50 px-4 py-3 text-sm">
          <div class="font-bold text-sky-800 mb-1 tracking-wide text-xs uppercase">適用對象</div>
          <div class="text-slate-700">講義、產品型錄、雜誌、報告、作品集等，任何以 PDF 形式編排好的文件。</div>
        </div>
      </section>


      <!-- 5. 頁數與空白頁設定 -->
      <section class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 md:p-8">
        <h2 class="flex items-center gap-3 text-lg md:text-xl font-bold text-slate-800 mb-4">
          <span class="flex items-center justify-center w-8 h-8 rounded-full bg-slate-800 text-white text-sm">5</span>
          頁數與空白頁設定
        </h2>

        <p class="text-slate-700 leading-relaxed">
          電子翻頁書在閱讀時採<strong class="text-slate-900">雙頁對開</strong>顯示（封面單頁、內頁成對）。
          若您希望達成以下任一效果，請<strong class="text-slate-900">將 PDF 的總頁數調整為奇數</strong>：
        </p>

        <ul class="list-disc pl-6 text-slate-700 leading-relaxed space-y-1 my-3">
          <li>希望在書末自動補上一頁<strong class="text-slate-900">空白頁</strong>；或</li>
          <li>希望<strong class="text-slate-900">最後一頁單獨顯示</strong>，不與前一頁成對。</li>
        </ul>

        <div class="rounded-lg border-l-4 border-amber-400 bg-amber-50 px-4 py-3 text-sm my-4">
          <div class="font-bold text-amber-800 mb-1 tracking-wide text-xs uppercase">重點</div>
          <div class="text-slate-700 leading-relaxed">
            當 PDF 總頁數為<strong>偶數</strong>時，最後兩頁會成對顯示；<br>
            當 PDF 總頁數為<strong>奇數</strong>時，最後一頁會單獨顯示（並於需要時補上空白頁）。
          </div>
        </div>

        <h3 class="font-semibold text-slate-900 mt-6 mb-2">如何調整頁數？</h3>
        <p class="text-slate-700 leading-relaxed">
          請於<strong class="text-slate-900">上傳前</strong>使用 Adobe Acrobat 或其他 PDF 編輯工具，於需要的位置插入或刪除一頁，
          使總頁數成為奇數。若您手邊沒有編輯工具，可使用下列線上服務（免費、免安裝）：
        </p>

        <ul class="mt-3 divide-y divide-slate-200">
          <li v-for="tool in tools" :key="tool.href" class="py-3">
            <a
              :href="tool.href"
              target="_blank"
              rel="noopener"
              class="text-slate-800 font-semibold hover:text-slate-950 hover:underline break-all"
            >
              {{ tool.label }}
            </a>
            <span class="ml-2 text-xs text-slate-500">{{ tool.host }}</span>
          </li>
        </ul>

        <div class="mt-4 rounded-lg border-l-4 border-sky-400 bg-sky-50 px-4 py-3 text-sm">
          <div class="font-bold text-sky-800 mb-1 tracking-wide text-xs uppercase">小技巧</div>
          <div class="text-slate-700 leading-relaxed">
            多數線上工具支援「於指定頁碼前後插入空白頁」，只需於送出前多加一頁即可。
            若您的 PDF 已有封面設計，請保留封面，僅調整內頁頁數。
          </div>
        </div>
      </section>

      <!-- 6. 上傳限制 -->
      <section class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 md:p-8">
        <h2 class="flex items-center gap-3 text-lg md:text-xl font-bold text-slate-800 mb-4">
          <span class="flex items-center justify-center w-8 h-8 rounded-full bg-slate-800 text-white text-sm">6</span>
          上傳限制與處理時間
        </h2>

        <div class="overflow-hidden rounded-lg border border-slate-200">
          <table class="w-full text-sm">
            <tbody class="divide-y divide-slate-200">
              <tr v-for="l in limits" :key="l.label">
                <th class="w-2/5 bg-slate-50 text-left font-bold text-slate-700 px-4 py-3">
                  {{ l.label }}
                </th>
                <td class="px-4 py-3 text-slate-700">{{ l.value }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="mt-5 rounded-lg border-l-4 border-amber-400 bg-amber-50 px-4 py-3 text-sm">
          <div class="font-bold text-amber-800 mb-1 tracking-wide text-xs uppercase">請耐心等候</div>
          <div class="text-slate-700 leading-relaxed">
            送出後請勿關閉頁面或重複送出，待系統完成轉檔後會自動跳轉或提示。
            若檔案接近 20 MB，或頁數較多（例如 200 頁以上），轉檔時間可能長達數分鐘。
          </div>
        </div>

        <div class="mt-3 rounded-lg border-l-4 border-emerald-400 bg-emerald-50 px-4 py-3 text-sm">
          <div class="font-bold text-emerald-800 mb-1 tracking-wide text-xs uppercase">轉檔完成後</div>
          <div class="text-slate-700">您將取得翻頁書的專屬連結，可於任何裝置的瀏覽器中開啟閱讀。</div>
        </div>
      </section>

      <!-- 7. FAQ -->
      <section class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 md:p-8">
        <h2 class="flex items-center gap-3 text-lg md:text-xl font-bold text-slate-800 mb-4">
          <span class="flex items-center justify-center w-8 h-8 rounded-full bg-slate-800 text-white text-sm">7</span>
          常見問題
        </h2>

        <div class="divide-y divide-slate-200">
          <div v-for="(faq, i) in faqs" :key="i" class="py-3">
            <button
              type="button"
              @click="toggleFaq(i)"
              class="w-full flex items-center justify-between gap-3 text-left font-semibold text-slate-800 hover:text-slate-950"
            >
              <span>{{ faq.q }}</span>
              <span
                class="shrink-0 text-slate-400 text-xl leading-none transition-transform duration-200"
                :class="{ 'rotate-45': openFaqs.has(i) }"
              >+</span>
            </button>
            <div
              v-show="openFaqs.has(i)"
              class="text-sm text-slate-600 leading-relaxed mt-2"
            >
              {{ faq.a }}
            </div>
          </div>
        </div>
      </section>

    </main>

    <footer class="text-center text-xs text-slate-500 py-8">
      PDF 轉電子翻頁書 · 使用說明
    </footer>
  </div>
</WebLayout>
</template>