<script setup>
import { containerMaxW } from "@/config.js";
import BaseLevel from "@/components/base/BaseLevel.vue";
import JustboilLogo from "@/components/JustboilLogo.vue";
import { APP_NAME } from "@/constants";

const year = new Date().getFullYear();
</script>

<template>
  <footer class="py-2 px-6" :class="containerMaxW">
    <BaseLevel>
      <div class="text-center md:text-left">
        <b>&copy; {{ year }} {{ APP_NAME }}</b>
        <slot />
      </div>
      <div class="md:py-2">
        Template: <a href="https://justboil.me/" target="_blank">JustBoil.me</a>
      </div>
    </BaseLevel>
  </footer>
</template>
