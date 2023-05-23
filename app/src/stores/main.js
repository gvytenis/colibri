import { defineStore } from "pinia";

import { GET_COLLECTION_AUTHORS } from "@/graphql/query/author/getAuthors";
import { GET_COLLECTION_CATEGORIES } from "@/graphql/query/category/getCategories";
import { GET_COLLECTION_BOOKS } from "@/graphql/query/book/getBooks";
import { GET_COLLECTION_RESERVATIONS } from "@/graphql/query/reservation/getReservations";
import { GET_COLLECTION_USERS } from "@/graphql/query/user/getUsers";
import { parseJwt } from "@/helper/jwtParser";
import { graphQlQuery } from "@/graphql/graphQlQuery";
import { API_URL, ROLES, STORAGE_KEY } from "@/constants";
import { GET_COLLECTION_MY_RESERVATIONS } from "@/graphql/query/reservation/getMyReservations";

export const useMainStore = defineStore("main", {
  state: () => ({
    /* User */
    userName: null,
    userEmail: null,
    userAvatar: null,

    user: null,
    token: null,

    /* Field focus with ctrl+k (to register only once) */
    isFieldFocusRegistered: false,

    authors: [],
    categories: [],
    books: [],
    reservations: [],
    myReservations: [],
    users: [],
  }),
  actions: {
    setToken(token) {
      this.token = token;
    },
    getToken() {
      return localStorage.getItem(STORAGE_KEY.token);
    },
    setUser(payload) {
      if (payload.name) {
        this.userName = payload.name;
      }
      if (payload.email) {
        this.userEmail = payload.email;
      }
      if (payload.avatar) {
        this.userAvatar = payload.avatar;
      }
    },
    populateData(token) {
      const parsedToken = parseJwt(token);

      this.fetchAuthors();
      this.fetchCategories();
      this.fetchBooks();
      this.fetchMyReservations();

      if (ROLES.admin === parsedToken.roles[0]) {
        this.fetchReservations();
        this.fetchUsers();
      }
    },
    fetchAuthors() {
      graphQlQuery(API_URL.base, GET_COLLECTION_AUTHORS, this.getToken())
      .then(result => {
        this.authors = result.data.getAuthors.authors;
      });
    },
    fetchCategories() {
      graphQlQuery(API_URL.base, GET_COLLECTION_CATEGORIES, this.getToken())
        .then(result => {
          this.categories = result.data.getCategories.categories;
        });
    },
    fetchBooks() {
      graphQlQuery(API_URL.base, GET_COLLECTION_BOOKS, this.getToken())
        .then(result => {
          this.books = result.data.getBooks.books;
        });
    },
    fetchReservations() {
      graphQlQuery(API_URL.base, GET_COLLECTION_RESERVATIONS, this.getToken())
        .then(result => {
          this.reservations = result.data.getReservations.reservations;
        });
    },
    fetchMyReservations() {
      graphQlQuery(API_URL.base, GET_COLLECTION_MY_RESERVATIONS(localStorage.getItem(STORAGE_KEY.userId)), this.getToken())
        .then(result => {
          this.myReservations = result.data.getMyReservations.reservations;
        });
    },
    fetchUsers() {
      graphQlQuery(API_URL.base, GET_COLLECTION_USERS, this.getToken())
        .then(result => {
          this.users = result.data.getUsers.users;
        });
    },
  },
});
