import { defineStore } from "pinia";
import { parseJwt } from "@/helper/jwtParser";
import { ROLES, STORAGE_KEY } from "@/constants";

export const useUserStore = defineStore('user', {
  state: () => ({
    username: null,
    email: null,
  }),
  actions: {
    setToken(token) {
      localStorage.setItem(STORAGE_KEY.token, token);
    },
    getToken() {
      return localStorage.getItem(STORAGE_KEY.token);
    },
    setUsername(username) {
      localStorage.setItem(STORAGE_KEY.user, username);
    },
    getUsername() {
      return localStorage.getItem(STORAGE_KEY.user);
    },
    loginRequired(to) {
      const publicPages = ['/login'];
      const authenticationRequired = !publicPages.includes(to.path);

      return authenticationRequired && ((!this.getToken() || false) || this.tokenExpired());
    },
    logout() {
      localStorage.removeItem(STORAGE_KEY.token);
    },
    tokenExpired() {
      return new Date().getTime() >= parseJwt(this.getToken()).exp * 1000;
    },
    isAdmin() {
      return ROLES.admin === parseJwt(this.getToken()).roles[0];
    },
    isLoggedIn() {
      return null !== localStorage.getItem(STORAGE_KEY.token);
    },
  },
});
