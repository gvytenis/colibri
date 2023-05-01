import {
  mdiAccountCircle,
  mdiMonitor,
    mdiBookInformationVariant,
    mdiAccountBadge,
    mdiAccountGroup,
    mdiCog,
  mdiGithub,
  mdiLock,
  mdiAlertCircle,
  mdiSquareEditOutline,
  mdiTable,
  mdiViewList,
  mdiTelevisionGuide,
  mdiResponsive,
  mdiPalette,
  mdiReact,
} from "@mdi/js";

export default [
  {
    to: "/dashboard",
    icon: mdiMonitor,
    label: "Dashboard",
  },
  {
    to: "/authors",
    label: "Authors",
    icon: mdiAccountBadge,
  },
  {
    to: "/books",
    label: "Books",
    icon: mdiBookInformationVariant,
  },
  {
    to: "/categories",
    label: "Categories",
    icon: mdiCog,
  },
  {
    to: "/reservations",
    label: "Reservations",
    icon: mdiViewList,
  },
  {
    to: "/users",
    label: "Users",
    icon: mdiAccountGroup,
  },
  {
    to: "/login",
    label: "Login",
    icon: mdiLock,
  },
  {
    to: "/profile",
    label: "Profile",
    icon: mdiAccountCircle,
  },
  {
    to: "/forms",
    label: "Forms",
    icon: mdiSquareEditOutline,
  },
];
