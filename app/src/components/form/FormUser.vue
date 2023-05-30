<script setup>

import {onUpdated, reactive, ref} from "vue";
import {
  mdiAccount,
  mdiAlert,
  mdiFormTextboxPassword,
  mdiKey,
  mdiMail
} from "@mdi/js";
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
import { UPDATE_USER } from "@/graphql/mutation/user/updateUser";

import { API_URL } from "@/constants";
import { isEmailValid, isEmpty, isSelected } from "@/helper/validators";

const props = defineProps({
  data: Object,
  type: String,
});

const mainStore = useMainStore();
const userStore = useUserStore();

const form = reactive({
  id: null,
  name: null,
  username: null,
  email: null,
  roles: null,
  password: null,
  error: null,
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
    form.password = props.data.password
  }
});

const confirmMessageSet = ref(false);
const confirmMessageType = ref('success');
const confirmMessage = ref();

const DEFAULT_SUCCESS_MESSAGE_TIMEOUT = 1000;
const emit = defineEmits(['update:createModalActive', 'update:editModalActive']);

const createUser = async () => {
  const createUserQuery = CREATE_USER(form.name, form.username, form.email, form.roles.id, form.password);

  await graphQlQuery(API_URL.base, createUserQuery, userStore.getToken())
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
  const updateUserQuery = UPDATE_USER(form.id, form.name, form.username, form.email, form.roles.id, form.password);

  await graphQlQuery(API_URL.base, updateUserQuery, userStore.getToken())
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
  if (isEmpty(form.name)) {
    form.error = 'Enter a name.';
  } else if (isEmpty(form.username)) {
    form.error = 'Enter a username.';
  } else if (isEmpty(form.email)) {
    form.error = 'Enter a email.';
  } else if (isEmailValid(form.email)) {
    form.error = 'Enter a valid email.';
  } else if (!isSelected(form.roles)) {
    form.error = 'Select role.';
  } else if (isEmpty(form.password)) {
    form.error = 'Enter password.';
  } else {
    form.error = null;

    'edit' === props.type ? await updateUser() : await createUser();
  }
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
    <FormField label="Roles">
      <FormControl
          v-model="form.password"
          :icon="mdiFormTextboxPassword"
          type="password"
          placeholder="Password"
          required="required"
      />
    </FormField>

    <template #footer>
      <BaseButtons>
        <BaseButton label="Save" color="info" @click="submit" />
      </BaseButtons>
      <NotificationBar :color="confirmMessageType" :icon="mdiAlert" v-if="confirmMessageSet" class="mt-3">
        {{ confirmMessage }}
      </NotificationBar>

      <NotificationBar color="danger" :icon="mdiAlert" v-if="form.error" class="mt-3">
        {{ form.error }}
      </NotificationBar>
    </template>
  </CardBox>
</template>
