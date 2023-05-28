<script setup>
import { reactive } from "vue";
import { useRouter } from "vue-router";
import { mdiAccount, mdiAsterisk, mdiAlert } from "@mdi/js";
import SectionFullScreen from "@/components/section/SectionFullScreen.vue";
import CardBox from "@/components/card/CardBox.vue";
import FormCheckRadio from "@/components/form/FormCheckRadio.vue";
import FormField from "@/components/form/FormField.vue";
import FormControl from "@/components/form/FormControl.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseButtons from "@/components/base/BaseButtons.vue";
import LayoutGuest from "@/layouts/LayoutGuest.vue";
import NotificationBar from "@/components/notification-bar/NotificationBar.vue";

import { useUserStore } from "@/stores/user";
import { useMainStore } from "@/stores/main";

import { isEmpty } from "@/helper/validators";

const router = useRouter();
const userStore = useUserStore();
const mainStore = useMainStore();

const form = reactive({
  username: 'admin',
  password: 'admin',
  rememberMe: true,
  error: null,
});

const submit = () => {
  if (isEmpty(form.username)) {
    form.error = 'Enter a username.';
  } else if (isEmpty(form.password)) {
    form.error = 'Enter a password';
  } else {
    form.error = null;

    userStore.login(form.username, form.password)
    .then(async result => {
      const token = result.token;

      if (token) {
        userStore.setToken(token);
        await mainStore.fetchActiveUserData(form.username)
        .then(result => {
          userStore.setUserFullName(result.data.getUserByUsername.name);
          userStore.setUsername(result.data.getUserByUsername.username);
          userStore.setUserId(result.data.getUserByUsername.id);
          userStore.setEmail(result.data.getUserByUsername.email);
        });

        router.push('/dashboard');
      } else {
        form.error = result.message;
      }
    });
  }
};
</script>

<template>
  <LayoutGuest>
    <SectionFullScreen v-slot="{ cardClass }" bg="purplePink">
      <CardBox :class="cardClass" is-form @submit.prevent="submit">
        <FormField label="Login" help="Please enter your login">
          <FormControl
            v-model="form.username"
            :icon="mdiAccount"
            name="login"
            autocomplete="username"
          />
        </FormField>

        <FormField label="Password" help="Please enter your password">
          <FormControl
            v-model="form.password"
            :icon="mdiAsterisk"
            type="password"
            name="password"
            autocomplete="current-password"
          />
        </FormField>

        <FormCheckRadio
          v-model="form.rememberMe"
          name="rememberMe"
          label="Remember"
          :input-value="true"
        />

        <template #footer>
          <BaseButtons>
            <BaseButton type="submit" color="info" label="Login" />
          </BaseButtons>
        </template>

        <NotificationBar color="danger" :icon="mdiAlert" v-if="form.error" class="mt-3">
          {{ form.error }}
        </NotificationBar>
      </CardBox>
    </SectionFullScreen>
  </LayoutGuest>
</template>
