import { createApp, h, provide } from "vue";
import { createPinia } from "pinia";
import { DefaultApolloClient } from '@vue/apollo-composable'
import { ApolloClient, InMemoryCache } from '@apollo/client/core'

import App from "./App.vue";
import router from "./router";
import { useMainStore } from "@/stores/main.js";
import { useStyleStore } from "@/stores/style.js";
import { darkModeKey, styleKey } from "@/config.js";

import "./css/main.css";
import { useUserStore } from "@/stores/user";
import { API_URL, APP_NAME } from "@/constants";

/* Init Apollo Client */
const cache = new InMemoryCache()
const apolloClient = new ApolloClient({
  cache,
  uri: API_URL.base,
})

/* Init Pinia */
const pinia = createPinia();

/* Create Vue app */
const app = createApp({
  setup () {
    provide(DefaultApolloClient, apolloClient)
  },

  render: () => h(App),
});

app.use(router);
app.use(pinia);

app.config.warnHandler = () => null;

app.mount('#app');

/* Init Pinia stores */
const mainStore = useMainStore(pinia);
const styleStore = useStyleStore(pinia);
const userStore = useUserStore(pinia);

router.beforeEach(async (to, from) => {
  if (userStore.loginRequired(to)) {
    localStorage.setItem('return_url', to.fullPath);

    return '/login';
  } else if ('login' === to.name && userStore.isLoggedIn() && !userStore.tokenExpired()) {
    return to.fullPath;
  }
});

/* App style */
styleStore.setStyle(localStorage[styleKey] ?? "basic");

/* Dark mode */
if (
  (!localStorage[darkModeKey] &&
    window.matchMedia("(prefers-color-scheme: dark)").matches) ||
  localStorage[darkModeKey] === "1"
) {
  styleStore.setDarkMode(true);
}

/* Set document title from route meta */
router.afterEach((to) => {
  document.title = to.meta?.title
    ? `${to.meta.title} — ${APP_NAME}`
    : APP_NAME;
});
