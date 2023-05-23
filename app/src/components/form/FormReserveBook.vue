<script setup>

import { onUpdated, reactive, ref } from "vue";
import { mdiAlert, mdiKey } from "@mdi/js";
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

import { API_URL } from "@/constants";
import { CREATE_RESERVATION } from "@/graphql/mutation/reservation/createReservation";

const props = defineProps({
  data: Object,
  type: String,
});

const mainStore = useMainStore();
const userStore = useUserStore();

const form = reactive({
  title: null,
  dateFrom: null,
  dateTo: null,
});

onUpdated(() => {
  form.id = props.data.id;
  form.title = props.data.title;
});

const confirmMessageSet = ref(false);
const confirmMessageType = ref('success');
const confirmMessage = ref();

const DEFAULT_SUCCESS_MESSAGE_TIMEOUT = 1000;
const emit = defineEmits(['update:reserveModalActive',]);
const submit = async () => {
  const createReservationQuery = CREATE_RESERVATION(form.id, userStore.getUserId(), form.dateFrom, form.dateTo);

  await graphQlQuery(API_URL.base, createReservationQuery, userStore.getToken())
      .then(async result => {
        const response = result.data.createReservation;

        const code = response.code;
        const message = response.message;

        confirmMessageSet.value = true;
        confirmMessage.value = message;

        if (200 === code) {
          mainStore.fetchReservations();
          await sleep(DEFAULT_SUCCESS_MESSAGE_TIMEOUT);
          emit('update:reserveModalActive', false);
        } else {
          confirmMessageType.value = 'danger';
        }
      });
};
</script>

<template>
  <CardBox form @submit.prevent="submit" is-form is-form-modal>
    <div class="mb-3">
      <b>Book reservation:</b> {{ form.title }}
    </div>
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
