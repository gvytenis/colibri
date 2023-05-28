<script setup>
import SectionMain from "@/components/section/SectionMain.vue";
import CardBox from "@/components/card/CardBox.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import TableReservations from "@/components/table/TableReservations.vue";

import { onMounted } from "vue";
import { useMainStore } from "@/stores/main";

const mainStore = useMainStore();

onMounted(() => {
  mainStore.fetchReservations();
});
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <CardBox class="mb-6" has-table>
        <TableReservations />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
