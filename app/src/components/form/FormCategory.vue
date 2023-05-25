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
import NotificationBar from "@/components/notification-bar/NotificationBar.vue";
import { sleep } from "@/helper/sleep";
import { useMainStore } from "@/stores/main";

import { CREATE_CATEGORY } from "@/graphql/mutation/category/createCategory";
import { UPDATE_CATEGORY } from "@/graphql/mutation/category/updateCategory";

import { API_URL } from "@/constants";
import { isEmpty } from "@/helper/validators";

const props = defineProps({
  data: Object,
  type: String,
});

const mainStore = useMainStore();
const userStore = useUserStore();

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

const createCategory = async () => {
  await graphQlQuery(API_URL.base, CREATE_CATEGORY(form.name), userStore.getToken())
      .then(async result => {
        const response = result.data.createCategory;

        const code = response.code;
        const message = response.message;

        confirmMessageSet.value = true;
        confirmMessage.value = message;

        if (200 === code) {
          mainStore.fetchCategories();
          await sleep(DEFAULT_SUCCESS_MESSAGE_TIMEOUT);
          emit('update:createModalActive', false);
        } else {
          confirmMessageType.value = 'danger';
        }
      });
}

const updateCategory = async () => {
  await graphQlQuery(API_URL.base, UPDATE_CATEGORY(form.id, form.name), userStore.getToken())
      .then(async result => {
        const response = result.data.updateCategory;

        const code = response.code;
        const message = response.message;

        confirmMessageSet.value = true;
        confirmMessage.value = message;

        if (200 === code) {
          mainStore.fetchCategories();
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
  } else {
    form.error = null;

    'edit' === props.type ? await updateCategory() : await createCategory();
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
          placeholder="Category full name"
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
