import {
  mdiAccountCircle,
  mdiMonitor,
  mdiBookInformationVariant,
  mdiAccountBadge,
  mdiAccountGroup,
  mdiCog,
  mdiViewList,
} from "@mdi/js";

export default [
  {
    to: "/dashboard",
    icon: mdiMonitor,
    label: "Dashboard",
    adminRoute: false,
  },
  {
    to: "/authors",
    label: "Authors",
    icon: mdiAccountBadge,
    adminRoute: true,
  },
  {
    to: "/books",
    label: "Books",
    icon: mdiBookInformationVariant,
    adminRoute: false,
  },
  {
    to: "/categories",
    label: "Categories",
    icon: mdiCog,
    adminRoute: true,
  },
  {
    to: "/reservations",
    label: "Reservations",
    icon: mdiViewList,
    adminRoute: true,
  },
  {
    to: "/my-reservations",
    label: "My Reservations",
    icon: mdiViewList,
    adminRoute: false,
  },
  {
    to: "/users",
    label: "Users",
    icon: mdiAccountGroup,
    adminRoute: true,
  },
  {
    to: "/profile",
    label: "Profile",
    icon: mdiAccountCircle,
    adminRoute: false,
  },
];
