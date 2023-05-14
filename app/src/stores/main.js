import { defineStore } from "pinia";

import { GET_COLLECTION_AUTHORS } from "@/graphql/query/authors";
import { GET_COLLECTION_CATEGORIES } from "@/graphql/query/categories";
import { GET_COLLECTION_BOOKS } from "@/graphql/query/books";
import { GET_COLLECTION_RESERVATIONS } from "@/graphql/query/reservations";
import { GET_COLLECTION_USERS } from "@/graphql/query/users";
import { parseJwt } from "@/helper/jwtParser";

const BASE_API_URL = `http://colibri.backend.localhost`;

const query = (graphqlQuery) => fetch(BASE_API_URL, {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' + localStorage.getItem('colibri_token'),
  },
  body: JSON.stringify({
    variables: {},
    query: graphqlQuery,
  }),
}).then(result => result.json());

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
      query(GET_COLLECTION_AUTHORS)
      .then(result => {
        this.authors = result.data.getAuthors.authors;
      });
    },
    fetchCategories() {
      query(GET_COLLECTION_CATEGORIES)
        .then(result => {
          this.categories = result.data.getCategories.categories;
        });
    },
    fetchBooks() {
      query(GET_COLLECTION_BOOKS)
        .then(result => {
          this.books = result.data.getBooks.books;
        });
    },
    fetchReservations() {
      query(GET_COLLECTION_RESERVATIONS)
        .then(result => {
          this.reservations = result.data.getReservations.reservations;
        });
    },
    fetchUsers() {
      query(GET_COLLECTION_USERS)
        .then(result => {
          this.users = result.data.getUsers.users;
        });
    },
  },
});
