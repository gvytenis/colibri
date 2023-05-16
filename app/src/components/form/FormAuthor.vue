<script setup>

import { onUpdated, reactive, ref } from "vue";
import { mdiAccount, mdiAlert } from "@mdi/js";
import CardBox from "@/components/card/CardBox.vue";
import FormField from "@/components/form/FormField.vue";
import FormControl from "@/components/form/FormControl.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseButtons from "@/components/base/BaseButtons.vue";
import { graphQlQuery } from "@/graphql/graphQlQuery";
import { useUserStore } from "@/stores/user";
import { CREATE_AUTHOR } from "@/graphql/mutation/author/createAuthor";
import NotificationBar from "@/components/notification-bar/NotificationBar.vue";
import { sleep } from "@/helper/sleep";
import { useMainStore } from "@/stores/main";
import { UPDATE_AUTHOR } from "@/graphql/mutation/author/updateAuthor";

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
});

onUpdated(() => {
  if ('edit' === props.type) {
    form.id = props.data.id;
    form.name = props.data.name;
  }
});

const confirmMessageSet = ref(false);
const confirmMessageType = ref('success');
const confirmMessage = ref();

const DEFAULT_SUCCESS_MESSAGE_TIMEOUT = 1000;
const emit = defineEmits(['update:createModalActive', 'update:editModalActive']);

const createAuthor = async () => {
  await graphQlQuery(BASE_API_URL, CREATE_AUTHOR(form.name), userStore.getToken())
      .then(async result => {
        const response = result.data.createAuthor;

        const code = response.code;
        const message = response.message;

        confirmMessageSet.value = true;
        confirmMessage.value = message;

        if (200 === code) {
          mainStore.fetchAuthors();
          await sleep(DEFAULT_SUCCESS_MESSAGE_TIMEOUT);
          emit('update:createModalActive', false);
        } else {
          confirmMessageType.value = 'danger';
        }
      });
}

const updateAuthor = async () => {
  await graphQlQuery(BASE_API_URL, UPDATE_AUTHOR(form.id, form.name), userStore.getToken())
      .then(async result => {
        const response = result.data.updateAuthor;

        const code = response.code;
        const message = response.message;

        confirmMessageSet.value = true;
        confirmMessage.value = message;

        if (200 === code) {
          mainStore.fetchAuthors();
          await sleep(DEFAULT_SUCCESS_MESSAGE_TIMEOUT);
          emit('update:editModalActive', false);
        } else {
          confirmMessageType.value = 'danger';
        }
      });
}

const submit = async () => {
  'edit' === props.type ? await updateAuthor() : await createAuthor();
};
</script>

<template>
  <CardBox form @submit.prevent="submit" is-form is-form-modal>
    <FormField label="Name">
      <FormControl
          v-model="form.name"
          :icon="mdiAccount"
          type="text"
          placeholder="Author full name"
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
    </template>
  </CardBox>
</template>
