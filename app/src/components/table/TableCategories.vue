<script setup>
import { computed, ref } from "vue";
import { useMainStore } from "@/stores/main";
import { mdiCog, mdiEye, mdiPencil, mdiTrashCan } from "@mdi/js";
import CardBoxModal from "@/components/card/CardBoxModal.vue";
import BaseLevel from "@/components/base/BaseLevel.vue";
import BaseButtons from "@/components/base/BaseButtons.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import CardBoxDeleteModal from "@/components/card/CardBoxDeleteModal.vue";
import FormCategory from "@/components/form/FormCategory.vue";
import CardBoxFormModal from "@/components/card/CardBoxFormModal.vue";
import IconRounded from "@/components/icon/IconRounded.vue";

const mainStore = useMainStore();
const items = computed(() => mainStore.categories);

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

const tableIcon = ref(mdiCog);
</script>

<template>
  <CardBoxFormModal
      v-model="createModalActive"
      title="Create"
  >
    <FormCategory v-model:createModalActive="createModalActive" type="create"/>
  </CardBoxFormModal>

  <CardBoxModal v-model="isModalActive" title="Sample modal">
    <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
    <p>This is sample modal</p>
  </CardBoxModal>

  <CardBoxFormModal
      v-model="editModalActive"
      title="Edit"
  >
    <FormCategory v-model:editModalActive="editModalActive" type="edit" :data="editModalFormData"/>
  </CardBoxFormModal>

  <CardBoxDeleteModal
      v-model="deleteModalActive"
      title="Please confirm"
      deletable-type="category"
      :deletable-id="modalItemId"
  >
    <p>Are you sure you want to delete this item?</p>
  </CardBoxDeleteModal>
  <section class="mb-6 flex items-center justify-between p-3 pt-10">
    <div class="flex items-center justify-start">
      <IconRounded :icon="tableIcon" class="md:mr-6"/>
      <h1 class="text-3xl leading-tight">Categories</h1>
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
        <th>Name</th>
        <th />
      </tr>
    </thead>
    <tbody>
      <tr v-for="category in itemsPaginated" :key="category.id">
        <td data-label="ID">
          {{ category.id }}
        </td>
        <td data-label="Name">
          {{ category.name }}
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
                @click="showEditModal(category)"
            />
            <BaseButton
                color="danger"
                :icon="mdiTrashCan"
                small
                @click="showDeleteModal(category.id)"
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
