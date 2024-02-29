<template>
  <WebLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">表格例表</h2>
    </template>
    <a-typography-title :level="4">Coming soon....</a-typography-title>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="mt-8 p-4 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
        <div class="ant-table ant-table-bordered">
          <div class="ant-table-container">
            <div class="ant-table-content">
              <table id="applicationSuccess" style="table-layout: auto">
                <tbody class="ant-table-tbody">
                  <template v-for="record in entry_records" :key="record.id">
                    <tr v-if="record.form_field.type == 'photo'">
                      <td>{{ record.form_field.field_label }}</td>
                      <td><img :src="record.field_value" class="w-[120px]" /></td>
                    </tr>
                    <tr v-else-if="record.form_field.type == 'radio'">
                      <td>{{ record.form_field.field_label }}</td>
                      <td>
                        {{
                          JSON.parse(record.form_field.options).find(
                            (x) => x.value == record.field_value
                          ).label
                        }}
                      </td>
                    </tr>
                    <tr v-else-if="record.form_field.type == 'checkbox'">
                      <td>{{ record.form_field.field_label }}</td>
                      <td>
                        {{
                          JSON.parse(record.field_value)
                            .map((x) => {
                              return JSON.parse(record.form_field.options).find(
                                (y) => y.value == x
                              ).label;
                            })
                            .join(",")
                        }}
                      </td>
                    </tr>
                    <tr v-else>
                      <td>{{ record.form_field.field_label }}</td>
                      <td>{{ record.field_value }}</td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="flex justify-between py-10">
          <div>
            <a href="route('/')">返回主頁</a>
          </div>
          <div>
            <a href="'/form/' + form.id + '/entry/' + entry.id + '/success?format=pdf'"
              >打印表格</a
            >
          </div>
          <div>
            <a href="route('forms.index')">活動列表</a>
          </div>
        </div>
      </div>
    </div>
  </WebLayout>
</template>

<script>
import WebLayout from "@/Layouts/WebLayout.vue";

export default {
  components: {
    WebLayout,
  },
  props: ["form", "entry_records", "entry"],
  data() {
    return {};
  },
  created() {},
  methods: {},
};
</script>
