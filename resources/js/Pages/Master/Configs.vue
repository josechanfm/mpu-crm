<template>
    <MasterLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Configurations
            </h2>
        </template>
        <a-button @click="createRecord" type="primary">Create</a-button>
        <a-table 
            :dataSource="configs" 
            :columns="columns">
            <template #bodyCell="{ column, text, record, index }">
                <template v-if="column.dataIndex === 'operation'">
                    <a-button @click="editRecord(record)">Edit</a-button>
                </template>
                <template v-else>
                    {{ record[column.dataIndex] }}
                </template>
            </template>
        </a-table>

<a-modal 
    v-model:open="modal.isOpen" 
    :title="modal.title" 
    width="60%" 
    okText="Save" 
    @ok="onFinish">
    <a-form ref="modalRef" :model="modal.data" layout="vertical">
        <a-form-item label="Label" name="label" :rules="[{ required: true }]">
            <a-input type="input" v-model:value="modal.data.label"/>
        </a-form-item>
        <a-form-item label="Key" name="key" :rules="[{ required: true }]">
            <a-input type="input" v-model:value="modal.data.key"/>
        </a-form-item>
        <a-form-item label="Value" name="value" :rules="[{ required: true }]">
            <a-textarea 
                v-model:value="modal.data.value" 
                :rows="10"
                @change="onValueChange" 
            />
        </a-form-item>
    </a-form>
</a-modal>

        <!-- Modal End -->
    </MasterLayout>
</template>

<script>
import MasterLayout from '@/Layouts/MasterLayout.vue';

export default {
    components: {
        MasterLayout,
    },
    props: ['configs'],
    data() {
        return {
            modal: {
                mode: null,
                isOpen: false,
                title: 'Config Item',
                data: {}
            },
            columns: [
                {
                    title: 'Label',
                    dataIndex: 'label',
                },
                {
                    title: 'Key',
                    dataIndex: 'key',
                },
                {
                    title: 'Operation',
                    dataIndex: 'operation',
                }
            ],
        }
    },

    methods: {
        createRecord() {
            this.modal.data = {};
            this.modal.mode = 'CREATE';
            this.modal.title = "Create Config";
            this.modal.isOpen = true;
        },
        editRecord(record) {
            this.modal.data = { ...record };  
            this.modal.data.value= JSON.stringify(record.value, null, 2); 
            console.log(this.modal.data.value);
            this.modal.mode = 'EDIT';
            this.modal.title = "Edit Config";
            this.modal.isOpen = true;

        },
        jsonStringify(value) {
            // Convert object to JSON string for textarea
            return JSON.stringify(value, null, 2); // Pretty print JSON
        },

        onValueChange(newValue) {
            try {
                // Try to parse the JSON string input back to an object
                this.modal.data.value = JSON.parse(newValue);
            } catch (e) {
                console.warn('Invalid JSON format: ', e);
                // Handle the error for invalid JSON (optional)
            }
        },

        onFinish() {
            this.$refs.modalRef.validateFields().then(() => {
                // Handle saving logic
                // Use modal.data.value which is now an object or handle it accordingly
                if (this.modal.mode === 'EDIT') {
                    this.$inertia.put(route('master.configs.update', this.modal.data.id), this.modal.data, {
                        onSuccess: () => {
                            this.modal.isOpen = false;
                        },
                        onError: (error) => {
                            console.log(error);
                        }
                    });
                } else if (this.modal.mode === 'CREATE') {
                    this.$inertia.post(route('master.configs.store'), this.modal.data, {
                        onSuccess: () => {
                            this.modal.isOpen = false;
                        },
                        onError: (error) => {
                            console.log(error);
                        }
                    });
                }
            }).catch(err => {
                console.log(err);
            });
        },
        
    },
}
</script>