<script setup>
import SectionMain from "@/components/section/SectionMain.vue";
import TableCategories from "@/components/table/TableCategories.vue";
import CardBox from "@/components/card/CardBox.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <CardBox class="mb-6" has-table>
        <TableCategories />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
