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
import {useUserStore} from "@/stores/user";

const router = useRouter();
const userStore = useUserStore();

const form = reactive({
  username: 'admin',
  password: 'admin',
  rememberMe: true,
  error: null,
});

const BASE_API_URL = `http://colibri.backend.localhost`;

const submit = () => {
  fetch(BASE_API_URL + '/api/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      username: form.username,
      password: form.password,
    }),
  })
  .then(result => result.json())
  .then(async result => {
    const token = result.token;

    if (token) {
      userStore.setToken(token);

      await fetch(BASE_API_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + userStore.getToken(),
        },
        body: JSON.stringify({
          variables: {},
          query: `
            query GetUser {
              getUserByUsername(username: "` + form.username + `") {
                  id
                  name
                  username
                  email
                  status
                  roles
              }
          }
        `,
        }),
      })
          .then(result => result.json())
          .then(result => {
            userStore.setUsername(result.data.getUserByUsername.name);
          });

      router.push('/dashboard');
    } else {
      form.error = result.message;
    }
  });
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
