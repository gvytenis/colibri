<script setup>
defineProps({
  noPadding: Boolean,
});
</script>

<template>
  <div class="pt-6" :class="{ 'p-6': !noPadding }">
    <slot />
  </div>
</template>
