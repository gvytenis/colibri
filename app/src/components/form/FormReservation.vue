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
import { CREATE_RESERVATION } from "@/graphql/mutation/reservation/createReservation";

const props = defineProps({
  id: Number,
  book: Number,
  user: Number,
  dateFrom: String,
  dateTo: String,
});

const mainStore = useMainStore();
const userStore = useUserStore();

const BASE_API_URL = `http://colibri.backend.localhost`;

const form = reactive({
  book: props.book,
  user: props.user,
  dateFrom: props.dateFrom,
  dateTo: props.dateTo,
});

const confirmMessageSet = ref(false);
const confirmMessageType = ref('success');
const confirmMessage = ref();

const DEFAULT_SUCCESS_MESSAGE_TIMEOUT = 1000;
const emit = defineEmits(['update:createModalActive']);

const books = mainStore.books.map(book => {
  const { title, ...rest } = book;
  return { label: title, ...rest };
});

const users = mainStore.users.map(user => {
  const { name, ...rest } = user;
  return { label: name, ...rest };
});

const createReservation = async () => {
  const createReservationQuery = CREATE_RESERVATION(form.book.id, form.user.id, form.dateFrom, form.dateTo);

  await graphQlQuery(BASE_API_URL, createReservationQuery, userStore.getToken())
      .then(async result => {
        const response = result.data.createReservation;

        const code = response.code;
        const message = response.message;

        confirmMessageSet.value = true;
        confirmMessage.value = message;

        if (200 === code) {
          mainStore.fetchReservations();
          await sleep(DEFAULT_SUCCESS_MESSAGE_TIMEOUT);
          emit('update:createModalActive', false);
        } else {
          confirmMessageType.value = 'danger';
        }
      });
}

const submit = async () => {
  await createReservation();
};
</script>

<template>
  <CardBox form @submit.prevent="submit" is-form is-form-modal>
    <FormField label="Book">
      <FormControl
          v-model="form.book"
          :icon="mdiAccount"
          type="text"
          placeholder="Book"
          required="required"
          :options="books"
      />
    </FormField>
    <FormField label="User">
      <FormControl
          v-model="form.user"
          :icon="mdiAccount"
          type="text"
          placeholder="User"
          required="required"
          :options="users"
      />
    </FormField>
    <FormField label="Date from">
      <FormControl
          v-model="form.dateFrom"
          :icon="mdiKey"
          type="text"
          placeholder="Date from"
          required="required"
      />
    </FormField>
    <FormField label="Date to">
      <FormControl
          v-model="form.dateTo"
          :icon="mdiKey"
          type="text"
          placeholder="Date to"
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
