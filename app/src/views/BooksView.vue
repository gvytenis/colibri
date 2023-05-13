<script setup>
import SectionMain from "@/components/section/SectionMain.vue";
import TableBooks from "@/components/table/TableBooks.vue";
import CardBox from "@/components/card/CardBox.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
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
