<script setup>

import {onUpdated, reactive, ref} from "vue";
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
import {UPDATE_USER} from "@/graphql/mutation/user/updateUser";

const props = defineProps({
  data: Object,
  type: String,
});

const mainStore = useMainStore();
const userStore = useUserStore();

const BASE_API_URL = `http://colibri.backend.localhost`;

const form = reactive({
  id: null,
  name: null,
  username: null,
  email: null,
  roles: null,
});

const roles = [
  { id: '[\'ROLE_USER\']', label: 'User', },
  { id: '[\'ROLE_ADMIN\']', label: 'Admin', },
];

onUpdated(() => {
  if ('edit' === props.type) {
    form.id = props.data.id;
    form.name = props.data.name;
    form.username = props.data.username;
    form.email = props.data.email;
    form.roles = ref(roles);
  }
});

const confirmMessageSet = ref(false);
const confirmMessageType = ref('success');
const confirmMessage = ref();

const DEFAULT_SUCCESS_MESSAGE_TIMEOUT = 1000;
const emit = defineEmits(['update:createModalActive', 'update:editModalActive']);

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

const updateUser = async () => {
  const updateUserQuery = UPDATE_USER(form.id, form.name, form.username, form.email, form.roles.id);

  await graphQlQuery(BASE_API_URL, updateUserQuery, userStore.getToken())
      .then(async result => {
        const response = result.data.updateUser;

        const code = response.code;
        const message = response.message;

        confirmMessageSet.value = true;
        confirmMessage.value = message;

        if (200 === code) {
          mainStore.fetchUsers();
          await sleep(DEFAULT_SUCCESS_MESSAGE_TIMEOUT);
          emit('update:editModalActive', false);
        } else {
          confirmMessageType.value = 'danger';
        }
      });
}

const submit = async () => {
  'edit' === props.type ? await createUser() : await createUser();
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
