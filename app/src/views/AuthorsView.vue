<script setup>
import SectionMain from "@/components/section/SectionMain.vue";
import TableAuthors from "@/components/table/TableAuthors.vue";
import CardBox from "@/components/card/CardBox.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <CardBox class="mb-6" has-table>
        <TableAuthors />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
