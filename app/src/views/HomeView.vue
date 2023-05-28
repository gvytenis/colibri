<script setup>
import { ref } from "vue";
import { useMainStore } from "@/stores/main";
import {
  mdiAccountMultiple,
  mdiChartTimelineVariant,
  mdiFormatListGroup,
  mdiAccountArrowUp,
} from "@mdi/js";
import SectionMain from "@/components/section/SectionMain.vue";
import CardBoxWidget from "@/components/card/CardBoxWidget.vue";
import CardBox from "@/components/card/CardBox.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/section/SectionTitleLineWithButton.vue";
import { useUserStore } from "@/stores/user";
import TableDashboardReservations from "@/components/table/TableDashboardReservations.vue";

import { onMounted } from "vue";

const mainStore = useMainStore();
const userStore = useUserStore();

const myReservationCount = ref(mainStore.myReservations.length);
const totalAuthorCount = ref(mainStore.authors.length);
const totalBookCount = ref(mainStore.books.length);

onMounted(() => {
  mainStore.fetchMyReservations();
});

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiChartTimelineVariant"
        title="Overview"
        main
      >
      </SectionTitleLineWithButton>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-6">
        <CardBoxWidget
          color="text-emerald-500"
          :icon="mdiAccountMultiple"
          :number="myReservationCount"
          label="Your total reservations"
        />
        <CardBoxWidget
          color="text-blue-500"
          :icon="mdiAccountArrowUp"
          :number="totalAuthorCount"
          label="Total authors"
        />
        <CardBoxWidget
          color="text-red-500"
          :icon="mdiFormatListGroup"
          :number="totalBookCount"
          label="Books in library"
        />
      </div>

      <SectionTitleLineWithButton :icon="mdiAccountMultiple" title="My reservations" />

      <CardBox has-table>
        <TableDashboardReservations />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
