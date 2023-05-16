<script setup>
import { computed, ref } from "vue";
import { useMainStore } from "@/stores/main";
import { mdiEye, mdiPencil, mdiTrashCan } from "@mdi/js";
import CardBoxModal from "@/components/card/CardBoxModal.vue";
import BaseLevel from "@/components/base/BaseLevel.vue";
import BaseButtons from "@/components/base/BaseButtons.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import CardBoxDeleteModal from "@/components/card/CardBoxDeleteModal.vue";
import FormReservation from "@/components/form/FormReservation.vue";
import CardBoxFormModal from "@/components/card/CardBoxFormModal.vue";

const mainStore = useMainStore();
const items = computed(() => mainStore.reservations);

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

const isModalActive = ref(false);
const createModalActive = ref(false);
const editModalActive = ref(false);
const deleteModalActive = ref(false);

const modalItemId = ref(0);
const editModalFormData = ref([]);

const showEditModal = (data) => {
  editModalFormData.value = data;
  editModalActive.value = true;
};

const showDeleteModal = itemId => {
  modalItemId.value = itemId;
  deleteModalActive.value = true;
};
</script>

<template>
  <div class="p-5 lg:px-6 border-t border-gray-100 dark:border-slate-800 flex" style="justify-content: end;">
    <BaseLevel>
      <BaseButton
          color="info"
          :icon="mdiEye"
          small
          @click="createModalActive = true"
          label="Create"
      />
    </BaseLevel>
  </div>
  <CardBoxFormModal
      v-model="createModalActive"
      title="Create"
  >
    <FormReservation v-model:createModalActive="createModalActive" type="create"/>
  </CardBoxFormModal>

  <CardBoxModal v-model="isModalActive" title="Sample modal">
    <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
    <p>This is sample modal</p>
  </CardBoxModal>

  <CardBoxFormModal
      v-model="editModalActive"
      title="Edit"
  >
    <FormReservation v-model:editModalActive="editModalActive" type="edit" :data="editModalFormData"/>
  </CardBoxFormModal>

  <CardBoxDeleteModal
      v-model="deleteModalActive"
      title="Please confirm"
      deletable-type="reservation"
      :deletable-id="modalItemId"
  >
    <p>Are you sure you want to delete this item?</p>
  </CardBoxDeleteModal>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Year</th>
        <th>Category</th>
        <th>Author</th>
        <th>User</th>
        <th />
      </tr>
    </thead>
    <tbody>
      <tr v-for="reservation in itemsPaginated" :key="reservation.id">
        <td data-label="ID">
          {{ reservation.id }}
        </td>
        <td data-label="Title">
          {{ reservation.book.title }}
        </td>
        <td data-label="BookYear">
          {{ reservation.book.year }}
        </td>
        <td data-label="Category">
          {{ reservation.book.category.name }}
        </td>
        <td data-label="Author">
          {{ reservation.book.author.name }}
        </td>
        <td data-label="User">
          {{ reservation.user.name }}
        </td>
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons type="justify-start lg:justify-end" no-wrap>
            <BaseButton
              color="info"
              :icon="mdiEye"
              small
              @click="isModalActive = true"
            />
            <BaseButton
                color="warning"
                :icon="mdiPencil"
                small
                @click="showEditModal(reservation)"
            />
            <BaseButton
                color="danger"
                :icon="mdiTrashCan"
                small
                @click="showDeleteModal(reservation.id)"
            />
          </BaseButtons>
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
