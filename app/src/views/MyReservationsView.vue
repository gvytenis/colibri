<script setup>
import SectionMain from "@/components/section/SectionMain.vue";
import CardBox from "@/components/card/CardBox.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import TableMyReservations from "@/components/table/TableMyReservations.vue";
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
