<template>
    <MemberLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $t('member_dashboard') }}
            </h2>
        </template>
        <div class="mx-auto p-5">
            <a-row :gutter="24">
                <a-col :span="12">
                    <div class="bg-white relative shadow rounded-lg p-5">
                        <a-typography-title :level="5">{{ $t('profile.personal_info') }}</a-typography-title>
                        <a-form 
                            :model="member"
                            layout="vertical"
                            @finish="onProfileFinish"
                            @finishFailed="onProfileFinishFailed"
                        >
                            <a-form-item :label="$t('profile.last_name')"  name="last_name">
                                <a-input v-model:value="member.last_name"/>
                            </a-form-item>
                            <a-form-item :label="$t('profile.first_name')"  name="first_name">
                                <a-input v-model:value="member.first_name"/>
                            </a-form-item>
                            <a-form-item :label="$t('profile.gender')"  name="gender">
                                <a-input v-model:value="member.gender"/>
                            </a-form-item>
                            <a-form-item :label="$t('profile.email')"  name="email">
                                <a-input v-model:value="member.email"/>
                            </a-form-item>
                            <a-form-item :wrapper-col="{ offset: 8, span: 16 }">
                                <a-button type="primary" html-type="submit">{{ $t('submit') }}</a-button>
                            </a-form-item>
                        </a-form>
                    </div>
                </a-col>
                <a-col :span="12">
                    <div class="bg-white relative shadow rounded-lg p-5">
                        <a-typography-title :level="5">{{ $t('profile.change_password') }}</a-typography-title>
                        <a-form 
                            :model=password
                            layout="vertical"
                            @finish="onPasswordFinish"
                            @finishFailed="onPasswordFinishFailed"
                        >
                            <a-form-item :label="$t('profile.current_password')"  name="current">
                                <a-input-password v-model:value="password.current"/>
                            </a-form-item>
                            <a-form-item :label="$t('profile.new_password')"  name="new">
                                <a-input-password v-model:value="password.new"/>
                            </a-form-item>
                            <a-form-item :label="$t('profile.confirm_password')"  name="confirm">
                                <a-input-password v-model:value="password.confirm"/>
                            </a-form-item>
                            <a-form-item :wrapper-col="{ offset: 8, span: 16 }">
                                <a-button type="primary" html-type="submit">{{ $t('submit') }}</a-button>
                            </a-form-item>
                        </a-form>
                    </div>
                </a-col>
            </a-row>

        </div>

    </MemberLayout>
</template>


<script setup>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import { UserOutlined, MessageOutlined, EditOutlined } from '@ant-design/icons-vue'

defineProps({
    member:Object
});
const password={
    current:'a',
    new:'b',
    confirm:'c  '
}
const onProfileFinish = values => {
  console.log('Success:', values);
};
const onProfileFinishFailed = errorInfo => {
  console.log('Failed:', errorInfo);
};
const onPasswordFinish = values => {
  console.log('Success:', values);
};
const onPasswordFinishFailed = errorInfo => {
  console.log('Failed:', errorInfo);
};
</script>
