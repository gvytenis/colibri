<script setup>

import { reactive, ref } from "vue";
import { mdiAccount, mdiAlert, mdiKey, mdiMail } from "@mdi/js";
import CardBox from "@/components/card/CardBox.vue";
import FormField from "@/components/form/FormField.vue";
import FormControl from "@/components/form/FormControl.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseButtons from "@/components/base/BaseButtons.vue";
import { graphQlQuery } from "@/graphql/graphQlQuery";
import { useUserStore } from "@/stores/user";
import NotificationBar from "@/components/notification-bar/NotificationBar.vue";
import { sleep } from "@/helper/sleep";
import { useMainStore } from "@/stores/main";
import { CREATE_USER } from "@/graphql/mutation/user/createUser";

const props = defineProps({
  id: Number,
  name: String,
  username: String,
  email: String,
  roles: String,
});

const mainStore = useMainStore();
const userStore = useUserStore();

const BASE_API_URL = `http://colibri.backend.localhost`;

const form = reactive({
  name: props.name,
  username: props.username,
  email: props.email,
  roles: props.roles,
});

const roles = [
  { id: '[\'ROLE_USER\']', label: 'User', },
  { id: '[\'ROLE_ADMIN\']', label: 'Admin', },
];

const confirmMessageSet = ref(false);
const confirmMessageType = ref('success');
const confirmMessage = ref();

const DEFAULT_SUCCESS_MESSAGE_TIMEOUT = 1000;
const emit = defineEmits(['update:createModalActive']);

const createUser = async () => {
  const createUserQuery = CREATE_USER(form.name, form.username, form.email, form.roles.id);

  await graphQlQuery(BASE_API_URL, createUserQuery, userStore.getToken())
      .then(async result => {
        const response = result.data.createUser;

        const code = response.code;
        const message = response.message;

        confirmMessageSet.value = true;
        confirmMessage.value = message;

        if (200 === code) {
          mainStore.fetchUsers();
          await sleep(DEFAULT_SUCCESS_MESSAGE_TIMEOUT);
          emit('update:createModalActive', false);
        } else {
          confirmMessageType.value = 'danger';
        }
      });
}

const submit = async () => {
  await createUser();
};
</script>

<template>
  <CardBox form @submit.prevent="submit" is-form is-form-modal>
    <FormField label="Name">
      <FormControl
          v-model="form.name"
          :icon="mdiAccount"
          type="text"
          placeholder="User full name"
          required="required"
      />
    </FormField>
    <FormField label="Username">
      <FormControl
          v-model="form.username"
          :icon="mdiKey"
          type="text"
          placeholder="Username"
          required="required"
      />
    </FormField>
    <FormField label="Email">
      <FormControl
          v-model="form.email"
          :icon="mdiMail"
          type="email"
          placeholder="Email"
          required="required"
      />
    </FormField>
    <FormField label="Roles">
      <FormControl
          v-model="form.roles"
          :icon="mdiAccount"
          type="text"
          placeholder="Roles"
          required="required"
          :options="roles"
      />
    </FormField>

    <template #footer>
      <BaseButtons>
        <BaseButton label="Save" color="info" @click="submit" />
      </BaseButtons>
      <NotificationBar :color="confirmMessageType" :icon="mdiAlert" v-if="confirmMessageSet" class="mt-3">
        {{ confirmMessage }}
      </NotificationBar>
    </template>
  </CardBox>
</template>
