<script setup>
import SectionMain from "@/components/section/SectionMain.vue";
import CardBox from "@/components/card/CardBox.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import TableMyReservations from "@/components/table/TableMyReservations.vue";

import { onMounted } from "vue";
import { useMainStore } from "@/stores/main";

const mainStore = useMainStore();

onMounted(() => {
  mainStore.fetchMyReservations();
});
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <CardBox class="mb-6" has-table>
        <TableMyReservations />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
