<script setup>
import AsideMenuItem from "@/components/aside-menu/AsideMenuItem.vue";
import { ref } from "vue";
import { useUserStore } from "@/stores/user";

const userStore = useUserStore();

const props = defineProps({
  isDropdownList: Boolean,
  menu: {
    type: Array,
    required: true,
  },
});

const userMenu = ref(props.menu.filter(route => {
  return !route.adminRoute;
}));

const sideMenu = userStore.isAdmin() ? props.menu : userMenu;

const emit = defineEmits(["menu-click"]);

const menuClick = (event, item) => {
  emit("menu-click", event, item);
};
</script>

<template>
  <ul>
    <AsideMenuItem
      v-for="(item, index) in sideMenu"
      :key="index"
      :item="item"
      :is-dropdown-list="isDropdownList"
      @menu-click="menuClick"
    />
  </ul>
</template>
