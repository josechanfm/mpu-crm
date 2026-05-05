<template>
  <div class="souvenir-manual-wrapper">
    <!-- 顯示/隱藏手冊按鈕 -->
    <div class="flex justify-center mb-6">
      <a-button
        type="primary"
        size="large"
        @click="toggleManual"
        class="shadow-md hover:shadow-lg transition-all duration-200 rounded-full px-6 py-2 text-base font-medium"
        :class="[showManual ? 'bg-indigo-700 border-indigo-700' : 'bg-indigo-600 hover:bg-indigo-700']"
      >
        <span v-if="!showManual" class="flex items-center gap-2">
          <span>📘</span> 顯示使用者手冊
        </span>
        <span v-else class="flex items-center gap-2">
          <span>📖</span> 隱藏使用者手冊
        </span>
      </a-button>
    </div>

    <!-- 使用者手冊內容（預設隱藏） -->
    <div v-if="showManual" class="manual-container animate-fade-in">
      <a-card :bordered="false" class="rounded-2xl shadow-xl overflow-hidden">
        <div class="p-1 md:p-2">
          <!-- 標題與簡介 -->
          <div class="mb-8 border-b border-gray-100 pb-4">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-3xl">📌</span>
              <a-typography-title :level="3" class="!text-gray-800 !mb-0">完整使用者操作手冊</a-typography-title>
            </div>
            <a-typography-paragraph class="text-gray-600 text-base leading-relaxed">
              <strong>我的紀念品購買系統</strong> 提供澳門理工大學有NetId的用戶（（學生、教師、職員）購買校園紀念品。
              管理員可透過靈活的匯入機制、新增用戶名單或修改購買權限。系統同時提供有庫存、限額及期限等控制以及提供掃描 QR Code 領取驗證，輕鬆管理整個購買流程。
              本章節詳述 <strong>使用者管理、紀念品庫存、訂單歷程、領取作業及購買操作流程</strong>。
            </a-typography-paragraph>
            <a-divider class="!my-3" />
            <div class="flex flex-wrap gap-3 mt-2">
              <a-tag color="blue" class="px-3 py-1 rounded-full">NetId 唯一識別</a-tag>
              <a-tag color="green" class="px-3 py-1 rounded-full">歷史紀錄保留</a-tag>
              <a-tag color="orange" class="px-3 py-1 rounded-full">CSV 匯入／匯出</a-tag>
              <a-tag color="purple" class="px-3 py-1 rounded-full">QR Code 掃描領取</a-tag>
            </div>
          </div>

          <!-- 兩欄式：使用者管理 + 紀念品庫存定義 -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-7 mb-10">
            <!-- 左：使用者管理與匯入 -->
            <a-card :bordered="false" class="bg-gray-50/80 rounded-xl shadow-sm hover:shadow-md transition">
              <div class="flex items-center gap-2 mb-3">
                <span class="text-2xl">👥</span>
                <a-typography-title :level="4" class="!mb-0">使用者管理與匯入</a-typography-title>
              </div>
              <a-typography-paragraph class="text-gray-700">
                所有 <strong>NetId 使用者</strong>（學校、教師、職員）皆可透過批次匯入方式管理。系統保留每位使用者的完整歷史紀錄，確保資料一致性。
              </a-typography-paragraph>
              <a-divider class="!my-3" />
              <ul class="list-disc pl-5 space-y-2 text-gray-700">
                <li><span class="font-semibold">唯一識別欄位：</span><code class="bg-gray-200 px-1.5 py-0.5 rounded text-sm">NetId</code> – 避免重複建立。</li>
                <li><span class="font-semibold">匯入功能：</span> CSV 檔案可同時 <strong>新增使用者</strong> 與 <strong>修改購買限制</strong>（例如每年可購買資格）。</li>
                <li><span class="font-semibold">"can_buy" 旗標：</span> 設定 <code class="bg-gray-200 px-1 rounded">can_buy = 0</code> 可停用購買權限，但仍保留使用者資料。重新匯入更新該旗標即可改變狀態。</li>
                <li><span class="font-semibold">歷史完整性：</span> 所有舊紀錄皆留存於資料庫；被停用的使用者僅標記為不可購買，絕不刪除，確保訂單關聯性。</li>
                <li class="text-indigo-700 text-sm"><span class="font-medium">範例：</span> 年度紀念品購買名單 — 匯入 <kbd class="bg-gray-200 px-1">netid, can_buy</kbd>；設定 <kbd>can_buy=0</kbd> 即可取消該年度的購買資格。</li>
              </ul>
              <div class="mt-4 p-3 bg-blue-50 rounded-lg text-sm text-blue-800">
                💡 <span class="font-medium">提示：</span> 修改使用者購買限制後，前端按鈕會根據新的「can_buy」狀態即時更新可用性。
              </div>
            </a-card>

            <!-- 右：紀念品庫存邏輯 -->
            <a-card :bordered="false" class="bg-gray-50/80 rounded-xl shadow-sm hover:shadow-md transition">
              <div class="flex items-center gap-2 mb-3">
                <span class="text-2xl">🎁</span>
                <a-typography-title :level="4" class="!mb-0">紀念品庫存定義</a-typography-title>
              </div>
              <div class="space-y-3">
                <div class="border-b border-gray-200 pb-2">
                  <span class="font-bold text-gray-800">📦 Stock（總倉存量）</span>
                  <p class="text-sm text-gray-500 mt-0.5">所有訂單的整體庫存參考數量（靜態總量）。</p>
                </div>
                <div class="border-b border-gray-200 pb-2">
                  <span class="font-bold text-gray-800">⚖️ Quota（每人限購量）</span>
                  <p class="text-sm text-gray-500">每位 NetId 使用者可購買此紀念品的最大數量。</p>
                </div>
                <div class="border-b border-gray-200 pb-2">
                  <span class="font-bold text-gray-800">✅ Available（現有可購買存量）</span>
                  <p class="text-sm text-gray-500">隨著訂單成立而動態減少的即時可售數量。</p>
                </div>
                <div class="border-b border-gray-200 pb-2">
                  <span class="font-bold text-gray-800">⏳ Available To（購買期限）</span>
                  <p class="text-sm text-gray-500">該紀念品可供購買的最後日期／時間。</p>
                </div>
                <div>
                  <span class="font-bold text-gray-800">🔘 Is Available（上架供購買與否）</span>
                  <p class="text-sm text-gray-500">管理員可手動開啟或關閉銷售（不受庫存與限額影響）。</p>
                </div>
              </div>
              <a-divider class="!my-3" />
              <div class="bg-amber-50 rounded-lg p-2.5 text-sm">
                <span class="font-semibold">📐 範例：</span> Stock = 200，Quota = 每人 2 件，Available = 156 表示已賣出／預訂 44 件。當某使用者已達限購數量，購買按鈕將自動停用。
              </div>
            </a-card>
          </div>

          <!-- 訂單與領取管理（兩欄） -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-7 mb-10">
            <!-- 訂單紀錄 -->
            <a-card :bordered="false" class="bg-white border border-gray-100 rounded-xl shadow-sm">
              <div class="flex items-center gap-2 mb-2">
                <span class="text-2xl">🧾</span>
                <a-typography-title :level="4" class="!mb-0">訂單與交易紀錄</a-typography-title>
              </div>
              <a-typography-paragraph class="text-gray-700">
                <strong>訂單資料表</strong> 包含所有購買記錄 — 無論是已成功支付或尚未支付（待付款）。管理員可 <strong>匯出訂單紀錄</strong> 以進行對帳及數據分析。
              </a-typography-paragraph>
              <ul class="list-disc pl-5 space-y-1.5 text-gray-700">
                <li>每一筆訂單包含：訂單編號、NetId、購買品項、數量、總金額、付款狀態、時間戳記。</li>
                <li>匯出格式支援 CSV / Excel，包含完整訂單後設資料。</li>
                <li>未付款訂單會保留紀錄便於追蹤，但不會影響庫存，直至完成付款。</li>
                <li>歷史資料支援稽核軌跡及購買行為分析。</li>
              </ul>
              <div class="mt-3 flex flex-wrap gap-2">
                <a-tag color="processing">可匯出報表</a-tag>
                <a-tag color="default">金流串接</a-tag>
              </div>
            </a-card>

            <!-- 領取管理（管理員 QR 掃描） -->
            <a-card :bordered="false" class="bg-white border border-gray-100 rounded-xl shadow-sm">
              <div class="flex items-center gap-2 mb-2">
                <span class="text-2xl">📱</span>
                <a-typography-title :level="4" class="!mb-0">領取管理 — QR Code 確認</a-typography-title>
              </div>
              <a-typography-paragraph class="text-gray-700">
                <strong>管理員專用功能</strong>。管理員登入後，可透過掃描購買收據上的 QR Code 來驗證購買紀錄，並將該筆訂單標記為「已領取」。
              </a-typography-paragraph>
              <ul class="list-disc pl-5 space-y-1.5 text-gray-700">
                <li>掃描 QR Code 後系統自動帶入訂單資訊。</li>
                <li>領取方式為 <strong>一次性整筆訂單領取</strong>（不支援部分領取）。</li>
                <li>確認領取後，該訂單狀態變更為「已提取」，不可重複領取。</li>
                <li>有效降低人工核對錯誤，提升現場發放效率。</li>
              </ul>
              <div class="mt-3 p-2 bg-green-50 rounded-lg text-green-800 text-sm">
                🔔 注意：領取作業僅能整筆完成，無法只領取訂單中的部分商品。
              </div>
            </a-card>
          </div>

          <!-- 使用者購買操作流程（獨立區塊） -->
          <a-card :bordered="false" class="bg-indigo-50/40 rounded-xl border border-indigo-100">
            <div class="flex items-center gap-2 mb-3">
              <span class="text-2xl">🛒</span>
              <a-typography-title :level="4" class="!mb-0">使用者購買操作流程</a-typography-title>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <a-steps direction="vertical" :current="2" status="finish" class="custom-steps">
                  <a-step title="瀏覽紀念品公開頁面" description="https://venus.mpu.edu.mo/souvenirs 顯示所有可購買品項，未登入時選購按鈕無法操作。" />
                  <a-step title="登入 NetId 帳號" description="系統辨識使用者身份並載入個人購買限額及已購買數量。" />
                  <a-step title="檢視可購買數量" description="登入後按鈕上會顯示「尚可購買數量 / 已選購數量」，若已達限購額則按鈕自動停用。" />
                  <a-step title="加入購物車並結帳" description="選擇數量、確認訂單，進入付款流程。" />
                </a-steps>
              </div>
              <div class="bg-white p-4 rounded-xl shadow-sm">
                <p class="font-semibold text-gray-800 flex items-center gap-1">
                  <span>✅</span> 前端即時驗證邏輯
                </p>
                <p class="text-sm text-gray-600 mt-2">若使用者的 NetId 被標記為 <code class="bg-gray-100 px-1 rounded">can_buy = 0</code>，或該紀念品已超過個人限額／庫存不足／超過銷售期限，購買按鈕將顯示為不可操作。系統會根據 <strong>訂單歷史＋限額＋庫存</strong> 即時更新按鈕狀態，確保公平購買。</p>
                <p class="font-semibold text-gray-800 flex items-center gap-1"> 
                  <span>✅</span> 雙重確保機制（購買前存量與限額再驗證）
                </p>
                <p class="text-sm text-gray-600 mt-2"> 第一次確認：使用者在購物車中確認購買紀念品後，系統會立即檢查該品項的當前可供購買數量（available）以及使用者個人的限購額度（quota）。若條件符合，系統才會跳轉至付款確認頁面，讓使用者清楚知悉購買項目、數量及總金額。 </p>
                <p class="text-sm text-gray-600 mt-2"> 第二次確認：使用者確認付款、即將跳轉至銀行支付平台之前，系統會再次核對，確認剩餘存量是否足夠、採購數量是否仍在限額內。唯有二次條件皆符合，才會正式導向銀行支付介面。 </p> <p class="text-sm text-gray-600 mt-2"> 不論第一次或第二次核對過程中，若有任一條件不符合（存量不足、超過限額、商品已下架等），系統將直接跳轉至「購買不成功」的提示頁面。 </p><!-- ，並保留購物車內容供使用者調整 -->
                <div class="mt-3 text-xs text-gray-500 border-t pt-2">📌 管理員可隨時調整「是否開放購買」開關或匯入新名單，無需重新部署系統。</div>
              </div>
            </div>
            <a-divider class="!my-4" />
            <div class="text-center text-gray-500 text-sm">
              <span>🔒 所有使用者資料及訂單皆受保護，歷史紀錄永不遺失，確保完整購買歷程。</span>
            </div>
          </a-card>
        </div>
      </a-card>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

// 手冊顯示狀態，預設為隱藏
const showManual = ref(false);

// 切換顯示／隱藏
const toggleManual = () => {
  showManual.value = !showManual.value;
};
</script>

<style scoped>
/* 淡入動畫 */
.animate-fade-in {
  animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* 調整 Ant Design Steps 垂直間距（選擇性） */
.custom-steps .ant-steps-item-description {
  font-size: 0.85rem;
  color: #4b5563;
}
</style>