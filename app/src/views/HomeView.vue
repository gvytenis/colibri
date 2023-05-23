<script setup>
import { computed, ref, onMounted } from "vue";
import { useMainStore } from "@/stores/main";
import {
  mdiAccountMultiple,
  mdiCartOutline,
  mdiChartTimelineVariant,
  mdiMonitorCellphone,
  mdiReload,
  mdiGithub,
  mdiChartPie, mdiFormatListGroup, mdiBookAccount, mdiAccountArrowUp,
} from "@mdi/js";
import * as chartConfig from "@/components/Charts/chart.config.js";
import LineChart from "@/components/Charts/LineChart.vue";
import SectionMain from "@/components/section/SectionMain.vue";
import CardBoxWidget from "@/components/card/CardBoxWidget.vue";
import CardBox from "@/components/card/CardBox.vue";
import NotificationBar from "@/components/notification-bar/NotificationBar.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import CardBoxTransaction from "@/components/card/CardBoxTransaction.vue";
import CardBoxClient from "@/components/card/CardBoxClient.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/section/SectionTitleLineWithButton.vue";
import SectionBannerStarOnGitHub from "@/components/section/SectionBannerStarOnGitHub.vue";
import TableReservations from "@/components/table/TableReservations.vue";
import {useUserStore} from "@/stores/user";
import TableDashboardReservations from "@/components/table/TableDashboardReservations.vue";

const chartData = ref(null);
const mainStore = useMainStore();
const userStore = useUserStore();

const fillChartData = () => {
  chartData.value = chartConfig.sampleChartData();
};

onMounted(() => {
  fillChartData();
});

const myReservationCount = ref(mainStore.myReservations.length);
const totalAuthorCount = ref(mainStore.authors.length);
const totalBookCount = ref(mainStore.books.length);

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
