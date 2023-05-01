import { fileURLToPath, URL } from "node:url";

import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

// https://vitejs.dev/config/
export default defineConfig({
  // base: "/admin-one-vue-tailwind/",
  base: "/",
  plugins: [vue()],
  server: {
    host: true,
    port: 8080, // This is the port which we will use in docker
  },
  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./src", import.meta.url)),
    },
  },
});

// export default defineConfig({
//   plugins: [react()],
//   server: {
//     host: true,
//     port: 8000, // This is the port which we will use in docker
//   }
// })