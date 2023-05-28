<script setup>
import SectionMain from "@/components/section/SectionMain.vue";
import TableUsers from "@/components/table/TableUsers.vue";
import CardBox from "@/components/card/CardBox.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";

import { onMounted } from "vue";
import { useMainStore } from "@/stores/main";

const mainStore = useMainStore();

onMounted(() => {
  mainStore.fetchUsers();
});
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <CardBox class="mb-6" has-table>
        <TableUsers />
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
