<script setup>
import { reactive } from "vue";
import { useMainStore } from "@/stores/main";
import {
  mdiAccount,
  mdiMail,
  mdiAsterisk,
  mdiFormTextboxPassword,
  mdiAlert,
  mdiCheck,
} from "@mdi/js";
import SectionMain from "@/components/section/SectionMain.vue";
import CardBox from "@/components/card/CardBox.vue";
import BaseDivider from "@/components/base/BaseDivider.vue";
import FormField from "@/components/form/FormField.vue";
import FormControl from "@/components/form/FormControl.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseButtons from "@/components/base/BaseButtons.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/section/SectionTitleLineWithButton.vue";
import { useUserStore } from "@/stores/user";
import { isEmpty } from "@/helper/validators";
import { graphQlQuery } from "@/graphql/graphQlQuery";
import { API_URL } from "@/constants";
import { CHANGE_PASSWORD } from "@/graphql/mutation/user/changePassword";
import NotificationBar from "@/components/notification-bar/NotificationBar.vue";

const mainStore = useMainStore();
const userStore = useUserStore();

const profileForm = reactive({
  name: userStore.getUserFullName(),
  email: userStore.getEmail(),
});

const passwordForm = reactive({
  password_current: "",
  password: "",
  password_confirmation: "",
  error: null,
  success: null,
});

const submitProfile = () => {
  mainStore.setUser(profileForm);
};

const submitPass = async () => {
  if (isEmpty(passwordForm.password_current)) {
    passwordForm.error = 'Enter current password.';
  } else if (isEmpty(passwordForm.password)) {
    passwordForm.error = 'Enter new password.';
  } else if (isEmpty(passwordForm.password_confirmation)) {
    passwordForm.error = 'Confirm new password.';
  } else {
    passwordForm.error = null;

    await changePassword();
  }
};

const changePassword = async () => {
  await graphQlQuery(API_URL.base, CHANGE_PASSWORD(userStore.getUserId(), passwordForm.password_current, passwordForm.password, passwordForm.password_confirmation), userStore.getToken())
      .then(async result => {
        const response = result.data.changePassword;

        const code = response.code;
        const message = response.message;

        if (200 === code) {
          passwordForm.success = true;
        } else {
          passwordForm.error = message;
        }
      });
}
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiAccount" title="Profile" main/>

<!--      <UserCard class="mb-6" />-->

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <CardBox is-form @submit.prevent="submitProfile">
<!--          <FormField label="Avatar" help="Max 500kb">-->
<!--            <FormFilePicker label="Upload" />-->
<!--          </FormField>-->

          <FormField label="Name" help="Required. Your name">
            <FormControl
              v-model="profileForm.name"
              :icon="mdiAccount"
              name="username"
              required
              autocomplete="username"
            />
          </FormField>
          <FormField label="E-mail" help="Required. Your e-mail">
            <FormControl
              v-model="profileForm.email"
              :icon="mdiMail"
              type="email"
              name="email"
              required
              autocomplete="email"
            />
          </FormField>

          <template #footer>
            <BaseButtons>
              <BaseButton color="info" type="submit" label="Save" />
            </BaseButtons>
          </template>
        </CardBox>

        <CardBox is-form @submit.prevent="submitPass">
          <FormField
            label="Current password"
            help="Required. Your current password"
          >
            <FormControl
              v-model="passwordForm.password_current"
              :icon="mdiAsterisk"
              name="password_current"
              type="password"
              required
              autocomplete="current-password"
            />
          </FormField>

          <BaseDivider />

          <FormField label="New password" help="Required. New password">
            <FormControl
              v-model="passwordForm.password"
              :icon="mdiFormTextboxPassword"
              name="password"
              type="password"
              required
              autocomplete="new-password"
            />
          </FormField>

          <FormField
            label="Confirm password"
            help="Required. New password one more time"
          >
            <FormControl
              v-model="passwordForm.password_confirmation"
              :icon="mdiFormTextboxPassword"
              name="password_confirmation"
              type="password"
              required
              autocomplete="new-password"
            />
          </FormField>

          <template #footer>
            <BaseButtons>
              <BaseButton type="submit" color="info" label="Save" />
            </BaseButtons>
          </template>
          <NotificationBar color="danger" :icon="mdiAlert" v-if="passwordForm.error" class="mt-3">
            {{ passwordForm.error }}
          </NotificationBar>
          <NotificationBar color="success" :icon="mdiCheck" v-if="passwordForm.success" class="mt-3">
            Password has been changed successfully.
          </NotificationBar>
        </CardBox>
      </div>
    </SectionMain>
  </LayoutAuthenticated>
</template>
