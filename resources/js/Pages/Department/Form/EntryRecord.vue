<template>
  <DepartmentLayout title="View Entry">
    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            📄 View Record
          </h2>
          <a-button :href="route('manage.form.entries.index', form.id)" type="primary">
            ← Back to List
          </a-button>
        </div>

        <div class="p-6 space-y-6">
          <!-- Member ID (if required) -->
          <div v-if="form.require_member" class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Member ID</div>
            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
              {{ entry.member_id || '—' }}
            </div>
          </div>

          <!-- Fields -->
          <div class="divide-y divide-gray-200 dark:divide-gray-700">
            <div
              v-for="field in formFields"
              :key="field.id"
              class="py-4 first:pt-0"
            >
              <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                <!-- Label -->
                <div class="sm:w-1/5 flex-shrink-0">
                  <div class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ field.field_label }}
                    <span v-if="field.required" class="text-red-500 ml-1">*</span>
                  </div>
                  <div class="text-xs text-gray-400 dark:text-gray-500 uppercase">
                    {{ field.type }}
                  </div>
                </div>

                <!-- Value -->
                <div class="sm:w-4/5 text-gray-900 dark:text-gray-100">
                  <!-- Input / Number / Email -->
                  <template v-if="['input', 'number', 'email'].includes(field.type)">
                    <span class="break-all">{{ field.entry_record?.field_value || '—' }}</span>
                  </template>

                  <!-- Textarea / Longtext -->
                  <template v-else-if="['textarea', 'longtext'].includes(field.type)">
                    <div class="whitespace-pre-wrap bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg text-sm">
                      {{ field.entry_record?.field_value || '—' }}
                    </div>
                  </template>

                  <!-- Richtext -->
                  <template v-else-if="field.type === 'richtext'">
                    <div
                      class="prose prose-sm max-w-none dark:prose-invert bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg"
                      v-html="field.entry_record?.field_value || '—'"
                    ></div>
                  </template>

                  <!-- Radio / Checkbox / Dropdown / Select -->
<template v-else-if="['dropdown', 'select', 'checkbox', 'radio'].includes(field.type)">
  <div class="space-y-1">
    <div
      v-for="option in field.options"
      :key="option.value"
      class="flex items-start gap-2 text-sm"
    >
      <!-- Checkmark / empty square -->
      <span v-if="isSelected(field, option.value)" class="text-green-600 dark:text-green-400">
        ✅
      </span>
      <span v-else class="text-gray-300 dark:text-gray-600">⬜</span>

      <!-- Option label -->
      <span>{{ option.label }}</span>

      <!-- Custom text for "Other" -->
      <span v-if="option.value == 'option_other'">
        {{ getOptionOther(field.entry_record.extra) }}
      </span>
    </div>
  </div>
</template>

                  <!-- True / False -->
                  <template v-else-if="field.type === 'true_false'">
                    <span
                      v-if="field.entry_record?.field_value == true || field.entry_record?.field_value == '1'"
                      class="inline-flex items-center text-green-600 dark:text-green-400"
                    >
                      <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                      是 / Yes
                    </span>
                    <span
                      v-else-if="field.entry_record?.field_value == false || field.entry_record?.field_value == '0'"
                      class="inline-flex items-center text-red-600 dark:text-red-400"
                    >
                      <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                      否 / No
                    </span>
                    <span v-else class="text-gray-400">—</span>
                  </template>

                  <!-- Date / DateTime / Time -->
                  <template v-else-if="['date', 'datetime', 'time'].includes(field.type)">
                    <span>{{ field.entry_record?.field_value || '—' }}</span>
                  </template>

                  <!-- Photo -->
                  <template v-else-if="field.type === 'photo'">
                    <div v-if="field.entry_record?.field_value" class="mt-1">
                      <img
                        :src="field.entry_record.field_value"
                        alt="Photo"
                        class="max-w-xs max-h-48 object-cover rounded-lg shadow-md border border-gray-200 dark:border-gray-600"
                      />
                    </div>
                    <span v-else class="text-gray-400">—</span>
                  </template>

                  <!-- HTML (static content) -->
                  <template v-else-if="field.type === 'html'">
                    <div v-html="field.extra || '—'"></div>
                  </template>

                  <!-- Fallback -->
                  <template v-else>
                    <span class="text-gray-500">{{ field.entry_record?.field_value || '—' }}</span>
                  </template>
                </div>
              </div>
            </div>
          </div>

          <!-- Submitted At -->
          <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
            <span>
              <span class="font-medium">Submitted At:</span>
              {{ entry.created_at }}
            </span>
            <span>
              <span class="font-medium">Entry ID:</span>
              #{{ entry.id }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </DepartmentLayout>
</template>

<script>
import DepartmentLayout from "@/Layouts/DepartmentLayout.vue";

export default {
  components: {
    DepartmentLayout,
  },
  props: {
    form: { type: Object, required: true },
    entry: { type: Object, required: true },
    formFields: { type: Object, required: true },
  },
  methods: {
    isSelected(field, optionValue) {
      const value = field.entry_record?.field_value;
      if (!value) return false;
      // For checkbox (array)
      if (Array.isArray(value)) {
        return value.includes(optionValue);
      }
      // For radio / dropdown (single value)
      return value == optionValue;
    },
    getOptionOther(extra){
      if(extra == null){
        return null;
      }
      let other=JSON.parse(extra)
      console.log(extra)
      return other['option_other'];
    }
  },
};
</script>

<style scoped>
.prose img {
  max-width: 100%;
  height: auto;
}
.prose table {
  width: 100%;
  border-collapse: collapse;
}
.prose table th,
.prose table td {
  border: 1px solid #d1d5db;
  padding: 0.5rem;
}
</style>