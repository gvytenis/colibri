import { defineStore } from "pinia";
import { parseJwt } from "@/helper/jwtParser";

const BEARER_TOKEN = 'colibri_token';
const USER = 'colibri_user';
const ROLE_ADMIN = 'ROLE_ADMIN';

export const useUserStore = defineStore('user', {
  state: () => ({
    username: null,
    email: null,
  }),
  actions: {
    setToken(token) {
      localStorage.setItem(BEARER_TOKEN, token);
    },
    getToken() {
      return localStorage.getItem(BEARER_TOKEN);
    },
    setUsername(username) {
      localStorage.setItem(USER, username);
    },
    getUsername() {
      return localStorage.getItem(USER);
    },
    loginRequired(to) {
      const publicPages = ['/login'];
      const authenticationRequired = !publicPages.includes(to.path);

      return authenticationRequired && ((!this.getToken() || false) || this.tokenExpired());
    },
    logout() {
      localStorage.removeItem(BEARER_TOKEN);
    },
    tokenExpired() {
      return new Date().getTime() >= parseJwt(this.getToken()).exp * 1000;
    },
    isAdmin() {
      return ROLE_ADMIN === parseJwt(this.getToken()).roles[0];
    },
  },
});
