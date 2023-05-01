<script setup>
import {
  mdiMonitorCellphone,
  mdiTableBorder,
  mdiTableOff,
  mdiGithub,
} from "@mdi/js";
import SectionMain from "@/components/section/SectionMain.vue";
import NotificationBar from "@/components/notification-bar/NotificationBar.vue";
import TableAuthors from "@/components/table/TableAuthors.vue";
import TableSampleClients from "@/components/table/TableSampleClients.vue";
import CardBox from "@/components/card/CardBox.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/components/section/SectionTitleLineWithButton.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import CardBoxComponentEmpty from "@/components/card/CardBoxComponentEmpty.vue";
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <CardBox class="mb-6" has-table>
<!--        <TableSampleClients />-->
        <TableAuthors />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
