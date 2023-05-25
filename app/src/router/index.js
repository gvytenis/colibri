import { createRouter, createWebHashHistory } from "vue-router";

const routes = [
  {
    meta: {
      title: "Dashboard",
    },
    path: "/",
    name: "index",
    component: () => import("@/views/HomeView.vue"),
  },
  {
    meta: {
      title: "Dashboard",
    },
    path: "/dashboard",
    name: "dashboard",
    component: () => import("@/views/HomeView.vue"),
  },
  {
    meta: {
      title: "Authors",
    },
    path: "/authors",
    name: "authors",
    component: () => import("@/views/AuthorsView.vue"),
  },
  {
    meta: {
      title: "Books",
    },
    path: "/books",
    name: "books",
    component: () => import("@/views/BooksView.vue"),
  },
  {
    meta: {
      title: "Categories",
    },
    path: "/categories",
    name: "categories",
    component: () => import("@/views/CategoriesView.vue"),
  },
  {
    meta: {
      title: "Reservations",
    },
    path: "/reservations",
    name: "reservations",
    component: () => import("@/views/ReservationsView.vue"),
  },
  {
    meta: {
      title: "My Reservations",
    },
    path: "/my-reservations",
    name: "myReservations",
    component: () => import("@/views/MyReservationsView.vue"),
  },
  {
    meta: {
      title: "Users",
    },
    path: "/users",
    name: "users",
    component: () => import("@/views/UsersView.vue"),
  },
  {
    meta: {
      title: "Login",
    },
    path: "/login",
    name: "login",
    component: () => import("@/views/LoginView.vue"),
  },
  {
    meta: {
      title: "Profile",
    },
    path: "/profile",
    name: "profile",
    component: () => import("@/views/ProfileView.vue"),
  },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { top: 0 };
  },
});

export default router;
