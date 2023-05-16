<script setup>

import { reactive, ref } from "vue";
import { mdiAccount, mdiAlert, mdiTimelineClock } from "@mdi/js";
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
import { CREATE_BOOK } from "@/graphql/mutation/book/createBook";

const props = defineProps({
  id: Number,
  name: String,
  title: String,
  year: Number,
  category: Number,
  author: Number,
});

const mainStore = useMainStore();
const userStore = useUserStore();

const BASE_API_URL = `http://colibri.backend.localhost`;

const form = reactive({
  title: props.title,
  year: props.year,
  category: props.category,
  author: props.author,
});

const confirmMessageSet = ref(false);
const confirmMessageType = ref('success');
const confirmMessage = ref();

const DEFAULT_SUCCESS_MESSAGE_TIMEOUT = 1000;
const emit = defineEmits(['update:modelValue', 'cancel', 'confirm']);

const categories = mainStore.categories.map(category => {
  const { name, ...rest } = category;
  return { label: name, ...rest };
});

const authors = mainStore.authors.map(author => {
  const { name, ...rest } = author;
  return { label: name, ...rest };
});

const createBook = async () => {
  await graphQlQuery(BASE_API_URL, CREATE_BOOK(form.title, form.author.id, form.year, form.category.id), userStore.getToken())
      .then(async result => {
        const response = result.data.createBook;

        const code = response.code;
        const message = response.message;

        confirmMessageSet.value = true;
        confirmMessage.value = message;

        if (200 === code) {
          mainStore.fetchBooks();
          await sleep(DEFAULT_SUCCESS_MESSAGE_TIMEOUT);
          // TODO: Close modal
        } else {
          confirmMessageType.value = 'danger';
        }
      });
}

const submit = async () => {
  await createBook();
};
</script>

<template>
  <CardBox form @submit.prevent="submit" is-form is-form-modal>
    <FormField label="Title">
      <FormControl
          v-model="form.title"
          :icon="mdiAccount"
          type="text"
          placeholder="Book title"
          required="required"
      />
    </FormField>
    <FormField label="Year">
      <FormControl
          v-model="form.year"
          :icon="mdiTimelineClock"
          type="text"
          placeholder="Book year"
          required="required"
      />
    </FormField>
    <FormField label="Category">
      <FormControl v-model="form.category" :options="categories" />
    </FormField>
    <FormField label="Author">
      <FormControl v-model="form.author" :options="authors" />
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
