<script setup>
import { computed, ref } from "vue";
import { mdiAlert, mdiClose } from "@mdi/js";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseButtons from "@/components/base/BaseButtons.vue";
import CardBox from "@/components/card/CardBox.vue";
import OverlayLayer from "@/components/common/OverlayLayer.vue";
import CardBoxComponentTitle from "@/components/card/CardBoxComponentTitle.vue";
import NotificationBar from "@/components/notification-bar/NotificationBar.vue";

import { useMainStore } from "@/stores/main";
import { useUserStore } from "@/stores/user";

import { graphQlMutation } from "@/graphql/graphQlMutation";

import { DELETE_AUTHOR } from "@/graphql/mutation/author/deleteAuthor";
import { DELETE_BOOK } from "@/graphql/mutation/book/deleteBook";
import { DELETE_CATEGORY } from "@/graphql/mutation/category/deleteCategory";
import { DELETE_RESERVATION } from "@/graphql/mutation/reservation/deleteReservation";
import { DELETE_USER } from "@/graphql/mutation/user/deleteUser";

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  button: {
    type: String,
    default: "danger",
  },
  buttonLabel: {
    type: String,
    default: "Yes",
  },
  hasCancel: {
    type: Boolean,
    default: true,
  },
  modelValue: {
    type: [String, Number, Boolean],
    default: null,
  },
  deletableType: {
    type: String,
    required: true,
  },
  deletableId: {
    type: Number,
    required: true,
  }
});

const emit = defineEmits(["update:modelValue", "cancel", "confirm"]);

const value = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});

const confirmCancel = (mode) => {
  value.value = false;
  emit(mode);
};

const mainStore = useMainStore();
const userStore = useUserStore();

const confirmMessageSet = ref(false);
const confirmMessageType = ref('success');
const confirmMessage = ref();

const DEFAULT_SUCCESS_MESSAGE_TIMEOUT = 1000;

const sleep = async (timeout) => {
  await new Promise(r => setTimeout(r, timeout));
}

const deleteAuthor = async (BASE_API_URL) => {
  await graphQlMutation(BASE_API_URL, DELETE_AUTHOR(props.deletableId), userStore.getToken())
      .then(async result => {
        const response = result.data.deleteAuthor;

        const code = response.code;
        const message = response.message;

        confirmMessageSet.value = true;
        confirmMessage.value = message;

        if (200 === code) {
          mainStore.fetchAuthors();
          await sleep(DEFAULT_SUCCESS_MESSAGE_TIMEOUT);
          confirmCancel('confirm');
        } else {
          confirmMessageType.value = 'danger';
        }
      });
}

const deleteBook = async (BASE_API_URL) => {
  await graphQlMutation(BASE_API_URL, DELETE_BOOK(props.deletableId), userStore.getToken())
      .then(async result => {
        const response = result.data.deleteBook;

        const code = response.code;
        const message = response.message;

        confirmMessageSet.value = true;
        confirmMessage.value = message;

        if (200 === code) {
          mainStore.fetchBooks();
          await sleep(DEFAULT_SUCCESS_MESSAGE_TIMEOUT);
          confirmCancel('confirm');
        } else {
          confirmMessageType.value = 'danger';
        }
      });
}

const deleteCategory = async (BASE_API_URL) => {
  await graphQlMutation(BASE_API_URL, DELETE_CATEGORY(props.deletableId), userStore.getToken())
      .then(async result => {
        const response = result.data.deleteCategory;

        const code = response.code;
        const message = response.message;

        confirmMessageSet.value = true;
        confirmMessage.value = message;

        if (200 === code) {
          mainStore.fetchCategories();
          await sleep(DEFAULT_SUCCESS_MESSAGE_TIMEOUT);
          confirmCancel('confirm');
        } else {
          confirmMessageType.value = 'danger';
        }
      });
}

const deleteReservation = async (BASE_API_URL) => {
  await graphQlMutation(BASE_API_URL, DELETE_RESERVATION(props.deletableId), userStore.getToken())
      .then(async result => {
        const response = result.data.deleteReservation;

        const code = response.code;
        const message = response.message;

        confirmMessageSet.value = true;
        confirmMessage.value = message;

        if (200 === code) {
          mainStore.fetchReservations();
          await sleep(DEFAULT_SUCCESS_MESSAGE_TIMEOUT);
          confirmCancel('confirm');
        } else {
          confirmMessageType.value = 'danger';
        }
      });
}

const deleteUser = async (BASE_API_URL) => {
  await graphQlMutation(BASE_API_URL, DELETE_USER(props.deletableId), userStore.getToken())
      .then(async result => {
        const response = result.data.deleteUser;

        const code = response.code;
        const message = response.message;

        confirmMessageSet.value = true;
        confirmMessage.value = message;

        if (200 === code) {
          mainStore.fetchUsers();
          await sleep(DEFAULT_SUCCESS_MESSAGE_TIMEOUT);
          confirmCancel('confirm');
        } else {
          confirmMessageType.value = 'danger';
        }
      });
}

const confirm = async () => {
  const BASE_API_URL = `http://colibri.backend.localhost`;

  switch (props.deletableType) {
    case 'author':
      await deleteAuthor(BASE_API_URL);
      break;
    case 'book':
      await deleteBook(BASE_API_URL);
      break;
    case 'category':
      await deleteCategory(BASE_API_URL);
      break;
    case 'reservation':
      await deleteReservation(BASE_API_URL);
      break;
    case 'user':
      await deleteUser(BASE_API_URL);
      break;
  }
};

const cancel = () => confirmCancel("cancel");

window.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && value.value) {
    cancel();
  }
});
</script>

<template>
  <OverlayLayer v-show="value" @overlay-click="cancel">
    <CardBox
      v-show="value"
      class="shadow-lg max-h-modal w-11/12 md:w-3/5 lg:w-2/5 xl:w-4/12 z-50"
      is-modal
    >
      <CardBoxComponentTitle :title="title">
        <BaseButton
          v-if="hasCancel"
          :icon="mdiClose"
          color="whiteDark"
          small
          rounded-full
          @click.prevent="cancel"
        />
      </CardBoxComponentTitle>

      <div class="space-y-3">
        <slot />
      </div>

      <template #footer>
        <BaseButtons v-if="!confirmMessageSet">
          <BaseButton :label="buttonLabel" :color="button" @click="confirm" />
          <BaseButton
            v-if="hasCancel"
            label="Cancel"
            :color="button"
            outline
            @click="cancel"
          />
        </BaseButtons>
      </template>
      <NotificationBar :color="confirmMessageType" :icon="mdiAlert" v-if="confirmMessageSet" class="mt-3">
        {{ confirmMessage }}
      </NotificationBar>
    </CardBox>
  </OverlayLayer>
</template>
