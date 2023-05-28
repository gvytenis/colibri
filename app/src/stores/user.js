import { defineStore } from "pinia";
import { parseJwt } from "@/helper/jwtParser";
import {API_URL, ROLES, STORAGE_KEY} from "@/constants";

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
    setUserFullName(name) {
      localStorage.setItem(STORAGE_KEY.user, name);
    },
    setUsername(username) {
      localStorage.setItem(STORAGE_KEY.username, username);
    },
    setUserId(id) {
      localStorage.setItem(STORAGE_KEY.userId, id);
    },
    setEmail(email) {
      localStorage.setItem(STORAGE_KEY.email, email);
    },
    getUserId() {
      return localStorage.getItem(STORAGE_KEY.userId);
    },
    getUserFullName() {
      return localStorage.getItem(STORAGE_KEY.user);
    },
    getUsername() {
      return localStorage.getItem(STORAGE_KEY.username);
    },
    getEmail() {
      return localStorage.getItem(STORAGE_KEY.email);
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
    login(username, password) {
      return fetch(API_URL.login, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          username: username,
          password: password,
        }),
      }).then(result => result.json())
    }
  },
});
