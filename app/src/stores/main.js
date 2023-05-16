import { defineStore } from "pinia";

import { GET_COLLECTION_AUTHORS } from "@/graphql/query/author/getAuthors";
import { GET_COLLECTION_CATEGORIES } from "@/graphql/query/category/getCategories";
import { GET_COLLECTION_BOOKS } from "@/graphql/query/book/getBooks";
import { GET_COLLECTION_RESERVATIONS } from "@/graphql/query/reservation/getReservations";
import { GET_COLLECTION_USERS } from "@/graphql/query/user/getUsers";
import { parseJwt } from "@/helper/jwtParser";
import { graphQlQuery } from "@/graphql/graphQlQuery";

const BASE_API_URL = `http://colibri.backend.localhost`;

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
    users: [],
  }),
  actions: {
    setToken(token) {
      this.token = token;
    },
    getToken() {
      return localStorage.getItem('colibri_token');
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

      if ('ROLE_ADMIN' === parsedToken.roles[0]) {
        this.fetchReservations();
        this.fetchUsers();
      }
    },
    fetchAuthors() {
      graphQlQuery(BASE_API_URL, GET_COLLECTION_AUTHORS, this.getToken())
      .then(result => {
        this.authors = result.data.getAuthors.authors;
      });
    },
    fetchCategories() {
      graphQlQuery(BASE_API_URL, GET_COLLECTION_CATEGORIES, this.getToken())
        .then(result => {
          this.categories = result.data.getCategories.categories;
        });
    },
    fetchBooks() {
      graphQlQuery(BASE_API_URL, GET_COLLECTION_BOOKS, this.getToken())
        .then(result => {
          this.books = result.data.getBooks.books;
        });
    },
    fetchReservations() {
      graphQlQuery(BASE_API_URL, GET_COLLECTION_RESERVATIONS, this.getToken())
        .then(result => {
          this.reservations = result.data.getReservations.reservations;
        });
    },
    fetchUsers() {
      graphQlQuery(BASE_API_URL, GET_COLLECTION_USERS, this.getToken())
        .then(result => {
          this.users = result.data.getUsers.users;
        });
    },
  },
});
