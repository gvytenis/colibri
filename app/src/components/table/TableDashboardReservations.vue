<script setup>
import { computed, ref } from "vue";
import { useMainStore } from "@/stores/main";
import BaseLevel from "@/components/base/BaseLevel.vue";
import BaseButtons from "@/components/base/BaseButtons.vue";
import BaseButton from "@/components/base/BaseButton.vue";

const mainStore = useMainStore();
const items = computed(() => mainStore.myReservations);

const perPage = ref(5);
const currentPage = ref(0);

let itemsPaginated = computed(() =>
    items.value.slice(
      perPage.value * currentPage.value,
      perPage.value * (currentPage.value + 1)
  )
);

const numPages = computed(() => Math.ceil(items.value.length / perPage.value));
const currentPageHuman = computed(() => currentPage.value + 1);

const pagesList = computed(() => {
  const pagesList = [];

  for (let i = 0; i < numPages.value; i++) {
    pagesList.push(i);
  }

  return pagesList;
});
</script>

<template>
  <table>
    <thead>
      <tr>
        <th>Title</th>
        <th>Category</th>
        <th>Author</th>
        <th>From</th>
        <th>To</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="reservation in itemsPaginated" :key="reservation.id">
        <td data-label="Title">
          {{ reservation.book.title }}
        </td>
        <td data-label="BookCategory">
          {{ reservation.book.category.name }}
        </td>
        <td data-label="Author">
          {{ reservation.book.author.name }}
        </td>
        <td data-label="DateFrom">
          {{ reservation.dateFrom }}
        </td>
        <td data-label="DateTo">
          {{ reservation.dateTo }}
        </td>
      </tr>
    </tbody>
  </table>
  <div class="p-3 lg:px-6 border-t border-gray-100 dark:border-slate-800">
    <BaseLevel>
      <BaseButtons>
        <BaseButton
          v-for="page in pagesList"
          :key="page"
          :active="page === currentPage"
          :label="page + 1"
          :color="page === currentPage ? 'lightDark' : 'whiteDark'"
          small
          @click="currentPage = page"
        />
      </BaseButtons>
      <small>Page {{ currentPageHuman }} of {{ numPages }}</small>
    </BaseLevel>
  </div>
</template>
