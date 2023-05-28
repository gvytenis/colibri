<script setup>
import SectionMain from "@/components/section/SectionMain.vue";
import TableBooks from "@/components/table/TableBooks.vue";
import CardBox from "@/components/card/CardBox.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";

import { onMounted } from "vue";
import { useMainStore } from "@/stores/main";

const mainStore = useMainStore();

onMounted(() => {
  mainStore.fetchBooks();
});
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <CardBox class="mb-6" has-table>
        <TableBooks />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
