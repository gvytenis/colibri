<script setup>
import { computed, ref } from "vue";
import { useMainStore } from "@/stores/main";
import { mdiBookInformationVariant, mdiCartCheck, mdiEye, mdiPencil, mdiTrashCan } from "@mdi/js";
import CardBoxModal from "@/components/card/CardBoxModal.vue";
import BaseLevel from "@/components/base/BaseLevel.vue";
import BaseButtons from "@/components/base/BaseButtons.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import CardBoxDeleteModal from "@/components/card/CardBoxDeleteModal.vue";
import CardBoxFormModal from "@/components/card/CardBoxFormModal.vue";
import FormBook from "@/components/form/FormBook.vue";
import IconRounded from "@/components/icon/IconRounded.vue";
import { useUserStore } from "@/stores/user";
import FormReserveBook from "@/components/form/FormReserveBook.vue";

const mainStore = useMainStore();
const userStore = useUserStore();
const items = computed(() => mainStore.books);

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

const infoModalActive = ref(false);
const createModalActive = ref(false);
const editModalActive = ref(false);
const deleteModalActive = ref(false);
const reserveModalActive = ref(false);

const modalItemId = ref(0);
const infoModalData = ref([]);
const editModalFormData = ref([]);
const reserveModalFormData = ref([]);

const showInfoModal = (data) => {
  infoModalData.value = data;
  infoModalActive.value = true;
};

const showEditModal = (data) => {
  editModalFormData.value = data;
  editModalActive.value = true;
};

const showDeleteModal = itemId => {
  modalItemId.value = itemId;
  deleteModalActive.value = true;
};

const showReserveModal = data => {
  reserveModalFormData.value = data;
  reserveModalActive.value = true;
};

const tableIcon = ref(mdiBookInformationVariant);
</script>

<template>
  <CardBoxModal v-model="infoModalActive" title="Details" button-label="Close">
    <p><b>Title:</b> {{ infoModalData.title ?? '' }}</p>
    <p><b>Year:</b> {{ infoModalData.year ?? '' }}</p>
    <p><b>Category:</b> {{ infoModalData.category?.name ?? '' }}</p>
    <p><b>Author:</b> {{ infoModalData.author?.name ?? '' }}</p>
  </CardBoxModal>

  <CardBoxFormModal
      v-model="createModalActive"
      title="Create"
  >
    <FormBook v-model:createModalActive="createModalActive" type="create"/>
  </CardBoxFormModal>

  <CardBoxFormModal
      v-model="editModalActive"
      title="Edit"
  >
    <FormBook v-model:editModalActive="editModalActive" type="edit" :data="editModalFormData"/>
  </CardBoxFormModal>

  <CardBoxDeleteModal
      v-model="deleteModalActive"
      title="Please confirm"
      deletable-type="book"
      :deletable-id="modalItemId"
  >
    <p>Are you sure you want to delete this item?</p>
  </CardBoxDeleteModal>

  <CardBoxFormModal
      v-model="reserveModalActive"
      title="Reserve"
  >
    <FormReserveBook v-model:reserveModalActive="reserveModalActive" :data="reserveModalFormData"/>
  </CardBoxFormModal>
  <section class="mb-6 flex items-center justify-between p-3 pt-10">
    <div class="flex items-center justify-start">
      <IconRounded :icon="tableIcon" class="md:mr-6"/>
      <h1 class="text-3xl leading-tight">Books</h1>
    </div>
    <div class="flex items-center justify-end">
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
  </section>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Year</th>
        <th>Category</th>
        <th>Author</th>
        <th />
      </tr>
    </thead>
    <tbody>
      <tr v-for="book in itemsPaginated" :key="book.id">
        <td data-label="ID">
          {{ book.id }}
        </td>
        <td data-label="Title">
          {{ book.title }}
        </td>
        <td data-label="Year">
          {{ book.year }}
        </td>
        <td data-label="Category">
          {{ book.category.name }}
        </td>
        <td data-label="Author">
          {{ book.author.name }}
        </td>
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons type="justify-start lg:justify-end" no-wrap>
            <BaseButton
              color="info"
              :icon="mdiEye"
              small
              @click="showInfoModal(book)"
            />
            <BaseButton
                color="warning"
                :icon="mdiPencil"
                small
                @click="showEditModal(book)"
                v-if="userStore.isAdmin()"
            />
            <BaseButton
                color="danger"
                :icon="mdiTrashCan"
                small
                @click="showDeleteModal(book.id)"
                v-if="userStore.isAdmin()"
            />
            <BaseButton
                color="success"
                :icon="mdiCartCheck"
                small
                @click="showReserveModal(book)"
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
